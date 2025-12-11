"""
UNIVERSAL Scraper API untuk Laravel Integration (BPS Revamp)
Support: News, Press Release, Publication, Infographic

Usage: python scraper_api.py "https://kebumenkab.bps.go.id/..."
"""

import asyncio
import json
import sys
import re
import io
from urllib.parse import urljoin, urlparse, parse_qs, unquote
from playwright.async_api import async_playwright
from datetime import datetime

# Fix encoding untuk Windows Console
if sys.platform == 'win32':
    sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8', errors='replace')
    sys.stderr = io.TextIOWrapper(sys.stderr.buffer, encoding='utf-8', errors='replace')

# ==========================================
# KONFIGURASI
# ==========================================
BASE = "https://kebumenkab.bps.go.id"
HEADLESS_MODE = True  # Set True untuk di VPS/Laravel, False untuk debug

# ==========================================
# HELPER FUNCTIONS
# ==========================================
def absolutize(href):
    if not href or href.startswith("http"): return href
    return urljoin(BASE, href)

def clean_next_image(src):
    """Membersihkan URL gambar dari proxy Next.js agar dapat resolusi asli"""
    if not src or "/_next/image" not in src: return src
    try:
        parsed = urlparse(src)
        params = parse_qs(parsed.query)
        if "url" in params: return unquote(params["url"][0])
    except: pass
    return src

def detect_type(url):
    if "/publication/" in url: return "publication"
    if "/pressrelease/" in url: return "pressrelease"
    if "/news/" in url: return "news"
    if "/infographic" in url: return "infographic"
    return "general"

# ==========================================
# MAIN SCRAPER LOGIC
# ==========================================
async def extract_content(page, url):
    content_type = detect_type(url)
    
    result = {
        "success": True,
        "url": url,
        "type": content_type,
        "title": "",
        "date": "",
        "date_iso": "", # YYYY-MM-DD
        "category": "",
        "content": "",
        "image": "",
        "pdf_url": "",
        "scraped_at": datetime.now().strftime("%Y-%m-%d %H:%M:%S")
    }

    try:
        # Timeout 60s untuk VPS
        await page.goto(url, wait_until="domcontentloaded", timeout=60000)
        await page.wait_for_timeout(2000) # Tunggu render JS
        
        # Close popup BPS jika ada
        try:
            popup = page.locator("button:has-text('Tutup'), button:has-text('Close')").first
            if await popup.is_visible(timeout=1000):
                await popup.click()
        except: pass

        # -------------------------------------------
        # 1. JUDUL (Universal)
        # -------------------------------------------
        
        if content_type == "infographic":
            # Untuk infografik: ambil dari og:title lebih akurat
            try:
                og_title = await page.locator('meta[property="og:title"]').get_attribute("content")
                if og_title:
                    result["title"] = og_title.strip()
            except: pass
            
            # Fallback ke h1 jika og:title kosong
            if not result["title"]:
                title_selectors = ["h1.text-xl", "h1", ".article-title", "main h1"]
                for sel in title_selectors:
                    if await page.locator(sel).count():
                        result["title"] = (await page.locator(sel).first.inner_text()).strip()
                        break
        else:
            # Untuk tipe lainnya: gunakan metode original
            title_selectors = ["h1.text-xl", "h1", ".article-title", "main h1"]
            for sel in title_selectors:
                if await page.locator(sel).count():
                    result["title"] = (await page.locator(sel).first.inner_text()).strip()
                    break

        # -------------------------------------------
        # 2. TANGGAL (Strategi Berlapis)
        # -------------------------------------------
        # Strategi A: Ambil dari URL (Paling Akurat untuk BPS)
        # Format: .../2025/12/01/...
        url_date = re.search(r'/(\d{4})/(\d{2})/(\d{2})/', url)
        if url_date:
            y, m, d = url_date.groups()
            result["date_iso"] = f"{y}-{m}-{d}"
            result["date"] = f"{d}-{m}-{y}" # Format Indo sederhana
        
        # Strategi B: Jika URL gagal, cari di halaman sesuai tipe
        if not result["date"]:
            if content_type == "infographic":
                # Untuk infografik: extract tahun dari judul (misal "...2024", "... 2025")
                # atau dari meta description
                try:
                    if result["title"]:
                        year_match = re.search(r'\b(20\d{2})\b', result["title"])
                        if year_match:
                            year = year_match.group(1)
                            # Default ke 31 Desember tahun tersebut
                            result["date_iso"] = f"{year}-12-31"
                            result["date"] = f"31-12-{year}"
                except: pass
            
            elif content_type == "publication":
                # Publikasi: Biasanya ada di tabel "Tanggal Rilis"
                # Cari text yang mengandung tanggal
                try:
                    meta_text = await page.locator("body").inner_text()
                    date_match = re.search(r'Tanggal Rilis\s*:\s*(\d{1,2}\s+[a-zA-Z]+\s+\d{4})', meta_text, re.IGNORECASE)
                    if date_match:
                        result["date"] = date_match.group(1)
                except: pass
            else:
                # News/Press Release: Format " | "
                meta_loc = page.locator("div.mt-4 p, div.bg-white p").first
                if await meta_loc.count():
                    text = (await meta_loc.inner_text()).strip()
                    if " | " in text:
                        result["date"] = text.split(" | ")[0].strip()
                        result["category"] = text.split(" | ")[1].strip()

        # -------------------------------------------
        # 3. KONTEN / ABSTRAK
        # -------------------------------------------
        # --- UNIVERSAL ABSTRAK/DESKRIPSI ---
        # Prioritas: div.Abstract_abstract__KyuKP, div.Abstract_abstract__J_Qgq
        abstract_html = ""
        for sel in ["div.Abstract_abstract__KyuKP", "div.Abstract_abstract__J_Qgq", "div[class*='abstract']"]:
            el = page.locator(sel).first
            if await el.count():
                abstract_html = (await el.inner_html()).strip()
                break

        # Fallback: meta og:description
        if not abstract_html:
            try:
                og_desc = await page.locator('meta[property="og:description"]').get_attribute("content")
                if og_desc:
                    abstract_html = f"<p>{og_desc}</p>"
            except: pass

        # Fallback: meta name=description
        if not abstract_html:
            try:
                desc = await page.locator('meta[name="description"]').get_attribute("content")
                if desc:
                    abstract_html = f"<p>{desc}</p>"
            except: pass

        # Fallback: body text (jika benar-benar kosong)
        if not abstract_html and content_type != "infographic":
            try:
                body_text = await page.locator("body").inner_text()
                abstract_html = f"<p>{body_text[:500]}</p>" # Ambil 500 karakter pertama
            except: pass

        # Bersihkan ke plain text
        def html_to_text(html):
            # Remove all tags, keep only text
            import re
            # Remove script/style
            html = re.sub(r'<(script|style)[^>]*>.*?</\1>', '', html, flags=re.DOTALL)
            # Remove all tags
            text = re.sub(r'<[^>]+>', '', html)
            # Replace multiple spaces/newlines
            text = re.sub(r'\s+', ' ', text).strip()
            return text

        if content_type == "infographic":
            try:
                desc = await page.locator('meta[name="description"]').get_attribute("content")
                result["content"] = "" # Kosongkan untuk infografik (hanya gambar)
            except: pass
        else:
            result["content"] = html_to_text(abstract_html) if abstract_html else ''

        # -------------------------------------------
        # 4. GAMBAR (Penting)
        # -------------------------------------------
        img_url = ""
        
        if content_type == "publication":
            # Cari gambar cover buku
            cover = page.locator("img[alt='Cover'], img[class*='cover']").first
            if await cover.count():
                img_url = await cover.get_attribute("src")
            
            # Cari link download PDF
            pdf_btn = page.locator("a[href*='download']").first
            if await pdf_btn.count():
                result["pdf_url"] = absolutize(await pdf_btn.get_attribute("href"))
        
        elif content_type == "infographic":
            # Untuk infografik: ambil dari og:image (paling reliable)
            try:
                og_img = await page.locator('meta[property="og:image"]').get_attribute("content")
                if og_img:
                    img_url = og_img
            except: pass
            
            # Fallback: cari gambar di main
            if not img_url:
                info_img = page.locator("main img").first
                if await info_img.count():
                    img_url = await info_img.get_attribute("src")
        
        # Fallback Image (Universal)
        if not img_url:
            img_selectors = ["article img", "main img", "div.mt-4 img"]
            for sel in img_selectors:
                imgs = page.locator(sel)
                if await imgs.count():
                    # Loop cari gambar yang bukan icon
                    for i in range(await imgs.count()):
                        src = await imgs.nth(i).get_attribute("src")
                        if src and "icon" not in src and "logo" not in src:
                            img_url = src
                            break
                    if img_url: break

        # Bersihkan URL Gambar
        if img_url:
            result["image"] = clean_next_image(absolutize(img_url))

    except Exception as e:
        result["success"] = False
        result["error"] = str(e)

    return result

# ==========================================
# SYSTEM ENTRY POINT
# ==========================================
async def main():
    try:
        if len(sys.argv) < 2:
            print(json.dumps({"success": False, "error": "URL parameter missing"}), flush=True)
            sys.exit(1)
        
        url = sys.argv[1].strip()
        
        async with async_playwright() as p:
            browser = await p.chromium.launch(
                headless=HEADLESS_MODE,
                args=['--no-sandbox', '--disable-setuid-sandbox']
            )
            
            context = await browser.new_context(
                user_agent="Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36"
            )
            page = await context.new_page()
            
            # Jalankan ekstraksi
            data = await extract_content(page, url)
            
            # Print JSON Murni dengan flush untuk memastikan output langsung
            print(json.dumps(data, ensure_ascii=False), flush=True)
            
            await browser.close()
    
    except Exception as e:
        # Jika ada error apapun, pastikan tetap output JSON
        error_data = {
            "success": False,
            "error": str(e),
            "error_type": type(e).__name__
        }
        print(json.dumps(error_data, ensure_ascii=False), flush=True)
        sys.exit(1)

if __name__ == "__main__":
    asyncio.run(main())
