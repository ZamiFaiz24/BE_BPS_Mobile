<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>Admin BPS Kebumen API Documentation</title>

    <link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset("/vendor/scribe/css/theme-default.style.css") }}" media="screen">
    <link rel="stylesheet" href="{{ asset("/vendor/scribe/css/theme-default.print.css") }}" media="print">

    <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.10/lodash.min.js"></script>

    <link rel="stylesheet"
          href="https://unpkg.com/@highlightjs/cdn-assets@11.6.0/styles/obsidian.min.css">
    <script src="https://unpkg.com/@highlightjs/cdn-assets@11.6.0/highlight.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jets/0.14.1/jets.min.js"></script>

    <style id="language-style">
        /* starts out as display none and is replaced with js later  */
                    body .content .bash-example code { display: none; }
                    body .content .javascript-example code { display: none; }
            </style>

    <script>
        var tryItOutBaseUrl = "http://localhost";
        var useCsrf = Boolean();
        var csrfUrl = "/sanctum/csrf-cookie";
    </script>
    <script src="{{ asset("/vendor/scribe/js/tryitout-5.6.0.js") }}"></script>

    <script src="{{ asset("/vendor/scribe/js/theme-default-5.6.0.js") }}"></script>

</head>

<body data-languages="[&quot;bash&quot;,&quot;javascript&quot;]">

<a href="#" id="nav-button">
    <span>
        MENU
        <img src="{{ asset("/vendor/scribe/images/navbar.png") }}" alt="navbar-image"/>
    </span>
</a>
<div class="tocify-wrapper">
    
            <div class="lang-selector">
                                            <button type="button" class="lang-button" data-language-name="bash">bash</button>
                                            <button type="button" class="lang-button" data-language-name="javascript">javascript</button>
                    </div>
    
    <div class="search">
        <input type="text" class="search" id="input-search" placeholder="Search">
    </div>

    <div id="toc">
                    <ul id="tocify-header-introduction" class="tocify-header">
                <li class="tocify-item level-1" data-unique="introduction">
                    <a href="#introduction">Introduction</a>
                </li>
                            </ul>
                    <ul id="tocify-header-authenticating-requests" class="tocify-header">
                <li class="tocify-item level-1" data-unique="authenticating-requests">
                    <a href="#authenticating-requests">Authenticating requests</a>
                </li>
                            </ul>
                    <ul id="tocify-header-endpoints" class="tocify-header">
                <li class="tocify-item level-1" data-unique="endpoints">
                    <a href="#endpoints">Endpoints</a>
                </li>
                                    <ul id="tocify-subheader-endpoints" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="endpoints-GETapi-user">
                                <a href="#endpoints-GETapi-user">GET api/user</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-data--dataset_code-">
                                <a href="#endpoints-GETapi-data--dataset_code-">GET api/data/{dataset_code}</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-chart-gender--dataset_code---year-">
                                <a href="#endpoints-GETapi-chart-gender--dataset_code---year-">GET api/chart/gender/{dataset_code}/{year}</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-POSTapi-update-dataset-unit">
                                <a href="#endpoints-POSTapi-update-dataset-unit">Manual update unit untuk dataset tertentu (untuk fix data yang unit-nya kosong)
POST /api/update-dataset-unit
Body: { "dataset_id": 21, "unit": "Orang" }</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-POSTapi-auto-fix-dataset-units">
                                <a href="#endpoints-POSTapi-auto-fix-dataset-units">Auto-fix unit untuk semua dataset berdasarkan nama dataset
POST /api/auto-fix-dataset-units
Ini akan secara otomatis menentukan unit yang sesuai berdasarkan nama dataset</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-dataset-units">
                                <a href="#endpoints-GETapi-dataset-units">Lihat semua unit yang ada di setiap dataset
GET /api/dataset-units</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-dataset-units--datasetId-">
                                <a href="#endpoints-GETapi-dataset-units--datasetId-">Lihat unit untuk dataset tertentu
GET /api/dataset-units/21</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-POSTapi-content-news">
                                <a href="#endpoints-POSTapi-content-news">POST api/content/news</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-POSTapi-content-press-releases">
                                <a href="#endpoints-POSTapi-content-press-releases">POST api/content/press-releases</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-POSTapi-content-infographics">
                                <a href="#endpoints-POSTapi-content-infographics">POST api/content/infographics</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-POSTapi-content-publications">
                                <a href="#endpoints-POSTapi-content-publications">POST api/content/publications</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-content-news">
                                <a href="#endpoints-GETapi-content-news">Get News with pagination, filtering, and sorting</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-content-press-releases">
                                <a href="#endpoints-GETapi-content-press-releases">Get Press Releases with pagination, filtering, and sorting</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-content-infographics">
                                <a href="#endpoints-GETapi-content-infographics">Get Infographics with pagination, filtering, and sorting</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-content-publications">
                                <a href="#endpoints-GETapi-content-publications">Get Publications with pagination, filtering, and sorting</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-homepage-insights-indicators">
                                <a href="#endpoints-GETapi-homepage-insights-indicators">Ambil nilai terbaru dari multiple datasets untuk insight
Contoh: /api/indicators</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-homepage-grid">
                                <a href="#endpoints-GETapi-homepage-grid">Get grid menu of statistics categories with dataset counts</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-homepage-grid--slug-">
                                <a href="#endpoints-GETapi-homepage-grid--slug-">Get detailed list of datasets for a specific grid category</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-datasets-categories">
                                <a href="#endpoints-GETapi-datasets-categories">Get all categories with their subjects for navigation</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-datasets">
                                <a href="#endpoints-GETapi-datasets">Get list of datasets filtered by subject or search query</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-datasets--dataset_id-">
                                <a href="#endpoints-GETapi-datasets--dataset_id-">Get detailed dataset information with table, chart, and insight data</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-datasets--dataset_id--history">
                                <a href="#endpoints-GETapi-datasets--dataset_id--history">Get historical data for a specific dataset</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-datasets--dataset_id--insights">
                                <a href="#endpoints-GETapi-datasets--dataset_id--insights">Get insight and summary information for a specific dataset</a>
                            </li>
                                                                        </ul>
                            </ul>
            </div>

    <ul class="toc-footer" id="toc-footer">
                    <li style="padding-bottom: 5px;"><a href="{{ route("scribe.postman") }}">View Postman collection</a></li>
                            <li style="padding-bottom: 5px;"><a href="{{ route("scribe.openapi") }}">View OpenAPI spec</a></li>
                <li><a href="http://github.com/knuckleswtf/scribe">Documentation powered by Scribe ✍</a></li>
    </ul>

    <ul class="toc-footer" id="last-updated">
        <li>Last updated: December 9, 2025</li>
    </ul>
</div>

<div class="page-wrapper">
    <div class="dark-box"></div>
    <div class="content">
        <h1 id="introduction">Introduction</h1>
<aside>
    <strong>Base URL</strong>: <code>http://localhost</code>
</aside>
<pre><code>This documentation aims to provide all the information you need to work with our API.

&lt;aside&gt;As you scroll, you'll see code examples for working with the API in different programming languages in the dark area to the right (or as part of the content on mobile).
You can switch the language used with the tabs at the top right (or from the nav menu at the top left on mobile).&lt;/aside&gt;</code></pre>

        <h1 id="authenticating-requests">Authenticating requests</h1>
<p>This API is not authenticated.</p>

        <h1 id="endpoints">Endpoints</h1>

    

                                <h2 id="endpoints-GETapi-user">GET api/user</h2>

<p>
</p>



<span id="example-requests-GETapi-user">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/user" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/user"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-user">
            <blockquote>
            <p>Example response (401):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthenticated.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-user" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-user"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-user"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-user" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-user">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-user" data-method="GET"
      data-path="api/user"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-user', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-user"
                    onclick="tryItOut('GETapi-user');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-user"
                    onclick="cancelTryOut('GETapi-user');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-user"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/user</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-user"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-user"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-GETapi-data--dataset_code-">GET api/data/{dataset_code}</h2>

<p>
</p>



<span id="example-requests-GETapi-data--dataset_code-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/data/consequatur" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/data/consequatur"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-data--dataset_code-">
            <blockquote>
            <p>Example response (404):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;No query results for model [App\\Models\\BpsDataset].&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-data--dataset_code-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-data--dataset_code-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-data--dataset_code-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-data--dataset_code-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-data--dataset_code-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-data--dataset_code-" data-method="GET"
      data-path="api/data/{dataset_code}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-data--dataset_code-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-data--dataset_code-"
                    onclick="tryItOut('GETapi-data--dataset_code-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-data--dataset_code-"
                    onclick="cancelTryOut('GETapi-data--dataset_code-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-data--dataset_code-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/data/{dataset_code}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-data--dataset_code-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-data--dataset_code-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>dataset_code</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="dataset_code"                data-endpoint="GETapi-data--dataset_code-"
               value="consequatur"
               data-component="url">
    <br>
<p>Example: <code>consequatur</code></p>
            </div>
                    </form>

                    <h2 id="endpoints-GETapi-chart-gender--dataset_code---year-">GET api/chart/gender/{dataset_code}/{year}</h2>

<p>
</p>



<span id="example-requests-GETapi-chart-gender--dataset_code---year-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/chart/gender/consequatur/consequatur" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/chart/gender/consequatur/consequatur"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-chart-gender--dataset_code---year-">
            <blockquote>
            <p>Example response (404):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Dataset not found&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-chart-gender--dataset_code---year-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-chart-gender--dataset_code---year-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-chart-gender--dataset_code---year-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-chart-gender--dataset_code---year-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-chart-gender--dataset_code---year-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-chart-gender--dataset_code---year-" data-method="GET"
      data-path="api/chart/gender/{dataset_code}/{year}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-chart-gender--dataset_code---year-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-chart-gender--dataset_code---year-"
                    onclick="tryItOut('GETapi-chart-gender--dataset_code---year-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-chart-gender--dataset_code---year-"
                    onclick="cancelTryOut('GETapi-chart-gender--dataset_code---year-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-chart-gender--dataset_code---year-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/chart/gender/{dataset_code}/{year}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-chart-gender--dataset_code---year-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-chart-gender--dataset_code---year-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>dataset_code</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="dataset_code"                data-endpoint="GETapi-chart-gender--dataset_code---year-"
               value="consequatur"
               data-component="url">
    <br>
<p>Example: <code>consequatur</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>year</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="year"                data-endpoint="GETapi-chart-gender--dataset_code---year-"
               value="consequatur"
               data-component="url">
    <br>
<p>Example: <code>consequatur</code></p>
            </div>
                    </form>

                    <h2 id="endpoints-POSTapi-update-dataset-unit">Manual update unit untuk dataset tertentu (untuk fix data yang unit-nya kosong)
POST /api/update-dataset-unit
Body: { &quot;dataset_id&quot;: 21, &quot;unit&quot;: &quot;Orang&quot; }</h2>

<p>
</p>



<span id="example-requests-POSTapi-update-dataset-unit">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/update-dataset-unit" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"dataset_id\": 17,
    \"unit\": \"mqeopfuudtdsufvyvddqa\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/update-dataset-unit"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "dataset_id": 17,
    "unit": "mqeopfuudtdsufvyvddqa"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-update-dataset-unit">
</span>
<span id="execution-results-POSTapi-update-dataset-unit" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-update-dataset-unit"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-update-dataset-unit"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-update-dataset-unit" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-update-dataset-unit">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-update-dataset-unit" data-method="POST"
      data-path="api/update-dataset-unit"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-update-dataset-unit', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-update-dataset-unit"
                    onclick="tryItOut('POSTapi-update-dataset-unit');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-update-dataset-unit"
                    onclick="cancelTryOut('POSTapi-update-dataset-unit');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-update-dataset-unit"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/update-dataset-unit</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-update-dataset-unit"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-update-dataset-unit"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>dataset_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="dataset_id"                data-endpoint="POSTapi-update-dataset-unit"
               value="17"
               data-component="body">
    <br>
<p>The <code>id</code> of an existing record in the bps_dataset table. Example: <code>17</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>unit</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="unit"                data-endpoint="POSTapi-update-dataset-unit"
               value="mqeopfuudtdsufvyvddqa"
               data-component="body">
    <br>
<p>Must not be greater than 100 characters. Example: <code>mqeopfuudtdsufvyvddqa</code></p>
        </div>
        </form>

                    <h2 id="endpoints-POSTapi-auto-fix-dataset-units">Auto-fix unit untuk semua dataset berdasarkan nama dataset
POST /api/auto-fix-dataset-units
Ini akan secara otomatis menentukan unit yang sesuai berdasarkan nama dataset</h2>

<p>
</p>



<span id="example-requests-POSTapi-auto-fix-dataset-units">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/auto-fix-dataset-units" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/auto-fix-dataset-units"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-auto-fix-dataset-units">
</span>
<span id="execution-results-POSTapi-auto-fix-dataset-units" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-auto-fix-dataset-units"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-auto-fix-dataset-units"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-auto-fix-dataset-units" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-auto-fix-dataset-units">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-auto-fix-dataset-units" data-method="POST"
      data-path="api/auto-fix-dataset-units"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-auto-fix-dataset-units', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-auto-fix-dataset-units"
                    onclick="tryItOut('POSTapi-auto-fix-dataset-units');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-auto-fix-dataset-units"
                    onclick="cancelTryOut('POSTapi-auto-fix-dataset-units');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-auto-fix-dataset-units"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/auto-fix-dataset-units</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-auto-fix-dataset-units"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-auto-fix-dataset-units"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-GETapi-dataset-units">Lihat semua unit yang ada di setiap dataset
GET /api/dataset-units</h2>

<p>
</p>



<span id="example-requests-GETapi-dataset-units">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/dataset-units" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/dataset-units"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-dataset-units">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: &quot;success&quot;,
    &quot;data&quot;: [
        {
            &quot;dataset_id&quot;: 5,
            &quot;dataset_name&quot;: &quot;Jumlah Penduduk Menurut Kelompok Umur dan Jenis Kelamin di Provinsi Jawa Tengah&quot;,
            &quot;units&quot;: [
                &quot;Jiwa&quot;
            ],
            &quot;unit_count&quot;: 1
        },
        {
            &quot;dataset_id&quot;: 6,
            &quot;dataset_name&quot;: &quot;Tingkat Pengangguran Terbuka Menurut Jenis Kelamin di Kabupaten Kebumen (Persen)&quot;,
            &quot;units&quot;: [
                &quot;Persen&quot;
            ],
            &quot;unit_count&quot;: 1
        },
        {
            &quot;dataset_id&quot;: 7,
            &quot;dataset_name&quot;: &quot;Tingkat Pengangguran Terbuka Menurut Tingkat Pendidikan di Kebumen (Persen)&quot;,
            &quot;units&quot;: [
                &quot;Persen&quot;
            ],
            &quot;unit_count&quot;: 1
        },
        {
            &quot;dataset_id&quot;: 8,
            &quot;dataset_name&quot;: &quot;Jumlah Penduduk Kabupaten Kebumen Menurut Jenis Kelamin dan Kecamatan&quot;,
            &quot;units&quot;: [
                &quot;Jiwa&quot;
            ],
            &quot;unit_count&quot;: 1
        },
        {
            &quot;dataset_id&quot;: 9,
            &quot;dataset_name&quot;: &quot;Persentase Penduduk Berumur 15 Tahun Ke Atas yang Bekerja Menurut Lapangan Pekerjaan Utama di Kabupaten Kebumen&quot;,
            &quot;units&quot;: [
                &quot;Persen&quot;
            ],
            &quot;unit_count&quot;: 1
        },
        {
            &quot;dataset_id&quot;: 10,
            &quot;dataset_name&quot;: &quot;Tingkat Partisipasi Angkatan Kerja (TPAK) Menurut Jenis Kelamin di Kabupaten Kebumen&quot;,
            &quot;units&quot;: [
                &quot;Persen&quot;
            ],
            &quot;unit_count&quot;: 1
        },
        {
            &quot;dataset_id&quot;: 11,
            &quot;dataset_name&quot;: &quot;Distribusi Produk Domestik Regional Bruto (PDRB) Triwulanan Menurut Pengeluaran Atas Dasar Harga Berlaku Kabupaten Kebumen&quot;,
            &quot;units&quot;: [
                &quot;Persen&quot;
            ],
            &quot;unit_count&quot;: 1
        },
        {
            &quot;dataset_id&quot;: 12,
            &quot;dataset_name&quot;: &quot;Produk Domestik Regional Bruto (PDRB) Triwulanan Menurut Pengeluaran Atas Dasar Harga Konstan 2010 Kabupaten Kebumen&quot;,
            &quot;units&quot;: [
                &quot;Milyar Rupiah&quot;
            ],
            &quot;unit_count&quot;: 1
        },
        {
            &quot;dataset_id&quot;: 13,
            &quot;dataset_name&quot;: &quot;Produk Domestik Regional Bruto (PDRB) Triwulanan Menurut Pengeluaran Atas Dasar Harga Berlaku Kabupaten Kebumen&quot;,
            &quot;units&quot;: [
                &quot;Milyar Rupiah&quot;
            ],
            &quot;unit_count&quot;: 1
        },
        {
            &quot;dataset_id&quot;: 14,
            &quot;dataset_name&quot;: &quot;Produk Domestik Regional Bruto (PDRB) Triwulanan Menurut Lapangan Usaha Atas Dasar Harga Konstan Kabupaten Kebumen&quot;,
            &quot;units&quot;: [
                &quot;Milyar Rupiah&quot;
            ],
            &quot;unit_count&quot;: 1
        },
        {
            &quot;dataset_id&quot;: 15,
            &quot;dataset_name&quot;: &quot;Produk Domestik Regional Bruto (PDRB) Triwulanan Menurut Lapangan Usaha Atas Dasar Harga Berlaku Kabupaten Kebumen&quot;,
            &quot;units&quot;: [
                &quot;Milyar Rupiah&quot;
            ],
            &quot;unit_count&quot;: 1
        },
        {
            &quot;dataset_id&quot;: 20,
            &quot;dataset_name&quot;: &quot;Penduduk Menurut Kelompok Umur dan Kecamatan (Perempuan)&quot;,
            &quot;units&quot;: [
                &quot;Jiwa&quot;
            ],
            &quot;unit_count&quot;: 1
        },
        {
            &quot;dataset_id&quot;: 21,
            &quot;dataset_name&quot;: &quot;Penduduk Berumur 15 Tahun Ke Atas yang Termasuk Angkatan Kerja Menurut Pendidikan Tertinggi yang Ditamatkan dan Kegiatan Selama Seminggu yang Lalu di Kabupaten Kebumen&quot;,
            &quot;units&quot;: [
                &quot;Persen&quot;
            ],
            &quot;unit_count&quot;: 1
        },
        {
            &quot;dataset_id&quot;: 22,
            &quot;dataset_name&quot;: &quot;Jumlah Kejadian Bencana Alam Menurut Kecamatan di Kabupaten Kebumen&quot;,
            &quot;units&quot;: [
                &quot;Kejadian&quot;
            ],
            &quot;unit_count&quot;: 1
        },
        {
            &quot;dataset_id&quot;: 23,
            &quot;dataset_name&quot;: &quot;Jumlah Dusun, Rukun Warga (RW), dan Rukun Tetangga (RT)  Menurut Kecamatan di Kabupaten Kebumen&quot;,
            &quot;units&quot;: [
                &quot;Unit&quot;
            ],
            &quot;unit_count&quot;: 1
        },
        {
            &quot;dataset_id&quot;: 24,
            &quot;dataset_name&quot;: &quot;Angka Beban Ketergantungan di Kabupaten Kebumen&quot;,
            &quot;units&quot;: [
                &quot;Persen&quot;
            ],
            &quot;unit_count&quot;: 1
        },
        {
            &quot;dataset_id&quot;: 25,
            &quot;dataset_name&quot;: &quot;Jumlah Perjalanan Wisatawan Nusantara dengan Tujuan Kabupaten Kebumen&quot;,
            &quot;units&quot;: [
                &quot;Orang&quot;
            ],
            &quot;unit_count&quot;: 1
        },
        {
            &quot;dataset_id&quot;: 26,
            &quot;dataset_name&quot;: &quot;Jumlah Perjalanan Wisatawan Nusantara dari Asal Kabupaten Kebumen&quot;,
            &quot;units&quot;: [
                &quot;Orang&quot;
            ],
            &quot;unit_count&quot;: 1
        },
        {
            &quot;dataset_id&quot;: 27,
            &quot;dataset_name&quot;: &quot;Jumlah Wisatawan Mancanegara dan Domestik di Kabupaten Kebumen&quot;,
            &quot;units&quot;: [
                &quot;Orang&quot;
            ],
            &quot;unit_count&quot;: 1
        },
        {
            &quot;dataset_id&quot;: 28,
            &quot;dataset_name&quot;: &quot;Rata-rata Upah/Gaji Bersih Sebulan Pekerja Formal Menurut Lapangan Pekerjaan Utama di Kabupaten Kebumen&quot;,
            &quot;units&quot;: [
                &quot;Rupiah&quot;
            ],
            &quot;unit_count&quot;: 1
        },
        {
            &quot;dataset_id&quot;: 29,
            &quot;dataset_name&quot;: &quot;Rata-rata Pendapatan Bersih Sebulan Pekerja Informal Menurut Lapangan Pekerjaan Utama di Kabupaten Kebumen&quot;,
            &quot;units&quot;: [
                &quot;Rupiah&quot;
            ],
            &quot;unit_count&quot;: 1
        },
        {
            &quot;dataset_id&quot;: 30,
            &quot;dataset_name&quot;: &quot;Ukuran Ketimpangan Gini Rasio di Kabupaten Kebumen&quot;,
            &quot;units&quot;: [
                &quot;Indeks&quot;
            ],
            &quot;unit_count&quot;: 1
        },
        {
            &quot;dataset_id&quot;: 31,
            &quot;dataset_name&quot;: &quot;Indeks Kedalaman Kemiskinan (P1) (Persen) di Kabupaten Kebumen&quot;,
            &quot;units&quot;: [
                &quot;Persen&quot;
            ],
            &quot;unit_count&quot;: 1
        },
        {
            &quot;dataset_id&quot;: 32,
            &quot;dataset_name&quot;: &quot;Indeks Keparahan Kemiskinan (P2) (Persen) di Kabupaten Kebumen&quot;,
            &quot;units&quot;: [
                &quot;Persen&quot;
            ],
            &quot;unit_count&quot;: 1
        },
        {
            &quot;dataset_id&quot;: 33,
            &quot;dataset_name&quot;: &quot;[Metode Baru] Indeks Pembangunan Manusia Kabupaten Kebumen&quot;,
            &quot;units&quot;: [
                &quot;Indeks&quot;
            ],
            &quot;unit_count&quot;: 1
        },
        {
            &quot;dataset_id&quot;: 34,
            &quot;dataset_name&quot;: &quot;Indeks Pembangunan Manusia Kabupaten Kebumen (Umur Harapan Hidup Hasil Long Form SP2020)&quot;,
            &quot;units&quot;: [
                &quot;Indeks&quot;
            ],
            &quot;unit_count&quot;: 1
        },
        {
            &quot;dataset_id&quot;: 35,
            &quot;dataset_name&quot;: &quot;Usia Harapan Hidup (UHH), Harapan Lama Sekolah (HLS), Rata-rata Lama Sekolah (RLS), Pengeluaran Riil per Kapita, Indeks Pembangunan Manusia (IPM) Menurut Jenis Kelamin&quot;,
            &quot;units&quot;: [
                &quot;Nilai&quot;
            ],
            &quot;unit_count&quot;: 1
        }
    ],
    &quot;total_datasets&quot;: 27
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-dataset-units" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-dataset-units"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-dataset-units"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-dataset-units" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-dataset-units">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-dataset-units" data-method="GET"
      data-path="api/dataset-units"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-dataset-units', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-dataset-units"
                    onclick="tryItOut('GETapi-dataset-units');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-dataset-units"
                    onclick="cancelTryOut('GETapi-dataset-units');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-dataset-units"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/dataset-units</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-dataset-units"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-dataset-units"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-GETapi-dataset-units--datasetId-">Lihat unit untuk dataset tertentu
GET /api/dataset-units/21</h2>

<p>
</p>



<span id="example-requests-GETapi-dataset-units--datasetId-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/dataset-units/consequatur" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/dataset-units/consequatur"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-dataset-units--datasetId-">
            <blockquote>
            <p>Example response (404):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: &quot;error&quot;,
    &quot;message&quot;: &quot;Dataset not found&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-dataset-units--datasetId-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-dataset-units--datasetId-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-dataset-units--datasetId-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-dataset-units--datasetId-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-dataset-units--datasetId-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-dataset-units--datasetId-" data-method="GET"
      data-path="api/dataset-units/{datasetId}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-dataset-units--datasetId-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-dataset-units--datasetId-"
                    onclick="tryItOut('GETapi-dataset-units--datasetId-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-dataset-units--datasetId-"
                    onclick="cancelTryOut('GETapi-dataset-units--datasetId-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-dataset-units--datasetId-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/dataset-units/{datasetId}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-dataset-units--datasetId-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-dataset-units--datasetId-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>datasetId</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="datasetId"                data-endpoint="GETapi-dataset-units--datasetId-"
               value="consequatur"
               data-component="url">
    <br>
<p>Example: <code>consequatur</code></p>
            </div>
                    </form>

                    <h2 id="endpoints-POSTapi-content-news">POST api/content/news</h2>

<p>
</p>



<span id="example-requests-POSTapi-content-news">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/content/news" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/content/news"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-content-news">
</span>
<span id="execution-results-POSTapi-content-news" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-content-news"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-content-news"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-content-news" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-content-news">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-content-news" data-method="POST"
      data-path="api/content/news"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-content-news', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-content-news"
                    onclick="tryItOut('POSTapi-content-news');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-content-news"
                    onclick="cancelTryOut('POSTapi-content-news');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-content-news"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/content/news</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-content-news"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-content-news"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-POSTapi-content-press-releases">POST api/content/press-releases</h2>

<p>
</p>



<span id="example-requests-POSTapi-content-press-releases">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/content/press-releases" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/content/press-releases"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-content-press-releases">
</span>
<span id="execution-results-POSTapi-content-press-releases" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-content-press-releases"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-content-press-releases"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-content-press-releases" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-content-press-releases">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-content-press-releases" data-method="POST"
      data-path="api/content/press-releases"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-content-press-releases', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-content-press-releases"
                    onclick="tryItOut('POSTapi-content-press-releases');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-content-press-releases"
                    onclick="cancelTryOut('POSTapi-content-press-releases');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-content-press-releases"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/content/press-releases</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-content-press-releases"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-content-press-releases"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-POSTapi-content-infographics">POST api/content/infographics</h2>

<p>
</p>



<span id="example-requests-POSTapi-content-infographics">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/content/infographics" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/content/infographics"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-content-infographics">
</span>
<span id="execution-results-POSTapi-content-infographics" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-content-infographics"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-content-infographics"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-content-infographics" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-content-infographics">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-content-infographics" data-method="POST"
      data-path="api/content/infographics"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-content-infographics', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-content-infographics"
                    onclick="tryItOut('POSTapi-content-infographics');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-content-infographics"
                    onclick="cancelTryOut('POSTapi-content-infographics');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-content-infographics"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/content/infographics</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-content-infographics"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-content-infographics"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-POSTapi-content-publications">POST api/content/publications</h2>

<p>
</p>



<span id="example-requests-POSTapi-content-publications">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/content/publications" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/content/publications"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-content-publications">
</span>
<span id="execution-results-POSTapi-content-publications" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-content-publications"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-content-publications"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-content-publications" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-content-publications">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-content-publications" data-method="POST"
      data-path="api/content/publications"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-content-publications', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-content-publications"
                    onclick="tryItOut('POSTapi-content-publications');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-content-publications"
                    onclick="cancelTryOut('POSTapi-content-publications');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-content-publications"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/content/publications</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-content-publications"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-content-publications"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-GETapi-content-news">Get News with pagination, filtering, and sorting</h2>

<p>
</p>



<span id="example-requests-GETapi-content-news">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/content/news" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/content/news"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-content-news">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;News retrieved successfully&quot;,
    &quot;data&quot;: [
        {
            &quot;id&quot;: 37,
            &quot;date&quot;: &quot;2025-11-21&quot;,
            &quot;category&quot;: null,
            &quot;title&quot;: &quot;BPS Kabupaten Kebumen Goes to School&quot;,
            &quot;abstract&quot;: null,
            &quot;thumbnail_url&quot;: &quot;https://web-api.bps.go.id/cover.php?f=kXy4lMisvQiLfo4rR0hfykt4ZE55TWdBb2MwbE9xcGQzemFCN25QUkF2Wm5wNkRiblBoUzdkd1JMVFJnOGRkVElnSE1mdXBzOVZ1bmtCWHlCdGdDV1hWb1NnVGU3VU9FdEVLZXJaZ09KaWRMemlkenhsQzJwYlJ3YUtISUY3TENsUVg3S2tLMGE2MmJmVDBR&quot;,
            &quot;link&quot;: &quot;https://kebumenkab.bps.go.id/id/news/2025/11/21/418/bps-kabupaten-kebumen-goes-to-school.html&quot;,
            &quot;created_at&quot;: &quot;2025-12-03T08:24:28.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-12-03T08:24:28.000000Z&quot;
        },
        {
            &quot;id&quot;: 36,
            &quot;date&quot;: &quot;2025-11-20&quot;,
            &quot;category&quot;: null,
            &quot;title&quot;: &quot;BPS Kabupaten Kebumen Goes to Campus&quot;,
            &quot;abstract&quot;: null,
            &quot;thumbnail_url&quot;: &quot;https://web-api.bps.go.id/cover.php?f=/pkQyI0Z25dKywsaDGDF9VE1V3NmKzdQaXVYMXY1MWsxSDhpK2lQcTVqaWppMS9XaXhEbWFKTG4yN3g5dFZVbXVQVEpsdUdaQWpsTHc5dHZOREhpNGFKZUx2N1RRQURGOC8wNmYvZmNtOVBEZzR6dWhaY3psM21KVTYvWWhUdnFva0F6ZVN6UE9ZZm5vZnBh&quot;,
            &quot;link&quot;: &quot;https://kebumenkab.bps.go.id/id/news/2025/11/20/416/bps-kabupaten-kebumen-goes-to-campus.html&quot;,
            &quot;created_at&quot;: &quot;2025-12-03T02:22:32.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-12-03T02:22:32.000000Z&quot;
        },
        {
            &quot;id&quot;: 35,
            &quot;date&quot;: &quot;2025-11-17&quot;,
            &quot;category&quot;: &quot;Kegiatan Statistik&quot;,
            &quot;title&quot;: &quot;Saksikan Sosialisasi SE2026 pada Acara Selamat Sore Kebumen&quot;,
            &quot;abstract&quot;: null,
            &quot;thumbnail_url&quot;: &quot;https://web-api.bps.go.id/cover.php?f=d8lmPvOW3E7DRJHVO9EHFG9hTytUSWRsVFpSOEZKbVhFZWx1aEpWWlBKUW52UWJlSHV2QlQvNzZXN2cvZWJrRFNOOEE5RWlQNVlCYVE3ZXQ5UlJlZ2NpWEl3bnZxckRnc1k5MnNLVkk1RnJZY3ZNU2FGd2c1SUpySVJiWXZTNnNLcDRhTzl5aFdwZEpzQUhGWXA1QitZS245UEt0MzdPSS8zcFRvcmNRQlJKNHlhejhiVFEvNjVhenA5ST0=&quot;,
            &quot;link&quot;: &quot;https://kebumenkab.bps.go.id/id/news/2025/11/17/413/saksikan-sosialisasi-se2026-pada-acara-selamat-sore-kebumen.html&quot;,
            &quot;created_at&quot;: &quot;2025-11-20T07:59:26.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-11-20T13:37:20.000000Z&quot;
        },
        {
            &quot;id&quot;: 33,
            &quot;date&quot;: &quot;2025-11-13&quot;,
            &quot;category&quot;: &quot;Kegiatan Statistik&quot;,
            &quot;title&quot;: &quot;Evaluasi Pendataan dan Koordinasi Respons Rate Supplier Survei MBG&quot;,
            &quot;abstract&quot;: &quot;&quot;,
            &quot;thumbnail_url&quot;: &quot;https://web-api.bps.go.id/cover.php?f=d8CR2/Zf0VRWq/eqMB6ALlViUmFIaWVjKzFDZ01GUXUycVI2aCt1aUpqKzkrZUV3cG83b1FNRWdISVJ3N2cvRlA3UmVhN2ZCOUE1bE1VR0hodkthR1dOYmJaUHB1ckJrcmw4cUJiNVQyREFLdzFZcnE5OE1NSWd4MTBmNEdDbGh2SnUvKytKQ1RYZEpSbDQzcFBNWi9zcXg4bmRyQTFUc2lYd1pVU012UEJuZml6QTJRSUJmWXlkZi9Kcz0=&quot;,
            &quot;link&quot;: &quot;https://kebumenkab.bps.go.id/id/news/2025/11/13/412/evaluasi-pendataan-dan-koordinasi-respons-rate-supplier-survei-mbg.html&quot;,
            &quot;created_at&quot;: &quot;2025-11-17T01:11:32.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-11-20T14:16:52.000000Z&quot;
        },
        {
            &quot;id&quot;: 34,
            &quot;date&quot;: &quot;2025-11-12&quot;,
            &quot;category&quot;: &quot;Kegiatan Statistik&quot;,
            &quot;title&quot;: &quot;Monev Program MBG: Data Akurat untuk Kebijakan Tepat Sasaran&quot;,
            &quot;abstract&quot;: &quot;BPS Kabupaten Kebumen tengah melaksanakan Survei Monitoring dan Evaluasi (Monev) Program Makan Bergizi Gratis (MBG). Tujuan utama survei ini adalah untuk menghadirkan data statistik yang objektif dan akurat sebagai bahan evaluasi pemerintah, agar program MBG dapat berjalan efektif dan tepat sasaran.&nbsp;Untuk memastikan kualitas dan validitas data di lapangan, Tim BPS Provinsi Jawa Tengah turut ha.....&quot;,
            &quot;thumbnail_url&quot;: &quot;https://web-api.bps.go.id/cover.php?f=QfqVX6Vx3vyVmPNxpLsrszRNL3hDajRKRVJIdHFPVE5DRSszL0dCL0ptckVvb1gyZlQwWEFwb1RQVFR4WHpFWlV2M1ZVWEJkcmlRUUVaTk9nQjE5d3hRSEJPTno5aDYrNk15NnZtT01MVHg3T1JhUzBFUEdPT1RPVmo5V1FrS1EwM29jWnN2QnRFcGM0TGIzdHlHOHBFTng4b1I5ZksvVmVEWFB1U1NrVStrNG9jQWgrd3pxVThFdnh0TT0=&quot;,
            &quot;link&quot;: &quot;https://kebumenkab.bps.go.id/id/news/2025/11/12/411/monev-program-mbg--data-akurat-untuk-kebijakan-tepat-sasaran.html&quot;,
            &quot;created_at&quot;: &quot;2025-11-17T01:11:32.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-11-20T13:38:34.000000Z&quot;
        },
        {
            &quot;id&quot;: 29,
            &quot;date&quot;: &quot;2025-11-11&quot;,
            &quot;category&quot;: &quot;Kegiatan Statistik Lainnya&quot;,
            &quot;title&quot;: &quot;Lomba Konten Video dan Fotografi Dalam Rangka Gerakan Literasi Statistik&quot;,
            &quot;abstract&quot;: &quot;Halo Sobat Data! SIAPKAN DIRIMU! ✨Jago bikin video dan storytelling yang kuat?? Atau, jago menangkap momen lewat lensa kamera? Ini saatnya unjuk gigi dan berkarya!BPS Kabupaten Kebumen dengan bangga menyelenggarakan Lomba Konten Video dan Fotografi Dalam Rangka Gerakan Literasi Statistik. Kesempatan emas ini TERBUKA UNTUK UMUM!Ayo jadilah bagian dari gerakan literasi statistik, raih pen.....&quot;,
            &quot;thumbnail_url&quot;: &quot;https://web-api.bps.go.id/cover.php?f=3lxMgLjjRu5kKPh8c5bpnFk4Zk12U095aUVZelZkQ2dyRGxBQkZhUGFibHNJRFJxdUp6ZnlCQXR1TjBjd0lmejR3cTB0TkhSK054VGVBOUxEUkYzVXViNmhvUm5aL3BqdDh3VUx3K0I0dTkzK0ZCNmluTU1Fd21SZTdrbklPWExkZ3NIaTIzMUVHS3NvWXFZYUdvejlOcDZxNTNGcUR1ZVZGUGUxNlpXeEJqOU5MZGFycnBDWG9WcDdIND0=&quot;,
            &quot;link&quot;: &quot;https://kebumenkab.bps.go.id/id/news/2025/11/11/409/lomba-konten-video-dan-fotografi-dalam-rangka-gerakan-literasi-statistik.html&quot;,
            &quot;created_at&quot;: &quot;2025-11-12T15:43:57.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-11-20T14:18:47.000000Z&quot;
        },
        {
            &quot;id&quot;: 30,
            &quot;date&quot;: &quot;2025-11-10&quot;,
            &quot;category&quot;: &quot;Kegiatan Statistik Lainnya&quot;,
            &quot;title&quot;: &quot;Gerakan Literasi Statistik BPS Kabupaten Kebumen&quot;,
            &quot;abstract&quot;: &quot;Halo Sobat DataDi era derasnya informasi saat ini, literasi statistik menjadi hal yang krusial,Makanya penting banget buat ngerti data..Nah, pada Hari Senin, 10 November 2025, BPS Kab. Kebumen menyelenggarakan&nbsp;Literasi dan Pembinaan Statistik bagi Agen Statistik Universitas Putra Bangsa (UPB) sebagai wujud sinergi membangun kompetensi data.Para Agen Statistik dibekali kemampuan men.....&quot;,
            &quot;thumbnail_url&quot;: &quot;https://web-api.bps.go.id/cover.php?f=qxDPk3rzLt/hEUdd1ZN2SXRyU0YvRkVlcjVGM2xqeHJ0amUvaGdtV1BOOGQwc1lkRlV4YmhHRGFTK1p1eTNaSERvaTl1OUt4akFydkVPZGxQOFNQQUlvUEs1bkV2bTVjVm9nek82VzQrV3A0aHVRVmsvMWxTZUpHQlNWSzZZLzhzNTFmdUFBTWZ0TExPalJLQjFiWm53QU5FTHFONXZOMnRNRXg5Zz09&quot;,
            &quot;link&quot;: &quot;https://kebumenkab.bps.go.id/id/news/2025/11/10/410/gerakan-literasi-statistik-bps-kabupaten-kebumen.html&quot;,
            &quot;created_at&quot;: &quot;2025-11-12T15:43:57.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-11-20T14:19:28.000000Z&quot;
        },
        {
            &quot;id&quot;: 31,
            &quot;date&quot;: &quot;2025-11-06&quot;,
            &quot;category&quot;: &quot;Kegiatan Statistik&quot;,
            &quot;title&quot;: &quot;Refreshing Petugas Seruti Triwulan IV 2025&quot;,
            &quot;abstract&quot;: &quot;Setiap aktivitas konsumsi kita, mulai dari berbelanja kebutuhan pokok, membeli makan, hingga membayar tagihan, merupakan kegiatan yang menggerakkan roda ekonomi.Untuk mengukur seberapa kuat pergerakan ini, Badan Pusat Statistik (BPS) menyelenggarakan Survei Ekonomi Rumah Tangga Triwulanan atau Seruti.Survei ini ibarat &#039;detak jantung&#039; ekonomi yang dipantau setiap tiga bulan. Melalui Seruti, BPS .....&quot;,
            &quot;thumbnail_url&quot;: &quot;https://web-api.bps.go.id/cover.php?f=WOe+2gU7ypAhisLnE9CF7kxzWm9RazZJMFVxZ3B2ZjEvcEsvTkRFcWNINkZwRnFmMmZJL21xbnhlWVNCeU5iOWxLK0dTVDNuZUN3aVp4NjhDZE9zYWJFRzVTSkIvTUlCQXFLS3NBTnBydnZPZ050WElpUHVaVTcwSlgzNUF2Mm5EaUlHNUVBd1BEUE5ZaE5KaUh0YzUwNlRYYlVwV2pWdlE2MDIzZz09&quot;,
            &quot;link&quot;: &quot;https://kebumenkab.bps.go.id/id/news/2025/11/06/408/refreshing-petugas-seruti-triwulan-iv-2025.html&quot;,
            &quot;created_at&quot;: &quot;2025-11-12T15:43:57.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-11-20T06:18:02.000000Z&quot;
        },
        {
            &quot;id&quot;: 32,
            &quot;date&quot;: &quot;2025-11-05&quot;,
            &quot;category&quot;: &quot;Kegiatan Statistik Lainnya&quot;,
            &quot;title&quot;: &quot;Penguatan Data Sektoral&quot;,
            &quot;abstract&quot;: &quot;Dalam rangka memperkuat sinergi dan akurasi data sektoral, Badan Pusat Statistik (BPS) Kabupaten Kebumen melaksanakan kunjungan kerja ke Dinas Perindustrian, Perdagangan, Koperasi, Usaha Kecil dan Menengah (Disperindag KUKM) Kabupaten Kebumen. Pertemuan yang digelar di Ruang Rapat Disperindag KUKM pada Selasa, 04 November 2025 ini menjadi forum strategis bagi kedua instansi untuk duduk bersama dan .....&quot;,
            &quot;thumbnail_url&quot;: &quot;https://web-api.bps.go.id/cover.php?f=lGA2/olHmp1/2YgKb59CT0tQWkhyQWpjcmp1dXBvSWViRXpFV2hEU0lWNWNMdDJyenBwRmczY1p5TDFkK0IrREJaZjJ1WWpuTzJoMnptYmpETGEyZ1ViWnlQYW5xZm5LNGJ2dHNPdk5SbURpdExvc0NOUFdJV3BkMUZvPQ==&quot;,
            &quot;link&quot;: &quot;https://kebumenkab.bps.go.id/id/news/2025/11/05/407/penguatan-data-sektoral.html&quot;,
            &quot;created_at&quot;: &quot;2025-11-12T15:43:57.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-11-12T15:43:57.000000Z&quot;
        },
        {
            &quot;id&quot;: 4,
            &quot;date&quot;: &quot;2025-10-29&quot;,
            &quot;category&quot;: &quot;Kegiatan Statistik Lainnya&quot;,
            &quot;title&quot;: &quot;Forum Satu Data Kabupaten Kebumen&quot;,
            &quot;abstract&quot;: &quot;Selasa, 28 Oktober 2025, sebagai bagian dari Literasi Statistik indikator strategis untuk perangkat daerah, Bapperida Kebumen bersama BPS Kabupaten Kebumen menggelar pertemuan Forum Satu Data guna memperkuat sinergi dalam penyelenggaraan kegiatan statistik sektoral.Kegiatan ini menjadi wadah koordinasi untuk memastikan data yang dihasilkan antar instansi bersifat akurat, mutakhir, terpadu, dan .....&quot;,
            &quot;thumbnail_url&quot;: &quot;https://web-api.bps.go.id/cover.php?f=SWSoztHXATaMUb5B5dpqmFpvZE54K1E5TE9lQkxnQThKdXNFL1djb0owb1psRnd0RWgxdWpBcjc5ZFRwaVQ4cFlFTnREc3hGd2tzdTRMa0pUMURGNWdGcHdiQXVoQW94WkJjVS9JT2x4ZW9tRjRSVEVTbW94S2tuaHZlZitFRUFNaElLVWYyaDhRcExrOHZV&quot;,
            &quot;link&quot;: &quot;https://kebumenkab.bps.go.id/id/news/2025/10/29/406/forum-satu-data-kabupaten-kebumen.html&quot;,
            &quot;created_at&quot;: &quot;2025-10-31T13:38:13.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-10-31T13:38:13.000000Z&quot;
        }
    ],
    &quot;pagination&quot;: {
        &quot;current_page&quot;: 1,
        &quot;last_page&quot;: 2,
        &quot;per_page&quot;: 10,
        &quot;total&quot;: 19
    }
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-content-news" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-content-news"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-content-news"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-content-news" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-content-news">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-content-news" data-method="GET"
      data-path="api/content/news"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-content-news', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-content-news"
                    onclick="tryItOut('GETapi-content-news');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-content-news"
                    onclick="cancelTryOut('GETapi-content-news');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-content-news"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/content/news</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-content-news"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-content-news"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-GETapi-content-press-releases">Get Press Releases with pagination, filtering, and sorting</h2>

<p>
</p>



<span id="example-requests-GETapi-content-press-releases">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/content/press-releases" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/content/press-releases"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-content-press-releases">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;Press releases retrieved successfully&quot;,
    &quot;data&quot;: [
        {
            &quot;id&quot;: 44,
            &quot;date&quot;: &quot;2025-11-11T17:00:00.000000Z&quot;,
            &quot;title&quot;: &quot;Perkembangan Pariwisata di Kabupaten Kebumen September 2025&quot;,
            &quot;abstract&quot;: null,
            &quot;thumbnail_url&quot;: &quot;https://web-api.bps.go.id/cover.php?f=xamW9G+/i1rkKhsFLARYiUdXaFZjY3cxZlhTcEFqMkRHOHB1ejcvMmE2Y1N6YVEzWXJZRnlHV2w0eTBIbTNRN1h2ZjFVNDVJVmJpVTBTakNLNUh0Y05WbnkzaVNET2w4NklSNGp3PT0=&quot;,
            &quot;link&quot;: &quot;https://kebumenkab.bps.go.id/id/pressrelease/2025/11/12/382/perkembangan-pariwisata-di-kabupaten-kebumen-september-2025.html&quot;,
            &quot;pdf_url&quot;: null,
            &quot;downloads&quot;: null,
            &quot;category&quot;: null,
            &quot;content_html&quot;: null,
            &quot;content_text&quot;: null,
            &quot;created_at&quot;: &quot;2025-12-02T15:46:52.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-12-02T15:46:52.000000Z&quot;
        },
        {
            &quot;id&quot;: 24,
            &quot;date&quot;: &quot;2025-10-09T17:00:00.000000Z&quot;,
            &quot;title&quot;: &quot;Perkembangan Pariwisata di Kabupaten Kebumen Agustus 2025&quot;,
            &quot;abstract&quot;: &quot;Tingkat Penghunian Kamar (TPK) pada&nbsp;Agustus 2025 sebesar&nbsp;30,62&nbsp;persen dimana TPK hotel bintang&nbsp;52,65persen dan nonbintang&nbsp;23,55&nbsp;persen.&nbsp;Rata-rata Lama Menginap (RLM) tamu pada&nbsp;Agustus 2025 sebesar 1,13&nbsp;malam, tercatat RLM hotel bintang sebesar 1,37&nbsp;malam dan hotel nonbintang 1,01&nbsp;malam.&nbsp;Pada Januari-Agustus 2025, perjalanan wisatawan nusantara (wisnus) tujuan&nbsp;Kebumen mencapai 2,77 juta perjalanan.&quot;,
            &quot;thumbnail_url&quot;: &quot;https://web-api.bps.go.id/cover.php?f=DQs8JrkBy9lGl5zsooKTh1dpeGtnSFNyUENsVDdMVHZkUnczblpmdHFKRkR1QnJCelhzTUd1M1pBOFkxUEcwTXpHY1I3SVVsQUwyT1ZzY3hiVHNWQW1NbjZ2NmlTZkVCOGJsQXdnPT0=&quot;,
            &quot;link&quot;: &quot;https://kebumenkab.bps.go.id/id/pressrelease/2025/10/10/381/perkembangan-pariwisata-di-kabupaten-kebumen-agustus-2025.html&quot;,
            &quot;pdf_url&quot;: &quot;https://web-api.bps.go.id/download.php?f=zGsCX/9PNQAKfz1ahY+HGzErclVROXVmRjFVMDZwSFZZNkFDSVdZc1QrRVlJaTF5TlNKZmNJdFFReCtDODZ5aEdGWW85djd0ZHhRMHJBcW1FSnRPcmpHNDJKUStMZWR0c2FPQlZQSDJLUDlrSjFvV2VPc0dSMUlaRW01bDRsSnBIZ3ZmTUl2WFNVckN0clFwNWFXZ0ZkaWo4OERRU2xLOFh2S0dlUy9nWDNuT3EyL2Fsdm1hUkREVklsTjdOOS93b3JvSnN5SVM4bUloc1oxcEtZVDRoZ3Y0ZzdjTmVRaEd6MzFndmc9PQ==&quot;,
            &quot;downloads&quot;: &quot;[{\&quot;text\&quot;:\&quot;Unduh Berita Resmi Statistik\&quot;,\&quot;url\&quot;:\&quot;https:\\/\\/web-api.bps.go.id\\/download.php?f=zGsCX\\/9PNQAKfz1ahY+HGzErclVROXVmRjFVMDZwSFZZNkFDSVdZc1QrRVlJaTF5TlNKZmNJdFFReCtDODZ5aEdGWW85djd0ZHhRMHJBcW1FSnRPcmpHNDJKUStMZWR0c2FPQlZQSDJLUDlrSjFvV2VPc0dSMUlaRW01bDRsSnBIZ3ZmTUl2WFNVckN0clFwNWFXZ0ZkaWo4OERRU2xLOFh2S0dlUy9nWDNuT3EyL2Fsdm1hUkREVklsTjdOOS93b3JvSnN5SVM4bUloc1oxcEtZVDRoZ3Y0ZzdjTmVRaEd6MzFndmc9PQ==\&quot;},{\&quot;text\&quot;:\&quot;Unduh Infografik\&quot;,\&quot;url\&quot;:\&quot;https:\\/\\/web-api.bps.go.id\\/cover.php?f=ZNUGgIxWIS7JQxLyB7l\\/6Wx0K3k4SmU3eEJOWUVzbDl4NHhNR3M4RGE0SklMbUg0bWx4cVZaMVJjOVhPbjJ6Z1pTQlJQaFR4bTNrdXloYi8zWUhiZGF3RVU4QW9mOS9JUE9hczBnPT0=\&quot;}]&quot;,
            &quot;category&quot;: null,
            &quot;content_html&quot;: &quot;&lt;p data-asw-orgfontsize=\&quot;16\&quot; style=\&quot;font-size: 16px;\&quot;&gt;Tingkat Penghunian Kamar (TPK) pada&amp;nbsp;Agustus 2025 sebesar&amp;nbsp;30,62&amp;nbsp;persen dimana TPK hotel bintang&amp;nbsp;52,65&amp;nbsp;&amp;nbsp;persen dan nonbintang&amp;nbsp;23,55&amp;nbsp;persen.&amp;nbsp;&lt;/p&gt;&lt;p data-asw-orgfontsize=\&quot;16\&quot; style=\&quot;font-size: 16px;\&quot;&gt;Rata-rata Lama Menginap (RLM) tamu pada&amp;nbsp;Agustus 2025 sebesar 1,13&amp;nbsp;malam, tercatat RLM hotel bintang sebesar 1,37&amp;nbsp;malam dan hotel nonbintang 1,01&amp;nbsp;malam.&amp;nbsp;&lt;/p&gt;&lt;p data-asw-orgfontsize=\&quot;16\&quot; style=\&quot;font-size: 16px;\&quot;&gt;Pada Januari-Agustus 2025, perjalanan wisatawan nusantara (wisnus) tujuan&amp;nbsp;Kebumen mencapai 2,77 juta perjalanan.&lt;/p&gt;&quot;,
            &quot;content_text&quot;: &quot;Tingkat Penghunian Kamar (TPK) pada&nbsp;Agustus 2025 sebesar&nbsp;30,62&nbsp;persen dimana TPK hotel bintang&nbsp;52,65&nbsp;&nbsp;persen dan nonbintang&nbsp;23,55&nbsp;persen.&nbsp;\n\nRata-rata Lama Menginap (RLM) tamu pada&nbsp;Agustus 2025 sebesar 1,13&nbsp;malam, tercatat RLM hotel bintang sebesar 1,37&nbsp;malam dan hotel nonbintang 1,01&nbsp;malam.&nbsp;\n\nPada Januari-Agustus 2025, perjalanan wisatawan nusantara (wisnus) tujuan&nbsp;Kebumen mencapai 2,77 juta perjalanan.&quot;,
            &quot;created_at&quot;: &quot;2025-10-31T15:10:09.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-10-31T15:10:09.000000Z&quot;
        },
        {
            &quot;id&quot;: 25,
            &quot;date&quot;: &quot;2025-09-08T17:00:00.000000Z&quot;,
            &quot;title&quot;: &quot;Perkembangan Tingkat Penghunian Kamar Hotel di Kabupaten Kebumen Juli 2025&quot;,
            &quot;abstract&quot;: &quot;Tingkat Penghunian Kamar (TPK) pada&nbsp;Juli 2025 sebesar&nbsp;29,08&nbsp;persen dimana TPK hotel bintang&nbsp;45,80persen dan nonbintang&nbsp;23,72&nbsp;persen.&nbsp;Rata-rata Lama Menginap (RLM) tamu pada&nbsp;Juli 2025 sebesar 1,14&nbsp;malam, tercatat RLM hotel bintang sebesar 1,36&nbsp;malam dan hotel nonbintang 1,03&nbsp;malam.&quot;,
            &quot;thumbnail_url&quot;: &quot;https://web-api.bps.go.id/cover.php?f=X/jP/eF6FHeYbuul+5zW1HUwVExFQjNZR0xScUFURi9vcW9vN0FqcDdyK3gweHhyM1pjZFBKTSs5Y3djTFIycEVYTTB3VUo1V2RxS09HSjVrdk44eVRtaEFja1RHNkVDQzhJR0lnPT0=&quot;,
            &quot;link&quot;: &quot;https://kebumenkab.bps.go.id/id/pressrelease/2025/09/09/380/perkembangan-tingkat-penghunian-kamar-hotel-di-kabupaten-kebumen-juli-2025.html&quot;,
            &quot;pdf_url&quot;: &quot;https://web-api.bps.go.id/download.php?f=Ruk+2jptTaVDA37n3hJhOW0rZk9tVjYrRklUVkdxMFNhRlR1WmowOXQ4UmlXV1JtbDdNV2YrWnNQanBxNWxYNDQ3ZDdxYWRpSDlyOFN1YmNRSUd5eHZObHFvTFhTaDAvWWpuMlJtNDRnYllKV2xoMEpneWRRcjBsVS9sU2lTNjFUWHNLeitoczRnY1V2RzdQdnZBWG9SdXBrY0l5QW5kcEZqVWdQc2FNaU1oeTIxKytadmZkS2NaN0FnblhoaWkyeUd3OCtWS1h4TEdlWkRNREpxaFprQnNweGptVUh2YmJYRktBTnBhcDJJOTlaNVZuaEs2dWZnZE0vWnM9&quot;,
            &quot;downloads&quot;: &quot;[{\&quot;text\&quot;:\&quot;Unduh Berita Resmi Statistik\&quot;,\&quot;url\&quot;:\&quot;https:\\/\\/web-api.bps.go.id\\/download.php?f=Ruk+2jptTaVDA37n3hJhOW0rZk9tVjYrRklUVkdxMFNhRlR1WmowOXQ4UmlXV1JtbDdNV2YrWnNQanBxNWxYNDQ3ZDdxYWRpSDlyOFN1YmNRSUd5eHZObHFvTFhTaDAvWWpuMlJtNDRnYllKV2xoMEpneWRRcjBsVS9sU2lTNjFUWHNLeitoczRnY1V2RzdQdnZBWG9SdXBrY0l5QW5kcEZqVWdQc2FNaU1oeTIxKytadmZkS2NaN0FnblhoaWkyeUd3OCtWS1h4TEdlWkRNREpxaFprQnNweGptVUh2YmJYRktBTnBhcDJJOTlaNVZuaEs2dWZnZE0vWnM9\&quot;},{\&quot;text\&quot;:\&quot;Unduh Infografik\&quot;,\&quot;url\&quot;:\&quot;https:\\/\\/web-api.bps.go.id\\/cover.php?f=VEAi\\/juvSeX6pf8NPjksamFPc3k4ZllIbWU1U1l4bTZuMm4vSVhJd3pJeFZTbHNoKzc5M1FER3BPZWIrQ25RVnZqTlpyNURjR3pPaFY4eURQVGRuS0NKS0o1SUtQNWNKS3pFOEJnPT0=\&quot;}]&quot;,
            &quot;category&quot;: null,
            &quot;content_html&quot;: &quot;&lt;p data-asw-orgfontsize=\&quot;16\&quot; style=\&quot;font-size: 16px;\&quot;&gt;Tingkat Penghunian Kamar (TPK) pada&amp;nbsp;Juli 2025 sebesar&amp;nbsp;29,08&amp;nbsp;persen dimana TPK hotel bintang&amp;nbsp;45,80&amp;nbsp;&amp;nbsp;persen dan nonbintang&amp;nbsp;23,72&amp;nbsp;persen.&amp;nbsp;&lt;/p&gt;&lt;p data-asw-orgfontsize=\&quot;16\&quot; style=\&quot;font-size: 16px;\&quot;&gt;Rata-rata Lama Menginap (RLM) tamu pada&amp;nbsp;Juli 2025 sebesar 1,14&amp;nbsp;malam, tercatat RLM hotel bintang sebesar 1,36&amp;nbsp;malam dan hotel nonbintang 1,03&amp;nbsp;malam.&amp;nbsp;&lt;/p&gt;&quot;,
            &quot;content_text&quot;: &quot;Tingkat Penghunian Kamar (TPK) pada&nbsp;Juli 2025 sebesar&nbsp;29,08&nbsp;persen dimana TPK hotel bintang&nbsp;45,80&nbsp;&nbsp;persen dan nonbintang&nbsp;23,72&nbsp;persen.&nbsp;\n\nRata-rata Lama Menginap (RLM) tamu pada&nbsp;Juli 2025 sebesar 1,14&nbsp;malam, tercatat RLM hotel bintang sebesar 1,36&nbsp;malam dan hotel nonbintang 1,03&nbsp;malam.&quot;,
            &quot;created_at&quot;: &quot;2025-10-31T15:10:12.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-10-31T15:10:12.000000Z&quot;
        },
        {
            &quot;id&quot;: 26,
            &quot;date&quot;: &quot;2025-08-07T17:00:00.000000Z&quot;,
            &quot;title&quot;: &quot;Perkembangan Tingkat Penghunian Kamar Hotel di Kabupaten Kebumen Juni 2025&quot;,
            &quot;abstract&quot;: &quot;Tingkat Penghunian Kamar (TPK) pada&nbsp;Juni 2025 sebesar&nbsp;31,77&nbsp;persen dimana TPK hotel bintang&nbsp;54,03persen dan nonbintang&nbsp;24,66&nbsp;persen.&nbsp;Rata-rata Lama Menginap (RLM) tamu pada&nbsp;Juni 2025 sebesar 1,12 malam, tercatat RLM hotel bintang sebesar 1,32 malam dan hotel nonbintang 1,01 malam.&quot;,
            &quot;thumbnail_url&quot;: &quot;https://web-api.bps.go.id/cover.php?f=qYiwV7LIAmL0fmBBlrAnGzVZdC9kcDdiMTZLYUd5R0dKWG82L21TdnVGMERVUnh4Q01ENnpnbS9pUmZsS1BTVHJ0am1kQVpacXcrUFNHRUVaZHRlQTdLMnc0TzUxSVE2Zkl3QUtBPT0=&quot;,
            &quot;link&quot;: &quot;https://kebumenkab.bps.go.id/id/pressrelease/2025/08/08/379/perkembangan-tingkat-penghunian-kamar-hotel-di-kabupaten-kebumen-juni-2025.html&quot;,
            &quot;pdf_url&quot;: &quot;https://web-api.bps.go.id/download.php?f=NLoqQYv/cRawxnk35Y40xlBmaDVWQzlqT2ZjcTlSaVJMSXA2TVE4MVlEMVRNZDMrUDNZOEJmb3hGaWFBbDl2NEpGT005cFZySmdTdDN6VVU0bDJIOElKSFFTbkNzRU1pQXZERlZqTFpNOTUwaFlBTXA3MzAvMlJvNWZxMUdBWWJ2MnpYS2VZRktoZFpxSnNVa3JiOUFPY2FoSzVpa2lmVFhXTGMvNjdXUXZSQnBJTE1JK2NHWFp2RFlSYmp5WlFxQnoxbUtQTGFmWFBRMUNsZTdzcDRMWjRVZEtRWjF3a080TzNiUVdyVGpNaDBMS2huNmVXT096WXduS1E9&quot;,
            &quot;downloads&quot;: &quot;[{\&quot;text\&quot;:\&quot;Unduh Berita Resmi Statistik\&quot;,\&quot;url\&quot;:\&quot;https:\\/\\/web-api.bps.go.id\\/download.php?f=NLoqQYv\\/cRawxnk35Y40xlBmaDVWQzlqT2ZjcTlSaVJMSXA2TVE4MVlEMVRNZDMrUDNZOEJmb3hGaWFBbDl2NEpGT005cFZySmdTdDN6VVU0bDJIOElKSFFTbkNzRU1pQXZERlZqTFpNOTUwaFlBTXA3MzAvMlJvNWZxMUdBWWJ2MnpYS2VZRktoZFpxSnNVa3JiOUFPY2FoSzVpa2lmVFhXTGMvNjdXUXZSQnBJTE1JK2NHWFp2RFlSYmp5WlFxQnoxbUtQTGFmWFBRMUNsZTdzcDRMWjRVZEtRWjF3a080TzNiUVdyVGpNaDBMS2huNmVXT096WXduS1E9\&quot;},{\&quot;text\&quot;:\&quot;Unduh Infografik\&quot;,\&quot;url\&quot;:\&quot;https:\\/\\/web-api.bps.go.id\\/cover.php?f=rFCrMbaHdbs4AqYcT\\/QqJVdzNFZYSU0ydGxIWjBjYnlCay9ocW0wR0lkOHM0NmlTYVFBVnNYWGZpbk5peExHcnAvS012c1A3WUwyL0RVT3gvWDQyeEdaLzJjSDJOZkk0SFBhZE9BPT0=\&quot;}]&quot;,
            &quot;category&quot;: null,
            &quot;content_html&quot;: &quot;&lt;p data-asw-orgfontsize=\&quot;16\&quot; style=\&quot;font-size: 16px;\&quot;&gt;Tingkat Penghunian Kamar (TPK) pada&amp;nbsp;Juni 2025 sebesar&amp;nbsp;31,77&amp;nbsp;persen dimana TPK hotel bintang&amp;nbsp;54,03&amp;nbsp;&amp;nbsp;persen dan nonbintang&amp;nbsp;24,66&amp;nbsp;persen.&amp;nbsp;&lt;/p&gt;&lt;p data-asw-orgfontsize=\&quot;16\&quot; style=\&quot;font-size: 16px;\&quot;&gt;Rata-rata Lama Menginap (RLM) tamu pada&amp;nbsp;Juni 2025 sebesar 1,12 malam, tercatat RLM hotel bintang sebesar 1,32 malam dan hotel nonbintang 1,01 malam.&lt;/p&gt;&quot;,
            &quot;content_text&quot;: &quot;Tingkat Penghunian Kamar (TPK) pada&nbsp;Juni 2025 sebesar&nbsp;31,77&nbsp;persen dimana TPK hotel bintang&nbsp;54,03&nbsp;&nbsp;persen dan nonbintang&nbsp;24,66&nbsp;persen.&nbsp;\n\nRata-rata Lama Menginap (RLM) tamu pada&nbsp;Juni 2025 sebesar 1,12 malam, tercatat RLM hotel bintang sebesar 1,32 malam dan hotel nonbintang 1,01 malam.&quot;,
            &quot;created_at&quot;: &quot;2025-10-31T15:10:15.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-10-31T15:10:15.000000Z&quot;
        },
        {
            &quot;id&quot;: 27,
            &quot;date&quot;: &quot;2025-07-07T17:00:00.000000Z&quot;,
            &quot;title&quot;: &quot;Perkembangan Tingkat Penghunian Kamar Hotel di Kabupaten Kebumen Mei 2025&quot;,
            &quot;abstract&quot;: &quot;Tingkat Penghunian Kamar (TPK) pada Mei 2025 sebesar&nbsp;30,02&nbsp;persen dimana TPK hotel bintang&nbsp;51,32persen dan nonbintang&nbsp;23,32&nbsp;persen.&nbsp;Rata-rata Lama Menginap (RLM) tamu pada Mei 2025 sebesar 1,12&nbsp;malam, tercatat RLM hotel bintang sebesar 1,31&nbsp;malam dan hotel nonbintang 1,02&nbsp;malam.&quot;,
            &quot;thumbnail_url&quot;: &quot;https://web-api.bps.go.id/cover.php?f=wKlSxLKpK1QYnpVwTyTVJURTWjZjRUk5TnhOUWMzZGlIWjBTbUhPNkdNNkEyN3lqUUZDQXJvemlVellseGhLcHI2UWgvYmtjQ1ZxalQ5OUlQYVlYdUN0ajFRNno3Y1dIU21YZWtnPT0=&quot;,
            &quot;link&quot;: &quot;https://kebumenkab.bps.go.id/id/pressrelease/2025/07/08/378/perkembangan-tingkat-penghunian-kamar-hotel-di-kabupaten-kebumen-mei-2025.html&quot;,
            &quot;pdf_url&quot;: &quot;https://web-api.bps.go.id/download.php?f=GISzBX6uDoU53wlTsx9V+itqOG5UbHRZSEpjcFVyVEUraEw2ZEJWR0JVeDBpTXhWNFBwejh5MDl2alYzdFU1NjU1QWZOaWlNbTZwa3BkTGdmdmRtdE1Mc2dibDlKa3I2eXpMUngrMStuekZIUlgzZUhUUkN4VExUZW9rREwrYyt2UUxqaHUwdDBWK3cwUTZxU3RRRStQMUxMUytWYkNkcmcvQ0N6ZGJuL0ZIaWoramtVOUkxUGlMQnZOcGJyZFMvYUJ5MStEdmRtRENpMWxhSko5QXVLQlVqbGV5MzVhOFV5ODl4dnR0d0FndWtPNEx6cEUzNVdaQ1V1SlU9&quot;,
            &quot;downloads&quot;: &quot;[{\&quot;text\&quot;:\&quot;Unduh Berita Resmi Statistik\&quot;,\&quot;url\&quot;:\&quot;https:\\/\\/web-api.bps.go.id\\/download.php?f=GISzBX6uDoU53wlTsx9V+itqOG5UbHRZSEpjcFVyVEUraEw2ZEJWR0JVeDBpTXhWNFBwejh5MDl2alYzdFU1NjU1QWZOaWlNbTZwa3BkTGdmdmRtdE1Mc2dibDlKa3I2eXpMUngrMStuekZIUlgzZUhUUkN4VExUZW9rREwrYyt2UUxqaHUwdDBWK3cwUTZxU3RRRStQMUxMUytWYkNkcmcvQ0N6ZGJuL0ZIaWoramtVOUkxUGlMQnZOcGJyZFMvYUJ5MStEdmRtRENpMWxhSko5QXVLQlVqbGV5MzVhOFV5ODl4dnR0d0FndWtPNEx6cEUzNVdaQ1V1SlU9\&quot;},{\&quot;text\&quot;:\&quot;Unduh Infografik\&quot;,\&quot;url\&quot;:\&quot;https:\\/\\/web-api.bps.go.id\\/cover.php?f=y2U8BdUCZ7nhGdyd4wLrU0tMKzFLZkJueU1WMXhrdllWblJLbVZJaE5EZ2lzbFhWK05UY2JnYzV6Um5MbDJyeFRpQWFaMEhISWc1NERpTGE3WHM5c1dWdHRON1hmOFByK1A0bkNRPT0=\&quot;}]&quot;,
            &quot;category&quot;: null,
            &quot;content_html&quot;: &quot;&lt;p&gt;Tingkat Penghunian Kamar (TPK) pada Mei 2025 sebesar&amp;nbsp;30,02&amp;nbsp;persen dimana TPK hotel bintang&amp;nbsp;51,32&amp;nbsp;&amp;nbsp;persen dan nonbintang&amp;nbsp;23,32&amp;nbsp;persen.&amp;nbsp;&lt;/p&gt;&lt;p&gt;Rata-rata Lama Menginap (RLM) tamu pada Mei 2025 sebesar 1,12&amp;nbsp;malam, tercatat RLM hotel bintang sebesar 1,31&amp;nbsp;malam dan hotel nonbintang 1,02&amp;nbsp;malam.&lt;/p&gt;&quot;,
            &quot;content_text&quot;: &quot;Tingkat Penghunian Kamar (TPK) pada Mei 2025 sebesar&nbsp;30,02&nbsp;persen dimana TPK hotel bintang&nbsp;51,32&nbsp;&nbsp;persen dan nonbintang&nbsp;23,32&nbsp;persen.&nbsp;\n\nRata-rata Lama Menginap (RLM) tamu pada Mei 2025 sebesar 1,12&nbsp;malam, tercatat RLM hotel bintang sebesar 1,31&nbsp;malam dan hotel nonbintang 1,02&nbsp;malam.&quot;,
            &quot;created_at&quot;: &quot;2025-10-31T15:10:18.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-10-31T15:10:18.000000Z&quot;
        },
        {
            &quot;id&quot;: 28,
            &quot;date&quot;: &quot;2025-06-04T17:00:00.000000Z&quot;,
            &quot;title&quot;: &quot;Perkembangan Tingkat Penghunian Kamar Hotel di Kabupaten Kebumen April 2025&quot;,
            &quot;abstract&quot;: &quot;Tingkat Penghunian Kamar (TPK) pada April 2025 sebesar 38,16&nbsp;persen dimana TPK hotel bintang 59,18persen dan nonbintang 31,53&nbsp;persen.&nbsp;Rata-rata Lama Menginap (RLM) tamu pada April 2025 sebesar 1,13&nbsp;malam, tercatat RLM hotel bintang sebesar 1,41&nbsp;malam dan hotel nonbintang 1,02&nbsp;malam.&quot;,
            &quot;thumbnail_url&quot;: &quot;https://web-api.bps.go.id/cover.php?f=FkTjBNBX9V1xfHOblAJcQnZmRXFscVM5NEJBQmFsK3BaVjk4cm90SkM3R0p1L3BJV1ZXY3NKREVVdnJNSFJ4c2VwM1RqdGMrdFRjenZFL0p6a1Nic1d2UHNVQTRYY3pIcmZEdTdRPT0=&quot;,
            &quot;link&quot;: &quot;https://kebumenkab.bps.go.id/id/pressrelease/2025/06/05/377/perkembangan-tingkat-penghunian-kamar-hotel-di-kabupaten-kebumen-april-2025.html&quot;,
            &quot;pdf_url&quot;: &quot;https://web-api.bps.go.id/download.php?f=3nflj+9W74fiH7FI5kuQBklKVktqSmJLWGlhaVV5SFZLaXRwSGJXVm5rL2ppSEFmM09UYTliakF1Q1J4MU52dUlhSTg2dmRLZTBpaElrYWlLVVlXQWhmZW9WNUg4cG1SdElMWGVpb1Q3R2wyZktsbUFaNHh5Y2VTanZ5YWcxT2d1TXA1Y2x6a09vTDBnczI1R2l6VXNHTU9kYXpMem9sMXg0QTZxcTFLNlJ5YzhQb2UrTG5sK25YbUI0MHNXcWdNSWp5MUhZMGJhdnhYWFNPbFpGZzl2M2FjRDgvTk4xV1h2c2ZkblEwaVo2SnRPT2drVUVwd3RjbjM3dlU9&quot;,
            &quot;downloads&quot;: &quot;[{\&quot;text\&quot;:\&quot;Unduh Berita Resmi Statistik\&quot;,\&quot;url\&quot;:\&quot;https:\\/\\/web-api.bps.go.id\\/download.php?f=3nflj+9W74fiH7FI5kuQBklKVktqSmJLWGlhaVV5SFZLaXRwSGJXVm5rL2ppSEFmM09UYTliakF1Q1J4MU52dUlhSTg2dmRLZTBpaElrYWlLVVlXQWhmZW9WNUg4cG1SdElMWGVpb1Q3R2wyZktsbUFaNHh5Y2VTanZ5YWcxT2d1TXA1Y2x6a09vTDBnczI1R2l6VXNHTU9kYXpMem9sMXg0QTZxcTFLNlJ5YzhQb2UrTG5sK25YbUI0MHNXcWdNSWp5MUhZMGJhdnhYWFNPbFpGZzl2M2FjRDgvTk4xV1h2c2ZkblEwaVo2SnRPT2drVUVwd3RjbjM3dlU9\&quot;},{\&quot;text\&quot;:\&quot;Unduh Infografik\&quot;,\&quot;url\&quot;:\&quot;https:\\/\\/web-api.bps.go.id\\/cover.php?f=iOT7PMWhmGVnxhGDQRLmt2txWWVLaFFJSVBXaXdHSGREcFZDR2FETnMydWFZbmNRRnd6dlBYQzcraHlpWThsTUdXZnFZS0V0RGZzcEphd2lhVTBlZm91d3VZc1EvclBUUnMvMG1nPT0=\&quot;}]&quot;,
            &quot;category&quot;: null,
            &quot;content_html&quot;: &quot;&lt;p&gt;Tingkat Penghunian Kamar (TPK) pada April 2025 sebesar 38,16&amp;nbsp;persen dimana TPK hotel bintang 59,18&amp;nbsp;&amp;nbsp;persen dan nonbintang 31,53&amp;nbsp;persen.&amp;nbsp;&lt;/p&gt;&lt;p&gt;Rata-rata Lama Menginap (RLM) tamu pada April 2025 sebesar 1,13&amp;nbsp;malam, tercatat RLM hotel bintang sebesar 1,41&amp;nbsp;malam dan hotel nonbintang 1,02&amp;nbsp;malam.&lt;/p&gt;&quot;,
            &quot;content_text&quot;: &quot;Tingkat Penghunian Kamar (TPK) pada April 2025 sebesar 38,16&nbsp;persen dimana TPK hotel bintang 59,18&nbsp;&nbsp;persen dan nonbintang 31,53&nbsp;persen.&nbsp;\n\nRata-rata Lama Menginap (RLM) tamu pada April 2025 sebesar 1,13&nbsp;malam, tercatat RLM hotel bintang sebesar 1,41&nbsp;malam dan hotel nonbintang 1,02&nbsp;malam.&quot;,
            &quot;created_at&quot;: &quot;2025-10-31T15:10:21.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-10-31T15:10:21.000000Z&quot;
        },
        {
            &quot;id&quot;: 29,
            &quot;date&quot;: &quot;2025-05-08T17:00:00.000000Z&quot;,
            &quot;title&quot;: &quot;Perkembangan Tingkat Penghunian Kamar Hotel di Kabupaten Kebumen Maret 2025&quot;,
            &quot;abstract&quot;: &quot;Tingkat Penghunian Kamar (TPK) pada Maret 2025 sebesar 24,72 persen dimana TPK hotel bintang 39,32persen dan nonbintang 18,16 persen.&nbsp;Rata-rata Lama Menginap (RLM) tamu pada Maret 2025 sebesar 1,26 malam, tercatat RLM hotel bintang sebesar 1,56 malam dan hotel nonbintang 1,05 malam.&quot;,
            &quot;thumbnail_url&quot;: &quot;https://web-api.bps.go.id/cover.php?f=dri5q5CvQ00Po3eBeZQZgUNUaUlPMHRVMWtJb3JRNEFHd2ZSSDVjQUNObUZqVlF0UHQvZ0tHaGhNaG0xa2w0aDdLRzhMRmswVllXQWNmN1lRYmhpTmd3SmE4RXB6S3VhQ1pCU1VRPT0=&quot;,
            &quot;link&quot;: &quot;https://kebumenkab.bps.go.id/id/pressrelease/2025/05/09/376/perkembangan-tingkat-penghunian-kamar-hotel-di-kabupaten-kebumen-maret-2025.html&quot;,
            &quot;pdf_url&quot;: &quot;https://web-api.bps.go.id/download.php?f=rGdqwXOar6i+hf7RUgqOCHBxR0xSVVF1SjJTbXNOUHdnbjJTNVkrckduSXpYV2hua25VOFFWbDY3bW85SkZyMDRrKzgzNVZlWklYTkh5SzBnb3BzOXA3aHJrbUladWx1cVkzRjlST3dIY0RzTXhDdWltaTNkbjZBdnkzWE9uWDFKVTZTTjFZMjlyREx6UHlPMFBWS3ZXekZFaU1JR0VjKzd5U0hmaHFadTd0MTBoVDIvcjEzUStKTWpKbjJFcmQvT1VoS0M3ZFJxcmxVa1p1L2czMWdTaStiTGVmMFp6RjNDYk9JaEs5dGZrL0lzdElYd3FGUGpMYXk3WkU9&quot;,
            &quot;downloads&quot;: &quot;[{\&quot;text\&quot;:\&quot;Unduh Berita Resmi Statistik\&quot;,\&quot;url\&quot;:\&quot;https:\\/\\/web-api.bps.go.id\\/download.php?f=rGdqwXOar6i+hf7RUgqOCHBxR0xSVVF1SjJTbXNOUHdnbjJTNVkrckduSXpYV2hua25VOFFWbDY3bW85SkZyMDRrKzgzNVZlWklYTkh5SzBnb3BzOXA3aHJrbUladWx1cVkzRjlST3dIY0RzTXhDdWltaTNkbjZBdnkzWE9uWDFKVTZTTjFZMjlyREx6UHlPMFBWS3ZXekZFaU1JR0VjKzd5U0hmaHFadTd0MTBoVDIvcjEzUStKTWpKbjJFcmQvT1VoS0M3ZFJxcmxVa1p1L2czMWdTaStiTGVmMFp6RjNDYk9JaEs5dGZrL0lzdElYd3FGUGpMYXk3WkU9\&quot;},{\&quot;text\&quot;:\&quot;Unduh Infografik\&quot;,\&quot;url\&quot;:\&quot;https:\\/\\/web-api.bps.go.id\\/cover.php?f=zfRwiDfzQ\\/QPYgmR\\/oXB4nBGZnUrWEJHeEtzYk4yTGlkZWlaYm0wREJLcFcyU2lrUTd2aGl5TDNUZ2RvOWQ4MzlNejFsYVhUQ2tVQlJHYnI4SG5CUmlOOHJjOHlKSjYzQUxjQ2dBPT0=\&quot;}]&quot;,
            &quot;category&quot;: null,
            &quot;content_html&quot;: &quot;&lt;p data-asw-orgfontsize=\&quot;16\&quot; style=\&quot;font-size: 16px;\&quot;&gt;Tingkat Penghunian Kamar (TPK) pada Maret 2025 sebesar 24,72 persen dimana TPK hotel bintang 39,32&amp;nbsp;&amp;nbsp;persen dan nonbintang 18,16 persen.&amp;nbsp;&lt;/p&gt;&lt;p data-asw-orgfontsize=\&quot;16\&quot; style=\&quot;font-size: 16px;\&quot;&gt;Rata-rata Lama Menginap (RLM) tamu pada Maret 2025 sebesar 1,26 malam, tercatat RLM hotel bintang sebesar 1,56 malam dan hotel nonbintang 1,05 malam.&amp;nbsp;&lt;/p&gt;&quot;,
            &quot;content_text&quot;: &quot;Tingkat Penghunian Kamar (TPK) pada Maret 2025 sebesar 24,72 persen dimana TPK hotel bintang 39,32&nbsp;&nbsp;persen dan nonbintang 18,16 persen.&nbsp;\n\nRata-rata Lama Menginap (RLM) tamu pada Maret 2025 sebesar 1,26 malam, tercatat RLM hotel bintang sebesar 1,56 malam dan hotel nonbintang 1,05 malam.&quot;,
            &quot;created_at&quot;: &quot;2025-10-31T15:10:24.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-10-31T15:10:24.000000Z&quot;
        },
        {
            &quot;id&quot;: 30,
            &quot;date&quot;: &quot;2025-05-07T17:00:00.000000Z&quot;,
            &quot;title&quot;: &quot;Perkembangan Tingkat Penghunian Kamar Hotel di Kabupaten Kebumen Januari dan Februari 2025&quot;,
            &quot;abstract&quot;: &quot;Tingkat Penghunian Kamar (TPK) pada Januari 2025 sebesar 30,25 persen dimana TPK hotel bintang 43,63persen dan nonbintang 24,26 persen.&nbsp;Rata-rata Lama Menginap (RLM) tamu pada Januari 2025 sebesar 1,18 malam, tercatat RLM hotel bintang sebesar 1,41 malam dan hotel nonbintang 1,04 malam.&nbsp;Tingkat Penghunian Kamar (TPK) pada Februari 2025 sebesar 31,95 persen dimana TPK hotel bintang 50,08persen dan nonbintang 23,80 persen.&nbsp;Rata-rata Lama Menginap (RLM) tamu pada Februari 2025 sebesar 1,16 malam, tercatat RLM hotel bintang sebesar 1,56 malam dan hotel nonbintang 1,05 malam.&quot;,
            &quot;thumbnail_url&quot;: &quot;https://web-api.bps.go.id/cover.php?f=gyEuvdjYwvPuliUilqS6EEtENHJDTTc2WlBMc3puQlphUjVabk5nMENieWVLRHBaS0J5OGlDeThrcHMyVUJpVXVLUHB1Q0RheWU0VkV3MVlyM2V6cFh6KytRWnRIQTYvYjhYbnp3PT0=&quot;,
            &quot;link&quot;: &quot;https://kebumenkab.bps.go.id/id/pressrelease/2025/05/08/375/perkembangan-tingkat-penghunian-kamar-hotel-di-kabupaten-kebumen-januari-dan-februari-2025.html&quot;,
            &quot;pdf_url&quot;: &quot;https://web-api.bps.go.id/download.php?f=QvQ6/cQegjAwrnzFeGyasWhjRFdvWk1JeEFWa1BlRVpUdlVpL3g4T2MwbnhUWmQzeE0vUXJIMWN3dFZHQ3kzTVk5RHdYSTJ3ZmhuOEJCR0hUSmgrMk5Cc3Y4citra21zNHhGS0xySUhNT2RwYlhLMFlVRFVEVnJhdkxvZUVyS3lPZ1dwc0RpN2lsQzZCSUlIR09YY3BqcFpIQW8xNUE4ZDFtTE5rcTdHTzZDc3NLbzF1RFpPR21yVU9wYWt2ZC90TVpjMFBPWmVFbDMvSkxuNCtqTnFWcTE3a0F0UWIxclJmeHV5cGdFRUtNZTVnalNjMndqZDJISE4zRUdvTHdsaENLUzhQTUhiZEZOUk8rNkw=&quot;,
            &quot;downloads&quot;: &quot;[{\&quot;text\&quot;:\&quot;Unduh Berita Resmi Statistik\&quot;,\&quot;url\&quot;:\&quot;https:\\/\\/web-api.bps.go.id\\/download.php?f=QvQ6\\/cQegjAwrnzFeGyasWhjRFdvWk1JeEFWa1BlRVpUdlVpL3g4T2MwbnhUWmQzeE0vUXJIMWN3dFZHQ3kzTVk5RHdYSTJ3ZmhuOEJCR0hUSmgrMk5Cc3Y4citra21zNHhGS0xySUhNT2RwYlhLMFlVRFVEVnJhdkxvZUVyS3lPZ1dwc0RpN2lsQzZCSUlIR09YY3BqcFpIQW8xNUE4ZDFtTE5rcTdHTzZDc3NLbzF1RFpPR21yVU9wYWt2ZC90TVpjMFBPWmVFbDMvSkxuNCtqTnFWcTE3a0F0UWIxclJmeHV5cGdFRUtNZTVnalNjMndqZDJISE4zRUdvTHdsaENLUzhQTUhiZEZOUk8rNkw=\&quot;},{\&quot;text\&quot;:\&quot;Unduh Infografik\&quot;,\&quot;url\&quot;:\&quot;https:\\/\\/web-api.bps.go.id\\/cover.php?f=xgrTh+50Ef5V6virSzcJFEN1QUNqV1JmVlM0eDNYVVI1WlNGVkVnRUNaMmJOZzVTZzRGR1lLTFpYMWRabUF2RS9NMG0zK2JhVjkzWWxqT29YUGREWXJDOFZPQVNlYWpHOVRKWW1RPT0=\&quot;}]&quot;,
            &quot;category&quot;: null,
            &quot;content_html&quot;: &quot;&lt;p data-asw-orgfontsize=\&quot;16\&quot; style=\&quot;font-size: 16px;\&quot;&gt;&lt;strong&gt;Tingkat Penghunian Kamar (TPK) pada Januari 2025 sebesar 30,25 persen dimana TPK hotel bintang 43,63&amp;nbsp;&amp;nbsp;persen dan nonbintang 24,26 persen.&amp;nbsp;&lt;/strong&gt;&lt;/p&gt;&lt;p data-asw-orgfontsize=\&quot;16\&quot; style=\&quot;font-size: 16px;\&quot;&gt;&lt;strong&gt;Rata-rata Lama Menginap (RLM) tamu pada Januari 2025 sebesar 1,18 malam, tercatat RLM hotel bintang sebesar 1,41 malam dan hotel nonbintang 1,04 malam.&amp;nbsp;&lt;/strong&gt;&lt;/p&gt;&lt;p data-asw-orgfontsize=\&quot;16\&quot; style=\&quot;font-size: 16px;\&quot;&gt;&lt;strong&gt;Tingkat Penghunian Kamar (TPK) pada Februari 2025 sebesar 31,95 persen dimana TPK hotel bintang 50,08&amp;nbsp;&amp;nbsp;persen dan nonbintang 23,80 persen.&amp;nbsp;&lt;/strong&gt;&lt;/p&gt;&lt;p data-asw-orgfontsize=\&quot;16\&quot; style=\&quot;font-size: 16px;\&quot;&gt;&lt;strong&gt;Rata-rata Lama Menginap (RLM) tamu pada Februari 2025 sebesar 1,16 malam, tercatat RLM hotel bintang sebesar 1,56 malam dan hotel nonbintang 1,05 malam.&amp;nbsp;&lt;/strong&gt;&lt;/p&gt;&quot;,
            &quot;content_text&quot;: &quot;Tingkat Penghunian Kamar (TPK) pada Januari 2025 sebesar 30,25 persen dimana TPK hotel bintang 43,63&nbsp;&nbsp;persen dan nonbintang 24,26 persen.&nbsp;\n\nRata-rata Lama Menginap (RLM) tamu pada Januari 2025 sebesar 1,18 malam, tercatat RLM hotel bintang sebesar 1,41 malam dan hotel nonbintang 1,04 malam.&nbsp;\n\nTingkat Penghunian Kamar (TPK) pada Februari 2025 sebesar 31,95 persen dimana TPK hotel bintang 50,08&nbsp;&nbsp;persen dan nonbintang 23,80 persen.&nbsp;\n\nRata-rata Lama Menginap (RLM) tamu pada Februari 2025 sebesar 1,16 malam, tercatat RLM hotel bintang sebesar 1,56 malam dan hotel nonbintang 1,05 malam.&quot;,
            &quot;created_at&quot;: &quot;2025-10-31T15:10:26.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-10-31T15:10:26.000000Z&quot;
        },
        {
            &quot;id&quot;: 31,
            &quot;date&quot;: &quot;2025-02-25T17:00:00.000000Z&quot;,
            &quot;title&quot;: &quot;Perkembangan Tingkat Penghunian Kamar Hotel di Kabupaten Kebumen Desember 2024&quot;,
            &quot;abstract&quot;: &quot;TPK Tingkat Penghunian Kamar (TPK) pada Desember 2024 sebesar 36,31 persen dimana TPK hotel bintang 64,38&nbsp;&nbsp;persen dan nonbintang 26,29 persen.&nbsp;Rata-rata Lama Menginap (RLM) tamu pada Desember 2024 sebesar 1,20 malam, tercatat RLM hotel bintang sebesar 1,35 malam dan hotel nonbintang 1,09 malam.&quot;,
            &quot;thumbnail_url&quot;: &quot;https://web-api.bps.go.id/cover.php?f=xfywucFNjbN9Wh0uJghorjg3TXY2UWc4eDVBUTE2TmZGcUEyc2pJRm1YN1B0Y2tiNHgrcWZSUXB3SU1SenZvUlhKa2JMZklkWDdYVXlzdVdZTUZtNUoyN25Sd2VBMTBIWWpneGJjTkFHdVVsZFRla0VZMVlEUVNTOUV4eFBZcWZhd1hTTFV4S0RIQWVibGU3&quot;,
            &quot;link&quot;: &quot;https://kebumenkab.bps.go.id/id/pressrelease/2025/02/26/374/perkembangan-tingkat-penghunian-kamar-hotel-di-kabupaten-kebumen-desember-2024.html&quot;,
            &quot;pdf_url&quot;: null,
            &quot;downloads&quot;: &quot;[{\&quot;text\&quot;:\&quot;Unduh Berita Resmi Statistik\&quot;,\&quot;url\&quot;:\&quot;https:\\/\\/web-api.bps.go.id\\/download.php?f=5KL\\/uH6ffEC3wVJvth7Jc2hKYnNVMU5ZWHVNT1dOdUU0c3BFeTJtOFNJNml3ajV0NXdhVm9aK0dWOG90eWpUNDBEclBuNnVKRFhCc2t4Q2RhbGQ0bGRGR2pZSU9nZ0xGZ2llb0F5M0pYbkNNYUxUNDhUR2liOTl4R1U4Q0FZcC82cGd6WHFmTEZ3azhmd3RDUkRWcHBNSERmcHc3M3RjTjVic3FtZXpQSlZTdE1SU2lwRVRIdW1ucmp6WDVwOHNtZ0tOc25XRzM2Y2d4bi9yYmtrV3FuNWNsa3habExydnJsczE5TjdwWmUzeE54azdYWk1RdzNod250SWM0eFFNVld2L2pvUzJFOFdJNisxQk9ZTVNpSnRPRHMyT0VLb0ZTWlQvSzNQbTdmVUJSbXJ5L3poNm5VekVwL0FkN0xWYXd0U0FoNlc0ZlY2ZHA1TE5tUTB6SnBsTU9RdmlhZkxaN2hFZnlSaGR6S1VjZGVHWEpzbkFGSWtsa0s5cGxoUWJiMTc2TjE3ZlZsc3FXSm9xRm44YmJvY1pkU3E2ZDFIbFcyVm1JOTV4N1dFWGlBd0NmOEpMOGoyL0w2OGc9\&quot;},{\&quot;text\&quot;:\&quot;Unduh Infografik\&quot;,\&quot;url\&quot;:\&quot;https:\\/\\/web-api.bps.go.id\\/cover.php?f=BISfjUewqHYDV1t57Ky4xnVkTUZnUFFQWGozZzJIVE5pMVVJa2wySTRLVE11c2lhWXFDNHFnZnJnbnYwcW5aTXNPM3JqZWlPaWZQWDNnVnU4ZGFKSlh6TlV6ckJvNDlDdkFqeEhJaDBXWkZtQ24rRjhOV3hGalU1bE1uQ2pOc1MvMjE3Q2ljenpEaUpYQWdj\&quot;}]&quot;,
            &quot;category&quot;: null,
            &quot;content_html&quot;: &quot;&lt;li class=\&quot;MsoNormal\&quot; data-asw-orgfontsize=\&quot;16\&quot; style=\&quot;font-size: 16px;\&quot;&gt;TPK Tingkat Penghunian Kamar (TPK) pada Desember 2024 sebesar 36,31 persen dimana TPK hotel bintang 64,38&amp;nbsp;&amp;nbsp;persen dan nonbintang 26,29 persen.&amp;nbsp;&lt;o:p&gt;&lt;/o:p&gt;&lt;/li&gt;&lt;li class=\&quot;MsoNormal\&quot; data-asw-orgfontsize=\&quot;16\&quot; style=\&quot;font-size: 16px;\&quot;&gt;Rata-rata Lama Menginap (RLM) tamu pada Desember 2024 sebesar 1,20 malam, tercatat RLM hotel bintang sebesar 1,35 malam dan hotel nonbintang 1,09 malam.&amp;nbsp;&lt;/li&gt;&quot;,
            &quot;content_text&quot;: &quot;TPK Tingkat Penghunian Kamar (TPK) pada Desember 2024 sebesar 36,31 persen dimana TPK hotel bintang 64,38&nbsp;&nbsp;persen dan nonbintang 26,29 persen.&nbsp;\nRata-rata Lama Menginap (RLM) tamu pada Desember 2024 sebesar 1,20 malam, tercatat RLM hotel bintang sebesar 1,35 malam dan hotel nonbintang 1,09 malam.&quot;,
            &quot;created_at&quot;: &quot;2025-10-31T15:10:29.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-10-31T15:10:29.000000Z&quot;
        },
        {
            &quot;id&quot;: 32,
            &quot;date&quot;: &quot;2025-01-30T17:00:00.000000Z&quot;,
            &quot;title&quot;: &quot;Perkembangan Tingkat Penghunian Kamar Hotel di Kabupaten Kebumen November 2024&quot;,
            &quot;abstract&quot;: &quot;TPK Tingkat Penghunian Kamar (TPK) pada November 2024 sebesar 31,58 persen dimana TPK hotel bintang 56,97&nbsp;&nbsp;persen dan nonbintang 22,23 persen.&nbsp;Rata-rata Lama Menginap (RLM) tamu pada November 2024 sebesar 1,24 malam, tercatat RLM hotel bintang sebesar 1,46 malam dan hotel nonbintang 1,08 malam.&quot;,
            &quot;thumbnail_url&quot;: &quot;https://web-api.bps.go.id/cover.php?f=1KX2keutEIIQXe64yiyi22FnY0t0cWVxMmsxZnBhcnNTRjBGUkxVczdvR1lpM0Z1U2V1dE00YjAyV2NISkNZRkduTjdFbzA0T2pranRuWWdrcTNlaGROWFNGNnlRYXcxbllMMkczZ3EwcFVPREY4ZXBJWTVvdnkvQ1c3bUxWNHV4Z3FjWmVDRjhpdGxvMjVO&quot;,
            &quot;link&quot;: &quot;https://kebumenkab.bps.go.id/id/pressrelease/2025/01/31/373/perkembangan-tingkat-penghunian-kamar-hotel-di-kabupaten-kebumen-november-2024.html&quot;,
            &quot;pdf_url&quot;: null,
            &quot;downloads&quot;: &quot;[{\&quot;text\&quot;:\&quot;Unduh Berita Resmi Statistik\&quot;,\&quot;url\&quot;:\&quot;https:\\/\\/web-api.bps.go.id\\/download.php?f=4h9pJQSnAMbrZefU5XTRbW4xdTZNQm84WW9PWWxVQThrNHFEZDRZOGJJaWgxVVlJMm9FbG5vVm1jT3RnSDc3cFN4WldoN0wvY1NYWU1IMk1MZ080NXp6TzgvYjJjY1psNTM2cmpTQW5Xd09hTDdBOXJWSGxhQWRaaDdXUkdINXBTOFFySjZGSHgwb2lNWTE2Ulh1YUVXOWRYM3FMNTJrazZDYTUzOWFLQ29pR09SZWE5Z2Nlb3dTWnNlN3JTRDVJS2FvY2dUV2szSjVscWxGcXQ1Qi83bWw4dVhCVm5pOUlSZ1Y0dEIrVmtKTTM2bVQ0Z1hNOHcrRGx0NS9QVFdIMit4MDgyYVdTcSt1NUszUnpmaGthWFRtY2lhM0tXUkppWkhoeHhKMk1XNDJZR2UyMGIrYU5CaUMzelVtODVRNytrNkNwTkgrdVEwUEtUSVhUaVdybHRTWW5HelAwRmQ1M0pja3Z6a0dERU55cnJwMzVIbU1NdVZ0ZW0rYmFWUFhJOWpGYlBqN29aZXNPci9BZ014MFMydTQrSElXc0VXbGVYdEt4QVFXeXVYc24xdHhJckVTcXAyc0Q5eEk9\&quot;},{\&quot;text\&quot;:\&quot;Unduh Infografik\&quot;,\&quot;url\&quot;:\&quot;https:\\/\\/web-api.bps.go.id\\/cover.php?f=GpUHoBl8We83D9TXq2+bZXQwVUZxaVVWZ1RZNG1yZXVOdXRodkM4am9DNG9hR3F6b216R043cjdtblAzNjFKY2dUTW0wNnlBQmhrQUVrblRYWWVEbkQ5WjNVVmF3QThZZ3FvVFZLZlFBZXByUmJObmQ4R0FlblI3T3JnemNoQVhyekNpT252RExHSDZtUHhn\&quot;}]&quot;,
            &quot;category&quot;: null,
            &quot;content_html&quot;: &quot;&lt;li class=\&quot;MsoNormal\&quot; data-asw-orgfontsize=\&quot;16\&quot; style=\&quot;font-size: 16px;\&quot;&gt;TPK Tingkat Penghunian Kamar (TPK) pada November 2024 sebesar 31,58 persen dimana TPK hotel bintang 56,97&amp;nbsp;&amp;nbsp;persen dan nonbintang 22,23 persen.&amp;nbsp;&lt;o:p&gt;&lt;/o:p&gt;&lt;/li&gt;&lt;li class=\&quot;MsoNormal\&quot; data-asw-orgfontsize=\&quot;16\&quot; style=\&quot;font-size: 16px;\&quot;&gt;Rata-rata Lama Menginap (RLM) tamu pada November 2024 sebesar 1,24 malam, tercatat RLM hotel bintang sebesar 1,46 malam dan hotel nonbintang 1,08 malam.&amp;nbsp;&lt;/li&gt;&quot;,
            &quot;content_text&quot;: &quot;TPK Tingkat Penghunian Kamar (TPK) pada November 2024 sebesar 31,58 persen dimana TPK hotel bintang 56,97&nbsp;&nbsp;persen dan nonbintang 22,23 persen.&nbsp;\nRata-rata Lama Menginap (RLM) tamu pada November 2024 sebesar 1,24 malam, tercatat RLM hotel bintang sebesar 1,46 malam dan hotel nonbintang 1,08 malam.&quot;,
            &quot;created_at&quot;: &quot;2025-10-31T15:10:32.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-10-31T15:10:32.000000Z&quot;
        },
        {
            &quot;id&quot;: 33,
            &quot;date&quot;: &quot;2025-01-02T17:00:00.000000Z&quot;,
            &quot;title&quot;: &quot;Perkembangan Indeks Harga Konsumen Provinsi Jawa Tengah Desember 2024&quot;,
            &quot;abstract&quot;: &quot;Desember 2024 inflasi&nbsp;Year on Year&nbsp;(y-on-y) Provinsi Jawa Tengah sebesar 1,67 persen. Inflasi tertinggi terjadi di Kota Tegal sebesar 2,19 persen&quot;,
            &quot;thumbnail_url&quot;: &quot;https://web-api.bps.go.id/cover.php?f=zmfR9zz9Oqo2+qC/SDV7/HFqYWlMVDlHT0QxUXcvRGlRUXI2R0xuSk1JU1dZQ0JDeEQ5WkFNU0pnTWloS3FpTzdMcU9wTFA3THpUMUljQzZJNzJWUmxSVWpNT0VHQjJjM3dyaXYzaDYyVEM5MG5oUEQwNFkwN0hXelRDeEg0dDFxY21qZVduNkJGZkNORENGRndnbXpFdkVqWElubmR4WnFVeTZBdz09&quot;,
            &quot;link&quot;: &quot;https://kebumenkab.bps.go.id/id/pressrelease/2025/01/03/372/perkembangan-indeks-harga-konsumen-provinsi-jawa-tengah-desember-2024.html&quot;,
            &quot;pdf_url&quot;: null,
            &quot;downloads&quot;: &quot;[{\&quot;text\&quot;:\&quot;Unduh Berita Resmi Statistik\&quot;,\&quot;url\&quot;:\&quot;https:\\/\\/web-api.bps.go.id\\/download.php?f=GCm9MqpVGN7Jk2Ojd6sw32ZYSVNFVjlXSEJqQVlJcWdWWDNsTkw1akRwNUZkMFNPODdmZ1dyblZpd2N2ZGc0NUZmazN5NkIxbW0yK0M5TFB5WFFWbjdYd29WWDBleHRQTStZUTRPSm9KU0ZWekR3bTVLQ2hmWFJ5WHQ0WWQ4NVJaTTJzdWdaZU53NFRrTUdtTjFCSzNPcFhzZE95UHlVWmUwTDgwYjFlbjJqYnNNUksxSytGdDNzb3YzMndIOTJsaW8wZUg1RXU3THNRWlRZeWtPNWUreXRMMWJSTi9ROXRuQWh2RHUxOFQ1bU0xbGNIUFdROVpBdEVFbjBOektxVXYxMDlvTElpRG54WWdZajg2MnMyenlLclBTbmx4QXFCS0JncGc0Vk5jZDJoczF5enFQUCthcWNBaHlFV3BIZTZuVkJIUFVDNlg1WndadXVzeTVxWU1uZUZsVHpCTklTT3JFeEFRUU9OKzBhRGFRV1haVTVPUTNHc2g0eEYxMTVaRVdMQ2haanNqNDdYMkFGS3A5dmJQeTQvMm5aeEY0aWI0dm1wcVE9PQ==\&quot;},{\&quot;text\&quot;:\&quot;Unduh Infografik\&quot;,\&quot;url\&quot;:\&quot;https:\\/\\/web-api.bps.go.id\\/cover.php?f=JGQB2rCesdKKtIBrId6c+zMzb2RSSmVYb3ozQ01Id3B3dUdWV2dIRnFKMFJEVDdvNmJ2c1NXcHB1U1pPTlFvcS9UOXRpMVowMjZjZWdPVFh4eDMzdmxwWnc5UVJ0RnZ3Rng5OVJtTkNkR3ZGU1dueWY5d3JzZTBQeFJDcUxmZm1xSmN3ZHkrd0JRVjA3ZUZKOENOOU1SaUZDOG9FbEtZaUtBSm8wdz09\&quot;}]&quot;,
            &quot;category&quot;: null,
            &quot;content_html&quot;: &quot;&lt;span&gt;Desember 2024 inflasi&amp;nbsp;&lt;/span&gt;&lt;i&gt;Year on Year&lt;/i&gt;&lt;span&gt;&amp;nbsp;(&lt;/span&gt;&lt;i&gt;y-on-y&lt;/i&gt;&lt;span&gt;) Provinsi Jawa Tengah sebesar 1,67 persen. Inflasi tertinggi terjadi di Kota Tegal sebesar 2,19 persen&lt;/span&gt;&quot;,
            &quot;content_text&quot;: &quot;Desember 2024 inflasi&nbsp;Year on Year&nbsp;(y-on-y) Provinsi Jawa Tengah sebesar 1,67 persen. Inflasi tertinggi terjadi di Kota Tegal sebesar 2,19 persen&quot;,
            &quot;created_at&quot;: &quot;2025-10-31T15:14:06.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-11-20T14:37:30.000000Z&quot;
        },
        {
            &quot;id&quot;: 34,
            &quot;date&quot;: &quot;2025-01-02T17:00:00.000000Z&quot;,
            &quot;title&quot;: &quot;Perkembangan Nilai Tukar Petani dan Harga Produsen Gabah Jawa Tengah Desember 2024&quot;,
            &quot;abstract&quot;: &quot;Nilai Tukar Petani Jawa Tengah Desember 2024 sebesar 112,98 atau naik 0,73 persenHarga Gabah Kering Panen (GKP) di tingkat petani naik 0,92 persen dan Harga Gabah Kering Giling (GKG) di tingkat penggilingan naik 1,84 persen&quot;,
            &quot;thumbnail_url&quot;: null,
            &quot;link&quot;: &quot;https://kebumenkab.bps.go.id/id/pressrelease/2025/01/03/371/perkembangan-nilai-tukar-petani-dan-harga-produsen-gabah-jawa-tengah-desember-2024.html&quot;,
            &quot;pdf_url&quot;: null,
            &quot;downloads&quot;: &quot;[{\&quot;text\&quot;:\&quot;Unduh Berita Resmi Statistik\&quot;,\&quot;url\&quot;:\&quot;https:\\/\\/web-api.bps.go.id\\/download.php?f=z1mn4TOrdK017IezuHJsYll5VFlqTlNLSFRDbTR5UFNWZHl3RlR6OXI4QU9Zb2VrcmNqZ0g3UWRhM24zaGtDL1g4NDlDR1B5YXkzNG1UOXEraVpkUFo4VE5rQjhnQmsvOVpHd1hOcHNSaTZaT3dRVisvS2tBWWRnZllpOWhpU0hmNFc0MktxNllIN2RGMi9sQXVWakQ0VWlIUWliWnUzQjY4ZVhuV0tvNnJqL09xTkU2a1k0TDNndnFJYi9TK240alV0aG81ejFvL1NKZG13YWthbkpVaTlsdVJ0dUpUNVQyTlBTZ3V1STRFeURReHBRQnU1M1I5cDdVZHpLNng1ZVlEY2V4WllRL1FFNkpPRzRQald5ZUpzQmQydDE3UFlrTXBML3Rlem9rNUdXNlRaU3d3TlpiUjRvaS9JUUdveVliZmFBOWxJVW9ONnNKcjhhdEtJR2xreVg3eG10bWgyZkdNN3N0K2pkOGM3UFJNdHJSZmt1VllsMnVCdzM1UkZmNVRpSURtd1NEUXBRUkk3MDd1WjNyRitEY0RvYUdITlVGT3ExdURGdkxJVXpFWStWOEF4TjYrN2Y2NzA9\&quot;},{\&quot;text\&quot;:\&quot;Unduh Infografik\&quot;,\&quot;url\&quot;:\&quot;https:\\/\\/web-api.bps.go.id\\/cover.php?f=2QnmLSBNSKif6yltvWvrx1M3YUZwOFRIUkJVaENrM3ZUZWV1OHc0VExFdVlnWWlUa29pYzNFaWVmWi81b1N3SGJXTHBYRFZtVDRVUXZkYmxSc2VTeFpRU01KWlZlc0tLb0tSN21Ha3FnNzhvbUxFT1B1enc3Y0ZybDF3RXhrVlkzYlJya2lmTnVIT1d0WUNiempPUm5NWUh6N1VrVUE3K21Ocnp2Zz09\&quot;}]&quot;,
            &quot;category&quot;: null,
            &quot;content_html&quot;: &quot;&lt;ul&gt;&lt;li data-asw-orgfontsize=\&quot;16\&quot;&gt;&lt;span data-asw-orgfontsize=\&quot;16\&quot;&gt;&lt;span lang=\&quot;EN\&quot; data-asw-orgfontsize=\&quot;16\&quot;&gt;Nilai Tukar Petani Jawa Tengah Desember 2024 sebesar 112,98 atau naik 0,73 persen&lt;/span&gt;&lt;/span&gt;&lt;/li&gt;&lt;li data-asw-orgfontsize=\&quot;16\&quot;&gt;&lt;span data-asw-orgfontsize=\&quot;16\&quot;&gt;&lt;span lang=\&quot;EN\&quot; data-asw-orgfontsize=\&quot;16\&quot;&gt;Harga Gabah Kering Panen (GKP) di tingkat petani naik 0,92 persen dan Harga Gabah Kering Giling (GKG) di tingkat penggilingan naik 1,84 persen&lt;/span&gt;&lt;/span&gt;&lt;/li&gt;&lt;/ul&gt;&quot;,
            &quot;content_text&quot;: &quot;Nilai Tukar Petani Jawa Tengah Desember 2024 sebesar 112,98 atau naik 0,73 persen\nHarga Gabah Kering Panen (GKP) di tingkat petani naik 0,92 persen dan Harga Gabah Kering Giling (GKG) di tingkat penggilingan naik 1,84 persen&quot;,
            &quot;created_at&quot;: &quot;2025-10-31T15:14:10.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-10-31T15:14:10.000000Z&quot;
        },
        {
            &quot;id&quot;: 35,
            &quot;date&quot;: &quot;2025-01-02T17:00:00.000000Z&quot;,
            &quot;title&quot;: &quot;Perkembangan Statistik Transportasi Jawa Tengah November 2024&quot;,
            &quot;abstract&quot;: &quot;Jumlah kedatangan (debarkasi) penumpang angkutan udara yang datang ke Jawa Tengah pada November 2024 sebanyak 144.491 orang, turun 0,50 persen dibandingkan Oktober 2024Jumlah kedatangan (debarkasi) penumpang angkutan laut pada November 2024 tercatat sebanyak 23.251 orang, turun 20,39 persen dibandingkan Oktober 2024&quot;,
            &quot;thumbnail_url&quot;: null,
            &quot;link&quot;: &quot;https://kebumenkab.bps.go.id/id/pressrelease/2025/01/03/370/perkembangan-statistik-transportasi-jawa-tengah-november-2024.html&quot;,
            &quot;pdf_url&quot;: null,
            &quot;downloads&quot;: &quot;[{\&quot;text\&quot;:\&quot;Unduh Berita Resmi Statistik\&quot;,\&quot;url\&quot;:\&quot;https:\\/\\/web-api.bps.go.id\\/download.php?f=1Spb9I9NJqHmyRqz\\/JTnSlZOUEU5Sk5BRmFZc01wMmEvNnhpcWFxT2NnZTB5RVc1MHBkemg5eUxvZnJLeTdaallYc0lvV29COGlnemxXNk5wM2ZGRUdJTDJiUzV0N05CN0ttNmhZRGUvM0tOMGVZeWowdGZGMW5yRXhndGxUS1NjUFh5b1ptSVNuc3RKcmlqV0svMWhRdjRkMVUvU2ZKUXQ0c2VFWVpLcit3OUZYNHdsQ09WMCs4TGpDYjhwc1cvbkgyUUxQdXFYa1Z3QTdSSm9UdmJiT2tFKzl1M2FnQ3ltUmdCVTZjNy9RcFBSd3hPc0RacVZGVXVMOHhNUjMzK1F5K25YUm4zck43ei9samlLMEtxRHNxUTNGQSszNTByVFQvWVpoRGpxTkFNNTB2R2F0eUl2bStkeHM2U05IeTBBQTI4RVQ3MFhXM29kSitHTnFCY3kxRWJQY0tsbDkwSDhvMHQ0djFkZ0VHUW1mTXQ2aUw2UkptS3ZmeXVkSXlzQVlqemZqNDJVWDVBZXU1bzIvd1k0clVJMzczVnlhYUdhU3JsWHc9PQ==\&quot;},{\&quot;text\&quot;:\&quot;Unduh Infografik\&quot;,\&quot;url\&quot;:\&quot;https:\\/\\/web-api.bps.go.id\\/cover.php?f=3EyBaAw58CqY8SvOe6eLEGdDaS9ZKzFTNmpIUjNpYkwyc2o1TmV6bjdNYVFEYi8xWGswdEt3QS8yVmNKTFpWSDdIWDl5UzlGZ3h2aWFpZXVVaHZHTUpadDVZdTVXeXdrUjI1eGtKWXVsYVhzS0hlcjhWY2NvOVhJdEVYRzdOVlJZYkVoaWVmNEFPRDVzY1o0NW0well2cVlaZitLRGdjYzVWamRIQT09\&quot;}]&quot;,
            &quot;category&quot;: null,
            &quot;content_html&quot;: &quot;&lt;ul&gt;&lt;li&gt;&lt;span&gt;&lt;span lang=\&quot;id\&quot;&gt;Jumlah kedatangan (debarkasi) penumpang angkutan udara yang datang ke Jawa Tengah pada November 2024 sebanyak 144.491 orang, turun 0,50 persen dibandingkan Oktober 2024&lt;/span&gt;&lt;/span&gt;&lt;/li&gt;&lt;li&gt;&lt;span&gt;&lt;span lang=\&quot;id\&quot;&gt;Jumlah kedatangan (debarkasi) penumpang angkutan laut pada November 2024 tercatat sebanyak 23.251 orang, turun 20,39 persen dibandingkan Oktober 2024&lt;/span&gt;&lt;/span&gt;&lt;/li&gt;&lt;/ul&gt;&quot;,
            &quot;content_text&quot;: &quot;Jumlah kedatangan (debarkasi) penumpang angkutan udara yang datang ke Jawa Tengah pada November 2024 sebanyak 144.491 orang, turun 0,50 persen dibandingkan Oktober 2024\nJumlah kedatangan (debarkasi) penumpang angkutan laut pada November 2024 tercatat sebanyak 23.251 orang, turun 20,39 persen dibandingkan Oktober 2024&quot;,
            &quot;created_at&quot;: &quot;2025-10-31T15:14:14.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-10-31T15:14:14.000000Z&quot;
        },
        {
            &quot;id&quot;: 36,
            &quot;date&quot;: &quot;2025-01-02T17:00:00.000000Z&quot;,
            &quot;title&quot;: &quot;Perkembangan Pariwisata Jawa Tengah November 2024&quot;,
            &quot;abstract&quot;: &quot;Tingkat Penghunian Kamar (TPK) pada November 2024 sebesar 35,42 persen dimana TPK hotel bintang 47,12 persen dan nonbintang 22,88 persen.Rata-rata Lama Menginap (RLM) tamu pada November 2024 sebesar 1,24 malam, tercatat RLM hotel bintang sebesar 1,35 malam dan hotel nonbintang 1,06 malam.Pada Januari-November 2024, perjalanan wisatawan nusantara (wisnus) tujuan Jawa Tengah mencapai 134,58 juta perjalanan. Jumlah ini naik 26,33 persen dibandingkan kumulatif periode yang sama pada tahun 2023.&quot;,
            &quot;thumbnail_url&quot;: &quot;https://web-api.bps.go.id/cover.php?f=M0M0ykpw71i3mRDzy5Tw+zFFazdqS0R5WjI5RDJleFluZ0UvQXhzbExMclJzSHpPQ0RKdUpyUDRaR05adHMvWWw0QmEyV3NmT0NsaHZ1WmVWRXBuSGFsbm5USVpSd3N1RjlkSEZhZnlWak1LYWR6Q05TWEdweHhJK2dLT1FQVm9sd3ZOTVMwdEdYdUltdmo2V25xbUJoK1ZvSGdXTzVSd2I4TTg0QT09&quot;,
            &quot;link&quot;: &quot;https://kebumenkab.bps.go.id/id/pressrelease/2025/01/03/369/perkembangan-pariwisata-jawa-tengah-november-2024.html&quot;,
            &quot;pdf_url&quot;: null,
            &quot;downloads&quot;: &quot;[{\&quot;text\&quot;:\&quot;Unduh Berita Resmi Statistik\&quot;,\&quot;url\&quot;:\&quot;https:\\/\\/web-api.bps.go.id\\/download.php?f=sKTt8NnHqGRmD19yQkefZlNlSytheURKVVVOMytXbUJQdGNFSFRXMEwyWkRTWUdhVjltYnNoVERGTU1ENjYxVFJqT29VZks5Rlc5by96eWttc3ByWVdYdmtVMjNXaEc3ekdxREROUGFhQ1VvSG54a0lzbnEvT2RrejRHbnVudUhUOWVINUpwZ3FDMm1weGZOU1o2VTNOdXNpaVlxaW9zNXlRc0JOZHc5bXhqSXJ5RzdmRUErTlJxN1FLN3FZUml4UmJIQ3JiUnpKR0g0RWJxZmQ1SmdySGVUVGdLclY3b0p2ekRPMnhxVVUveXd6RnBnMjV3QmdGWUFwcTVXZ0pINyt2a1d2eGpMNDgvMXdXbS9od3hTUktEaTZEY0NwSnByS3E4UmIvUCtLdml5MUd0bHpmeXJCK1R4OGwwelZzby9PNXEyMmt3NFdHekpaaWpIR0RCd1Bmc0lCM1kyWDNPR1k1UjliYnZXQW45andZR2kwVzBmYnpidm90c0VWeDhlZ3Q0L2tOMmtFQVljcDJyTg==\&quot;},{\&quot;text\&quot;:\&quot;Unduh Infografik\&quot;,\&quot;url\&quot;:\&quot;https:\\/\\/web-api.bps.go.id\\/cover.php?f=is3ThHDSuRq6+QukWkO672o0VEQyd3V1czgwSWx4aCt4eDhMMjV0UmFuOEFmRlFaZVhRdXN6T2tCTUs4MTRaL2VvL3dWVGhuYWlQd2ZFNE91MG9EaFRyYkdWU0NrSExRY01OVXNwSXdRVnRZbU8yd2h5VlllTGh0eTEvTXBhc0RmR0YwK29GR0ZRY244bzR4aExhRjFBU2tIeW9wNmZJSk5lbHNoQT09\&quot;}]&quot;,
            &quot;category&quot;: null,
            &quot;content_html&quot;: &quot;&lt;ul&gt;&lt;li&gt;&lt;span&gt;&lt;span lang=\&quot;id\&quot;&gt;Tingkat Penghunian Kamar (TPK) pada November 2024 sebesar 35,42 persen dimana TPK hotel bintang 47,12 persen dan nonbintang 22,88 persen.&lt;/span&gt;&lt;/span&gt;&lt;/li&gt;&lt;li&gt;&lt;span&gt;&lt;span lang=\&quot;id\&quot;&gt;Rata-rata Lama Menginap (RLM) tamu pada November 2024 sebesar 1,24 malam, tercatat RLM hotel bintang sebesar 1,35 malam dan hotel nonbintang 1,06 malam.&lt;/span&gt;&lt;/span&gt;&lt;/li&gt;&lt;li&gt;&lt;span&gt;&lt;span lang=\&quot;id\&quot;&gt;Pada Januari-November 2024, perjalanan wisatawan nusantara (wisnus) tujuan Jawa Tengah mencapai 134,58 juta perjalanan. Jumlah ini naik 26,33 persen dibandingkan kumulatif periode yang sama pada tahun 2023.&lt;/span&gt;&lt;/span&gt;&lt;/li&gt;&lt;/ul&gt;&quot;,
            &quot;content_text&quot;: &quot;Tingkat Penghunian Kamar (TPK) pada November 2024 sebesar 35,42 persen dimana TPK hotel bintang 47,12 persen dan nonbintang 22,88 persen.\nRata-rata Lama Menginap (RLM) tamu pada November 2024 sebesar 1,24 malam, tercatat RLM hotel bintang sebesar 1,35 malam dan hotel nonbintang 1,06 malam.\nPada Januari-November 2024, perjalanan wisatawan nusantara (wisnus) tujuan Jawa Tengah mencapai 134,58 juta perjalanan. Jumlah ini naik 26,33 persen dibandingkan kumulatif periode yang sama pada tahun 2023.&quot;,
            &quot;created_at&quot;: &quot;2025-10-31T15:14:17.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-11-20T14:38:45.000000Z&quot;
        },
        {
            &quot;id&quot;: 37,
            &quot;date&quot;: &quot;2024-12-10T17:00:00.000000Z&quot;,
            &quot;title&quot;: &quot;Perkembangan Tingkat Penghunian Kamar Hotel di Kabupaten Kebumen Oktober 2024&quot;,
            &quot;abstract&quot;: &quot;TPKTingkat Penghunian Kamar (TPK) pada Oktober 2024 sebesar 30,55 persendimana TPK hotel bintang 44,63 &nbsp;persen dan nonbintang 24,41 persen.&nbsp;Rata-rata Lama Menginap (RLM) tamu pada Oktober2024 sebesar 1,23 malam, tercatat RLM hotel bintang sebesar 1,42 malam danhotel nonbintang 1,11 malam.&quot;,
            &quot;thumbnail_url&quot;: &quot;https://web-api.bps.go.id/images/default.png&quot;,
            &quot;link&quot;: &quot;https://kebumenkab.bps.go.id/id/pressrelease/2024/12/11/368/perkembangan-tingkat-penghunian-kamar-hotel-di-kabupaten-kebumen-oktober-2024.html&quot;,
            &quot;pdf_url&quot;: null,
            &quot;downloads&quot;: &quot;[{\&quot;text\&quot;:\&quot;Unduh Berita Resmi Statistik\&quot;,\&quot;url\&quot;:\&quot;https:\\/\\/web-api.bps.go.id\\/download.php?f=qbrN1kBdRDf4WilTc5yp0UVHamF3dVdXZE92Rk1NUHF6bGtDZjF1V01LN2xBdVFBTVlPaTlQSjQzbVVYVlFXajZEbzhBbnZzbDd4c3pUM2dwMUFrdHAzK29hVW9XSE1KbXVwdnJxUEdyalNZdmNGNm5URnJZTFp4akovRitlcTY1YkRLTnhJVkJVVnF6OVJCcVg0cVYvVFd5VTduM1RJT2tVUXVmK3M5d1ZhWk1CT3dOakFLUm1waGhGcCtuVGxTZ1VyL0MydUpobVRxd3BtWWllSEhPTzJTOFZaZ3pzSWh6citRa1JGekFuNXdweXVrOEQ4bHNxWkRLc1MrZHNVdGY0N3JRRVBpQ3R5emlPQ0dPcUhqMndKSnlYZXhQbG4rTjhaUGpsc1F6Ymk1VXJUM1lQdjVYVFd3aXQzMjYzUXFzanZ5OXNPdWZvOWlXTzNQQXpRTmZHTXFXTmtFeGE2N0VRazVzbDBndzBKUERlNjc5Q01EUjh0YTZsRjQweXF6bkFvQSt3QzNXQjNkalpjUGZiejY1TjlCRWdGOGZvWXNlTFNwdGRDVS9wakx1aDcyRWViMFFRanVOQkk9\&quot;},{\&quot;text\&quot;:\&quot;Unduh Infografik\&quot;,\&quot;url\&quot;:\&quot;https:\\/\\/web-api.bps.go.id\\/cover.php?f=ktBbK0p9H47+scwV9quPg0grYlFoUU9RNlBzNC9jVDRsdWlXbTRkM3pDQUNmc1lNN0FHK2xBT0pzd2MyVUxnNGJ5TDVaaHhOalQ2T2R5SlRxQW9QSkRRREJOcm0zK1Q5SEhJN0VWd2xaZnFmYnI5L2ZhWjdnNEZlblljM1VOMVQ1OXd1cnh2eS9DRS9SSzR4U2VselVDZGI4LzBnYlk5QVBwRVkvUT09\&quot;}]&quot;,
            &quot;category&quot;: null,
            &quot;content_html&quot;: &quot;&lt;ul&gt;&lt;li&gt;&lt;span&gt;TPK\n     Tingkat Penghunian Kamar (TPK) pada Oktober 2024 sebesar 30,55 persen\n     dimana TPK hotel bintang 44,63 &lt;/span&gt;&lt;span&gt;&amp;nbsp;&lt;/span&gt;&lt;span&gt;persen dan nonbintang 24,41 persen.&amp;nbsp;&lt;/span&gt;&lt;/li&gt;&lt;li&gt;&lt;span&gt;Rata-rata Lama Menginap (RLM) tamu pada Oktober\n2024 sebesar 1,23 malam, tercatat RLM hotel bintang sebesar 1,42 malam dan\nhotel nonbintang 1,11 malam.&amp;nbsp;&lt;/span&gt;&lt;/li&gt;&lt;/ul&gt;&quot;,
            &quot;content_text&quot;: &quot;TPK Tingkat Penghunian Kamar (TPK) pada Oktober 2024 sebesar 30,55 persen dimana TPK hotel bintang 44,63 &nbsp;persen dan nonbintang 24,41 persen.&nbsp;\nRata-rata Lama Menginap (RLM) tamu pada Oktober 2024 sebesar 1,23 malam, tercatat RLM hotel bintang sebesar 1,42 malam dan hotel nonbintang 1,11 malam.&quot;,
            &quot;created_at&quot;: &quot;2025-10-31T15:14:21.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-10-31T15:14:21.000000Z&quot;
        }
    ],
    &quot;pagination&quot;: {
        &quot;current_page&quot;: 1,
        &quot;last_page&quot;: 2,
        &quot;per_page&quot;: 15,
        &quot;total&quot;: 21
    }
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-content-press-releases" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-content-press-releases"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-content-press-releases"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-content-press-releases" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-content-press-releases">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-content-press-releases" data-method="GET"
      data-path="api/content/press-releases"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-content-press-releases', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-content-press-releases"
                    onclick="tryItOut('GETapi-content-press-releases');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-content-press-releases"
                    onclick="cancelTryOut('GETapi-content-press-releases');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-content-press-releases"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/content/press-releases</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-content-press-releases"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-content-press-releases"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-GETapi-content-infographics">Get Infographics with pagination, filtering, and sorting</h2>

<p>
</p>



<span id="example-requests-GETapi-content-infographics">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/content/infographics" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/content/infographics"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-content-infographics">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;Infographics retrieved successfully&quot;,
    &quot;data&quot;: [
        {
            &quot;id&quot;: 14,
            &quot;title&quot;: &quot;Infografis Pemerintahan Kec Karanggayam2024&quot;,
            &quot;date&quot;: &quot;2025-11-08&quot;,
            &quot;category&quot;: &quot;Statistik Demografi dan Sosial&quot;,
            &quot;image_url&quot;: &quot;https://web-api.bps.go.id/cover.php?f=8+HHBBUd7QNnQnLoSwK3iEx4ZXBtdDBXWkJ1VWwrYlljUkZtMFdqL24xcTFMbC84aTMyZGxHS25RWFNMb1VjVkx3RmdjR01mK0JZVURTS3RrUjNMYXhzRWlKUWxhdThzcCtVSjhGSXFadGpkSUpuaVhBV2NYT0d0WFJUbXptTkRjaW1iK1JXeTRBS2hjMkV1dXZNdGJnbzRDdVQreHpCN01XaGg4QT09&quot;,
            &quot;link&quot;: &quot;https://kebumenkab.bps.go.id/id/infographic?id=1020&quot;,
            &quot;description&quot;: null,
            &quot;created_at&quot;: &quot;2025-11-08T14:20:04.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-11-08T14:20:04.000000Z&quot;
        },
        {
            &quot;id&quot;: 3,
            &quot;title&quot;: &quot;Perkembangan Tingkat Penghunian Kamar Hotel di Kabupaten Kebumen Agustus 2025(1)2025-10-10&quot;,
            &quot;date&quot;: &quot;2025-01-01&quot;,
            &quot;category&quot;: &quot;Statistik Ekonomi&quot;,
            &quot;image_url&quot;: &quot;https://web-api.bps.go.id/cover.php?f=RDtK/UGrPDfjXJX5pazIilNTUWNPNCtaVytBTnJiTER1UkJmSFcwUnNWMmlETDF1UFJpM0NaWWdKSy8zYm5UdjdUSUE3bzYyRWtYRlFrWlFaZ0JwbmZSVlo4MEt0Y2UxcnhBYUpBPT0=&quot;,
            &quot;link&quot;: &quot;https://kebumenkab.bps.go.id/id/infographic?id=1038&quot;,
            &quot;description&quot;: null,
            &quot;created_at&quot;: &quot;2025-11-08T08:40:45.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-11-08T08:40:45.000000Z&quot;
        },
        {
            &quot;id&quot;: 43,
            &quot;title&quot;: &quot;Tingkat Penganggran Kabupaten/Kota di Jateng 2024 - Infografik&quot;,
            &quot;date&quot;: &quot;2024-12-31&quot;,
            &quot;category&quot;: null,
            &quot;image_url&quot;: &quot;https://web-api.bps.go.id/cover.php?f=EHCrK2Xr2nfp83T2szQ45GVyUS8yMyswYUxSS0pzQWNPd2hGR1ZKaTVVNHV0TU1RcG5iWkpTay8vQ0hvemNWV0RDeUYxYWE5MkxVT3BvQzR0bVBhWWFTUjFUR3dmcjRiTzhTeCtuYzF1NjZOZ0ZYaXBhN25HeXNBOXpSQUZzK3ZzQVk1NVJnU1JxL05KMW1tWG9TWU5UYmRtQnM3aXVEYW1KQnoyUT09&quot;,
            &quot;link&quot;: &quot;https://kebumenkab.bps.go.id/id/infographic?id=1098&quot;,
            &quot;description&quot;: null,
            &quot;created_at&quot;: &quot;2025-12-04T02:56:11.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-12-04T02:56:11.000000Z&quot;
        },
        {
            &quot;id&quot;: 1,
            &quot;title&quot;: &quot;Infografis Sosial dan KesRa Kec Karangsambung 2024&quot;,
            &quot;date&quot;: &quot;2024-01-01&quot;,
            &quot;category&quot;: &quot;Statistik Demografi dan Sosial&quot;,
            &quot;image_url&quot;: &quot;https://web-api.bps.go.id/cover.php?f=4nHpR0xHg/H/FqBuWhjoZ2RzNWpvY1ZKck9rK29Ib09ud1RqMXR0MGlIZ0hkbUtXOGRLbWVxUHdxb0txRXNJOU1KZnBncEZGR0VCZ1EyTXFjbWlWVm5nRXl2NjZWaDFtNnJTVjNITjR1UnFpam1DYUZ0NzJzMzNmdHlJTDM4RzVqWDYzTEs2OHVjNGRRL1NrcGo1L3ZGZlRGOHZrTStwUjVhNys0QT09&quot;,
            &quot;link&quot;: &quot;https://kebumenkab.bps.go.id/id/infographic?id=1036&quot;,
            &quot;description&quot;: null,
            &quot;created_at&quot;: &quot;2025-11-08T08:40:45.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-11-08T08:40:45.000000Z&quot;
        },
        {
            &quot;id&quot;: 2,
            &quot;title&quot;: &quot;Infografis Pemerintahan Kec karangsambung 2024&quot;,
            &quot;date&quot;: &quot;2024-01-01&quot;,
            &quot;category&quot;: &quot;Statistik Demografi dan Sosial&quot;,
            &quot;image_url&quot;: &quot;https://web-api.bps.go.id/cover.php?f=wZRfWZKQLXhhmRNIkZiQ7lF3b3RuaGRraG5VanpuMVBwVllFaHJqM2kzZ1ZTZFk0OHo3WjFZbUFzeUU4NVhjQVgrR2g5Z2d5QzBvcFRWMzN2Q1dkbHc3NjBnMHJ1RDJmN043T2hIS2hzck1DTHlHRW1QbEEwKzE5MnlmUGwzRFlNQk8waS84cmZpMmZLbXFDV3FSZ3dwM1JLZUtKdVJrbzhoNGxndz09&quot;,
            &quot;link&quot;: &quot;https://kebumenkab.bps.go.id/id/infographic?id=1033&quot;,
            &quot;description&quot;: null,
            &quot;created_at&quot;: &quot;2025-11-08T08:40:45.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-11-08T08:40:45.000000Z&quot;
        },
        {
            &quot;id&quot;: 4,
            &quot;title&quot;: &quot;Infografis Pariwisata Kec Karangsambung 2024&quot;,
            &quot;date&quot;: &quot;2024-01-01&quot;,
            &quot;category&quot;: &quot;Statistik Ekonomi&quot;,
            &quot;image_url&quot;: &quot;https://web-api.bps.go.id/cover.php?f=kbQgVZ38urvWn8jRV5BSZTc2ZlZ4Nm9vVkVDV2V1ZTVmNlpPbXFlTjR0VlQrSzkrZitHeW5UbDUzYnFTcG80TGRHRGVjWm9HMytMOUdVMnNnZE41MTZmQW10RnJsWFIyTEsxUVNyb3BHVXpDWXRraWYwYWZuN3JvQmVIc25CU3Nsc3pmby9LT3BLNjVkM3pFL1BtMEN3MGpPY053QXZxSGx2MUxYQT09&quot;,
            &quot;link&quot;: &quot;https://kebumenkab.bps.go.id/id/infographic?id=1037&quot;,
            &quot;description&quot;: null,
            &quot;created_at&quot;: &quot;2025-11-08T08:40:45.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-11-08T08:40:45.000000Z&quot;
        },
        {
            &quot;id&quot;: 5,
            &quot;title&quot;: &quot;Infografis Pertanian Kecamatan Karangsambung 2024&quot;,
            &quot;date&quot;: &quot;2024-01-01&quot;,
            &quot;category&quot;: &quot;Statistik Lingkungan Hidup dan Multidomain&quot;,
            &quot;image_url&quot;: &quot;https://web-api.bps.go.id/cover.php?f=NCPou6p1FN2gWcLsnxsCLTBNTHlvVi9zWjBwWkZlNmFaWDVPSW9lcXQybmJ3dzVpTWhKWUVKQ1Z2a2FsQjNvREQzL0JMMW1BTFpVT04wdHVwYkZHRkhBNWV4b0dDOHZHa2wwV3NldGVNKzV5VWxMUXBGV1lrWGJ2WjNxQlkycmkyWjVQcmR1RHVRcDZxSmVtV3dmOHFBQ1N4YjZJaGVob1ZEamZGUT09&quot;,
            &quot;link&quot;: &quot;https://kebumenkab.bps.go.id/id/infographic?id=1034&quot;,
            &quot;description&quot;: null,
            &quot;created_at&quot;: &quot;2025-11-08T08:40:45.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-11-08T08:40:45.000000Z&quot;
        },
        {
            &quot;id&quot;: 6,
            &quot;title&quot;: &quot;Infografis Pertanian Kecamatan Sadang 2024&quot;,
            &quot;date&quot;: &quot;2024-01-01&quot;,
            &quot;category&quot;: &quot;Statistik Lingkungan Hidup dan Multidomain&quot;,
            &quot;image_url&quot;: &quot;https://web-api.bps.go.id/cover.php?f=f63GUgZaz4UosxFLNCZ6Ok1YNE81RS81NVVPOWxYZUhQd3NTdEl4amtrMHlxZmJFNmNHTFFLZVh4cGZYeHdwRjZVcVlvVU9TRk10SmFvR0JvUEFSeXNVNkdKU0dPb084TEFsZU00aUxYUkpncGFsMG51dklFZ25lME01KzYvelVyUHFJMVBWZDhOUFBhTDUybXBnMDEySUVHdDRyeTBvMzFnMEg4dz09&quot;,
            &quot;link&quot;: &quot;https://kebumenkab.bps.go.id/id/infographic?id=1027&quot;,
            &quot;description&quot;: null,
            &quot;created_at&quot;: &quot;2025-11-08T08:40:45.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-11-08T08:40:45.000000Z&quot;
        },
        {
            &quot;id&quot;: 7,
            &quot;title&quot;: &quot;Infografis Penduduk Kecamatan Karangsambung 2024&quot;,
            &quot;date&quot;: &quot;2024-01-01&quot;,
            &quot;category&quot;: &quot;Statistik Demografi dan Sosial&quot;,
            &quot;image_url&quot;: &quot;https://web-api.bps.go.id/cover.php?f=wTlGY8rgAp3HJWhpIpd0DjBONFYvZEdUUVBOcHpOTWNubzNlZVhadFRZWGRTYVNyNmhUTmc5cm5Bc1ZscHBUeFBET0c4NjdqZ2RNZFpJdGMyNFdnYnozWmp1TUh3N2R1azdQaVplOXpwb3B2NGZNWDlrYXVFemVycTJ3Vm9EdlpDNkJ1R1dDSDJhOFYvZnNyZ3dyUTBGbmthOE9zTlY5K0tOeUlYZz09&quot;,
            &quot;link&quot;: &quot;https://kebumenkab.bps.go.id/id/infographic?id=1032&quot;,
            &quot;description&quot;: null,
            &quot;created_at&quot;: &quot;2025-11-08T14:20:04.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-11-08T14:20:04.000000Z&quot;
        },
        {
            &quot;id&quot;: 8,
            &quot;title&quot;: &quot;Infografis Geografi Kecamatan Karangsambung 2024&quot;,
            &quot;date&quot;: &quot;2024-01-01&quot;,
            &quot;category&quot;: &quot;Statistik Demografi dan Sosial&quot;,
            &quot;image_url&quot;: &quot;https://web-api.bps.go.id/cover.php?f=ooH7FeKNccP1HcNIWN7A0npPU0E0V1grVDBDSWNRZmVqazRmMk01NkF6MVBIU3NmWWszdTM3VzlGWFF2NCtpR2VEaWRRMTdnKy9zN2daRXloNjRyMnhIbFY4cHpOMG1kcjhSQlgzL1hoNEdUVVFmdnpqZk1kQjZTWmJFc1JOZmgrSzZDeDc1TjRXa0FmNlRoS3UzZUJrdW5Tak1yZXpVeUdEck1HUT09&quot;,
            &quot;link&quot;: &quot;https://kebumenkab.bps.go.id/id/infographic?id=1031&quot;,
            &quot;description&quot;: null,
            &quot;created_at&quot;: &quot;2025-11-08T14:20:04.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-11-08T14:20:04.000000Z&quot;
        },
        {
            &quot;id&quot;: 9,
            &quot;title&quot;: &quot;Infografis Sosial dan KesRa Kecamatan Sadang 2024&quot;,
            &quot;date&quot;: &quot;2024-01-01&quot;,
            &quot;category&quot;: &quot;Statistik Demografi dan Sosial&quot;,
            &quot;image_url&quot;: &quot;https://web-api.bps.go.id/cover.php?f=f8A45y2KupDYTrsnVP+fJG1UTStuR0tQaTNXZU9Vb2U4WDNDZ2RxN3VSYVkvaFJ3SVpGclVBUGErUFNVd2tPWGxTbW95eXBpbjdialF2b1NOQkxCcGhOd0FxN1lYUFVVTTZieURiQWNRSCsrekNYWlRsWTBXNUdRSWFMaFlrSTlRYWx1WCtWdDhuZHlRaE1mMitydThBdWxCcnFaWkNQVExaZytUZz09&quot;,
            &quot;link&quot;: &quot;https://kebumenkab.bps.go.id/id/infographic?id=1029&quot;,
            &quot;description&quot;: null,
            &quot;created_at&quot;: &quot;2025-11-08T14:20:04.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-11-08T14:20:04.000000Z&quot;
        },
        {
            &quot;id&quot;: 10,
            &quot;title&quot;: &quot;Infografis Pemerintahan Kecamatan Sadang 2024&quot;,
            &quot;date&quot;: &quot;2024-01-01&quot;,
            &quot;category&quot;: &quot;Statistik Demografi dan Sosial&quot;,
            &quot;image_url&quot;: &quot;https://web-api.bps.go.id/cover.php?f=IFRYwj4L4HUx1yID9qDse05lcFFTdXpsTVNOZkVQYm5uVTMydEEzQnFKOFdLdGJzRm9MQmEyK3JLbVY3ZVQ5Znk4Uk94RzdBdG9yeFErMHZRd20zamZqOHNEM0pvUlZtTStQSVpxcHFIYyszWXBFMkFubWhmL1lQdWl4ZGx0NWhhTm5zRkxIaWpHYWFkWGFyUHpzbktaMmJIQjdqTzZHNlFJZVRxQT09&quot;,
            &quot;link&quot;: &quot;https://kebumenkab.bps.go.id/id/infographic?id=1026&quot;,
            &quot;description&quot;: null,
            &quot;created_at&quot;: &quot;2025-11-08T14:20:04.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-11-08T14:20:04.000000Z&quot;
        },
        {
            &quot;id&quot;: 11,
            &quot;title&quot;: &quot;Infografis Penduduk Kecamatan Sadang 2024&quot;,
            &quot;date&quot;: &quot;2024-01-01&quot;,
            &quot;category&quot;: &quot;Statistik Demografi dan Sosial&quot;,
            &quot;image_url&quot;: &quot;https://web-api.bps.go.id/cover.php?f=arR+r1QZN3b1mPLe6lY3tkl6STU1TzJPZFJYeEsxTUJBRG9vVmxXb3RNQlJ5d0FKVjR0ZXU4ZzJkTXFHOTl0VWdlMVFiZWsrTTAyZDJKcC9ja1hEclkrSk43S2VCL25ONVYwQVVHM2JqanNuMFFDRkIyRjZxQlljQUlJdEtKQ3lEYmorUEVrcitkNzlOa1dzMVdJbzlLeEt3VFJOZlJLakhScndxZz09&quot;,
            &quot;link&quot;: &quot;https://kebumenkab.bps.go.id/id/infographic?id=1025&quot;,
            &quot;description&quot;: null,
            &quot;created_at&quot;: &quot;2025-11-08T14:20:04.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-11-08T14:20:04.000000Z&quot;
        },
        {
            &quot;id&quot;: 12,
            &quot;title&quot;: &quot;Infografis Geografi Kecamatan Sadang 2024&quot;,
            &quot;date&quot;: &quot;2024-01-01&quot;,
            &quot;category&quot;: &quot;Statistik Demografi dan Sosial&quot;,
            &quot;image_url&quot;: &quot;https://web-api.bps.go.id/cover.php?f=6CXohVGRrRFnL/x2+uPk9k8rbG05U0ZDdkk4bm5vM1p0cFJnSXBqM3EyU3pYVUZiTEF4OG1jOW9UUTZqMTZyQ3ovVjNDeTZuL3k5UFp4V0h2OXl5NVBOVTNGaStZU0RvY0x2aHFIUTRCaW05VHVBSVloTXZsSWJIalkzbTVaTlFmeXFHNmg4Z2RXbGNvWDhBUHlPWlJ2UTg2TUtGeDF4NFZBNTBvQT09&quot;,
            &quot;link&quot;: &quot;https://kebumenkab.bps.go.id/id/infographic?id=1024&quot;,
            &quot;description&quot;: null,
            &quot;created_at&quot;: &quot;2025-11-08T14:20:04.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-11-08T14:20:04.000000Z&quot;
        },
        {
            &quot;id&quot;: 13,
            &quot;title&quot;: &quot;Infografis Sosial dan KesRa Kec Karanggayam 2024&quot;,
            &quot;date&quot;: &quot;2024-01-01&quot;,
            &quot;category&quot;: &quot;Statistik Demografi dan Sosial&quot;,
            &quot;image_url&quot;: &quot;https://web-api.bps.go.id/cover.php?f=8HqpIR6znW8EQyU67rl+A25vNXpHTXE3eXdKSmQ2UlNJekFXNG9uT05mV1RuOFBSUi9hWjkvUGFOLzJZN0RuOXdOSWhhNXV1VFE0MEJ3ZUFpUVFON2tnQ0ZCaTQyVFNaS3lxaE1aa0ZQWVp3blAyNXA5V1B5N3hlUmlseGJzWHZ4bExBOWs0Yy9oelVVaEZ3U3k4WWxIZEFGUE9JYVVOZE5pWm95dz09&quot;,
            &quot;link&quot;: &quot;https://kebumenkab.bps.go.id/id/infographic?id=1022&quot;,
            &quot;description&quot;: null,
            &quot;created_at&quot;: &quot;2025-11-08T14:20:04.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-11-08T14:20:04.000000Z&quot;
        }
    ],
    &quot;pagination&quot;: {
        &quot;current_page&quot;: 1,
        &quot;last_page&quot;: 3,
        &quot;per_page&quot;: 15,
        &quot;total&quot;: 42
    }
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-content-infographics" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-content-infographics"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-content-infographics"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-content-infographics" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-content-infographics">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-content-infographics" data-method="GET"
      data-path="api/content/infographics"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-content-infographics', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-content-infographics"
                    onclick="tryItOut('GETapi-content-infographics');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-content-infographics"
                    onclick="cancelTryOut('GETapi-content-infographics');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-content-infographics"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/content/infographics</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-content-infographics"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-content-infographics"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-GETapi-content-publications">Get Publications with pagination, filtering, and sorting</h2>

<p>
</p>



<span id="example-requests-GETapi-content-publications">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/content/publications" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/content/publications"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-content-publications">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;Publications retrieved successfully&quot;,
    &quot;data&quot;: [
        {
            &quot;id&quot;: 55,
            &quot;title&quot;: &quot;Statistik Kesejahteraan Rakyat Kabupaten Kebumen 2025&quot;,
            &quot;release_date&quot;: &quot;2025-11-27T17:00:00.000000Z&quot;,
            &quot;category&quot;: null,
            &quot;cover_url&quot;: &quot;http://localhost:8000/storage/content_images/1764748743_hshshshs.jpeg&quot;,
            &quot;pdf_url&quot;: &quot;https://web-api.bps.go.id/download.php?f=fHBa+Hw5QBI1fF52C6wP+VhCNU53SXRrNVJubHF6RUpxb1paakdxdXl5UllEekxINzVvRWhYOTdwQno1YnpEVkNhdGc0aWQ1TDB0SlNmaUlvVEsyVEFXZ09iVEMyZ0tCaVFLcUlBaFo0Z3VRNzd2RVVqLzd0UGpCTEZ0VWcrTEpBckRVMkgvanRRcHIrSjQ0dTRnVG0wd1ZsSCtsVDM2SmZEN2ovSUlvYzRWK203QVo3V2JQL3NxRmdQQ3FLN09VTWpQamcwZEpxN0ZkMy9scGFjVkF6UjhjUWJUcTY3Q0JpVHFDK1VQOVNkNHMvS1R3dmZHT0wraU85Nnl0aFJSRjdpK3BpOUF1Q3RBQTZJNlFNY3RSamZmMnR5ZTQwbjNyc2ZYdHlnPT0=&quot;,
            &quot;downloads&quot;: null,
            &quot;link&quot;: &quot;https://kebumenkab.bps.go.id/id/publication/2025/11/28/13cafb664383f4a55145b954/statistik-kesejahteraan-rakyat-kabupaten-kebumen-2025.html&quot;,
            &quot;abstract&quot;: &quot;Statistik Kesejahteraan Rakyat Kabupaten Kebumen Tahun 2025 merupakan publikasi yang menyajikan gambaran tentang taraf kesejahteraan rakyat beserta perkembangannya di Kabupaten Kebumen. Gambaran tersebut ditunjukkan dalam beberapa aspek kehidupan antara lain bidang kependudukan dan keluarga berencana, kesehatan, pendidikan, ketenagakerjaan, perumahan dan pengeluaran/konsumsi penduduk. Statistik Kesejahteraan Rakyat yang disajikan dalam publikasi ini bersumber dari hasil Survei Sosial Ekonomi Nasional (Susenas) yang merupakan survei berbasis rumah tangga. Publikasi ini menyajikan data-data hasil Susenas yang dilaksanakan pada bulan Maret 2025 terhadap 890 rumah tangga sampel. Publikasi ini diharapkan dapat memenuhi kebutuhan pengguna data akan data data sosial ekonomi.&quot;,
            &quot;created_at&quot;: &quot;2025-12-03T07:57:50.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-12-03T07:59:03.000000Z&quot;
        },
        {
            &quot;id&quot;: 24,
            &quot;title&quot;: &quot;Kabupaten Kebumen Dalam Infografis 2025&quot;,
            &quot;release_date&quot;: &quot;2025-10-29T17:00:00.000000Z&quot;,
            &quot;category&quot;: null,
            &quot;cover_url&quot;: &quot;https://web-api.bps.go.id/cover.php?f=T0Fg5P8/1rtRyDhgFAnbPDBCdGdiY1pWSU5NcCtvbWJyVXdnb0dLRDZLdTNYc2QyTERGWkl5dnRpbGtsWEdTNWh6M0N1VlhhUmlvZE9sZTg4VU4xaXZRbWNrNXpJNzM0SXNreEl6L2ZCbDloRk9mbm5nZVFKRFBKUjh3Q3Iwb1g3RVVwaU8yRVArU0FjbGVE&quot;,
            &quot;pdf_url&quot;: &quot;https://web-api.bps.go.id/download.php?f=uMgh+yYKft+MRoXWiQLUIEphaGppQUY5ektsVVd0WXRZQ2ROVWd3bUxNMGpJV3VkcnpDcENRb3doY0Nvczl5T2ZLU3hRV24waVBRWkI4RUR0YnhTa3JlbVlnZnc5OC9RUGlJNHV5MG12Nis4K2Z4cHFGcCs4OTd4aklKTExlUFhDL2ZWd3MwQ0krMWhSVXpwZXYxb09LeFZBUjZ4d25OLzlwc1RNWjBveWVnUmZycWtzdlUzNzVSY3FOWnpEVTVCYXF2L1hBM0lyOGlUQTFyclNLWkM2SmlqckhHczRhdnNhVENXSmhEdWVmekx1RU1PN1dzaHpROHQ2dWZVNjNxa1dSeEd4TkpJQS9JZW1MaTdEQlMvOSt1d1FEQTBjVkhZQk5GTW9BPT0=&quot;,
            &quot;downloads&quot;: &quot;[{\&quot;text\&quot;:\&quot;Unduh Publikasi\&quot;,\&quot;url\&quot;:\&quot;https:\\/\\/web-api.bps.go.id\\/download.php?f=uMgh+yYKft+MRoXWiQLUIEphaGppQUY5ektsVVd0WXRZQ2ROVWd3bUxNMGpJV3VkcnpDcENRb3doY0Nvczl5T2ZLU3hRV24waVBRWkI4RUR0YnhTa3JlbVlnZnc5OC9RUGlJNHV5MG12Nis4K2Z4cHFGcCs4OTd4aklKTExlUFhDL2ZWd3MwQ0krMWhSVXpwZXYxb09LeFZBUjZ4d25OLzlwc1RNWjBveWVnUmZycWtzdlUzNzVSY3FOWnpEVTVCYXF2L1hBM0lyOGlUQTFyclNLWkM2SmlqckhHczRhdnNhVENXSmhEdWVmekx1RU1PN1dzaHpROHQ2dWZVNjNxa1dSeEd4TkpJQS9JZW1MaTdEQlMvOSt1d1FEQTBjVkhZQk5GTW9BPT0=\&quot;}]&quot;,
            &quot;link&quot;: &quot;https://kebumenkab.bps.go.id/id/publication/2025/10/30/8bb53e2c0a8d17c42683a161/kabupaten-kebumen-dalam-infografis-2025.html&quot;,
            &quot;abstract&quot;: &quot;Kabupaten Kebumen dalam Infografis 2025 adalah publikasi tahunan BPS Kabupaten Kebumen sebagai pelengkap dari publikasi Kabupaten Kebumen Dalam Angka 2025. Pada Kabupaten Kebumen Dalam Angka 2025, data disajikan dalam bentuk tabel, sedangkan pada publikasi Kabupaten Kebumen Dalam Infografis, data disajikan secara visual dalam bentuk infografis. Tujuan disusunnya Kabupaten Kebumen dalam Infografis adalah sebagai upaya meningkatkan literasi pengguna terhadap data statistik. Topik dipilih berdasarkan isu terkini atau fakta menarik dari data Kabupaten Kebumen Dalam Angka 2025. Dengan visualisasi yang menarik, diharapkan pengguna lebih mudah memahami data yang disajikan.&quot;,
            &quot;created_at&quot;: &quot;2025-10-30T13:21:00.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-10-30T13:21:00.000000Z&quot;
        },
        {
            &quot;id&quot;: 25,
            &quot;title&quot;: &quot;Cerita Data Statistik&quot;,
            &quot;release_date&quot;: &quot;2025-10-20T17:00:00.000000Z&quot;,
            &quot;category&quot;: null,
            &quot;cover_url&quot;: &quot;https://web-api.bps.go.id/cover.php?f=7q2DUo258OmHJr/k9fFo1nhhYzJtWVdnWjFsalF5N1F4MmpPYzRubDgrY3IwVDNyTFhYNlRBWHh2UGlabS9kR2ZaZHgxNDBkSjFmektQYm1ab1J5YSt2V1lPUnh6YmFpdVhYWDgra3dXVGw4OVh0ejBSTjhTVGdkWk9ydENKYTI1VUhhU1lSNW11WXF1Nlo2&quot;,
            &quot;pdf_url&quot;: &quot;https://web-api.bps.go.id/download.php?f=Ux7WcKHLerydfR/wSfq4vWtDWFZZWGNJMlhrTCs5SGI1eGdkbG93OTFBU3pHM1YrUDNlaThqd2pOWnovaFNWcmpuNy8xaXRDSDU0V3VNTjRVQkJJNEZrRG5yQW5JeEp0b092NXFjaS9kWUpIcmtHT0N2aU5RY01STTUxZFFKMmhBNWpuRlE1MFZLWHVreVBSRXBQblpPcHRUMGFrMGhSaEF2cHVCZFdtNDdrS1JRUGhEWUdEVkNmclJkT2oyamkzOFE0eWFEVXZiaWNZdXFyVFYzNUdZd1gxKzhOQVUxMW5nNkVHZEpNRkJ3Y0ltSkhMSjBtUzlVdUJtVEk9&quot;,
            &quot;downloads&quot;: &quot;[{\&quot;text\&quot;:\&quot;Unduh Publikasi\&quot;,\&quot;url\&quot;:\&quot;https:\\/\\/web-api.bps.go.id\\/download.php?f=Ux7WcKHLerydfR\\/wSfq4vWtDWFZZWGNJMlhrTCs5SGI1eGdkbG93OTFBU3pHM1YrUDNlaThqd2pOWnovaFNWcmpuNy8xaXRDSDU0V3VNTjRVQkJJNEZrRG5yQW5JeEp0b092NXFjaS9kWUpIcmtHT0N2aU5RY01STTUxZFFKMmhBNWpuRlE1MFZLWHVreVBSRXBQblpPcHRUMGFrMGhSaEF2cHVCZFdtNDdrS1JRUGhEWUdEVkNmclJkT2oyamkzOFE0eWFEVXZiaWNZdXFyVFYzNUdZd1gxKzhOQVUxMW5nNkVHZEpNRkJ3Y0ltSkhMSjBtUzlVdUJtVEk9\&quot;}]&quot;,
            &quot;link&quot;: &quot;https://kebumenkab.bps.go.id/id/publication/2025/10/21/237c592e647aa999eeceda7d/cerita-data-statistik.html&quot;,
            &quot;abstract&quot;: &quot;Cerita Data Statistik disusun untuk memberikan gambaran menyeluruh mengenai kondisi ketahanan pangan, pola konsumsi rumah tangga, serta berbagai faktor sosial ekonomi yang memengaruhi kesejahteraan masyarakat di Kabupaten Kebumen.Data yang tersaji dalam publikasi ini bersumber dari berbagai survei Badan Pusat Statistik, termasuk Survei Sosial Ekonomi Nasional (Susenas) dan Potensi Desa (PODES), serta didukung oleh literatur dan penelitian terkait. Melalui penyajian dalam bentuk narasi yang lebih komunikatif, publikasi ini diharapkan dapat menjadi referensi bagi pemerintah daerah, akademisi, praktisi, maupun masyarakat umum dalam merumuskan kebijakan dan program pembangunan, khususnya di bidang ketahanan pangan.&quot;,
            &quot;created_at&quot;: &quot;2025-10-30T13:21:00.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-10-30T13:21:00.000000Z&quot;
        },
        {
            &quot;id&quot;: 26,
            &quot;title&quot;: &quot;Booklet SAKERNAS (Survei Angkatan Kerja Nasional) Agustus 2024&quot;,
            &quot;release_date&quot;: &quot;2025-10-07T17:00:00.000000Z&quot;,
            &quot;category&quot;: &quot;Tenaga Kerja&quot;,
            &quot;cover_url&quot;: &quot;https://web-api.bps.go.id/cover.php?f=u2ZLUiZvr1ghJv5tl8x/CEN3eTJLZW9qVGdFaVFzSktHUDc5MEt3MFQrWlc4MjhYOWdKNWl4a0syOVl5dnZmUDVOM3ZBMjViQnpUa2N5Z0tLNklEcklDQWJXNWE1bmJtRmhvdXUzcjFjNnN0ZzVIeGFXdTRvalloVE9LbThnQldsblRoNUl3WFd3ZDRuUkE0&quot;,
            &quot;pdf_url&quot;: &quot;https://web-api.bps.go.id/download.php?f=Keclw/XBUSPfENjbB1CEl01FV1l4SU1JNXRGV09TMW44dkJoMk9tVnEvejhBby9RMERjK0Y5cEtBQllXNkg1bEVueFVoQUVZTCtOeFNJVWZrS0FqNk5aT3NxL2t0TkFvdE1XMXk0RHlTdGtpUTM0QW1uT0ltVlF5QnR3TSt3RGdxSS9SdjJlQ3JGVGFjMVJFZ1IzSFprT1p6UHFKYjhtWjhwMUY0SVY4RUxIZG9SQ1FRNmZVRnB3MkgrZzRnSkppbDhxVmkwWDdjQVNiWjd5dVgxUWZqQlJxN01RL1h6UHFWQVRzUm9vc2g0WldHb2FuWHRaZFJ6WVluSU05bmJxK2p2QVJnVWdpVXFDMDFicXVzN0dEWTBHSldCb2VIa2t2eDlsZWg5dW9hREQzTXFUSnN6cEd4cmJCRkljPQ==&quot;,
            &quot;downloads&quot;: &quot;[{\&quot;text\&quot;:\&quot;Unduh Publikasi\&quot;,\&quot;url\&quot;:\&quot;https:\\/\\/web-api.bps.go.id\\/download.php?f=Keclw\\/XBUSPfENjbB1CEl01FV1l4SU1JNXRGV09TMW44dkJoMk9tVnEvejhBby9RMERjK0Y5cEtBQllXNkg1bEVueFVoQUVZTCtOeFNJVWZrS0FqNk5aT3NxL2t0TkFvdE1XMXk0RHlTdGtpUTM0QW1uT0ltVlF5QnR3TSt3RGdxSS9SdjJlQ3JGVGFjMVJFZ1IzSFprT1p6UHFKYjhtWjhwMUY0SVY4RUxIZG9SQ1FRNmZVRnB3MkgrZzRnSkppbDhxVmkwWDdjQVNiWjd5dVgxUWZqQlJxN01RL1h6UHFWQVRzUm9vc2g0WldHb2FuWHRaZFJ6WVluSU05bmJxK2p2QVJnVWdpVXFDMDFicXVzN0dEWTBHSldCb2VIa2t2eDlsZWg5dW9hREQzTXFUSnN6cEd4cmJCRkljPQ==\&quot;}]&quot;,
            &quot;link&quot;: &quot;https://kebumenkab.bps.go.id/id/publication/2025/10/08/09b60fd50c3545d61e071d98/booklet-sakernas--survei-angkatan-kerja-nasional--agustus-2024.html&quot;,
            &quot;abstract&quot;: &quot;Booklet SAKERNAS (Survei Angkatan Kerja Nasional) Agustus 2024 merupakan publikasi yang berisi indikator hasil Survei Angkatan Kerja Nasional (Sakernas) Agustus 2024. Terdapat 10 indikator utama ketenagakerjaan yang disajikan hasil pengukurannya pada booklet ini.&quot;,
            &quot;created_at&quot;: &quot;2025-10-30T13:21:00.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-10-30T13:21:00.000000Z&quot;
        },
        {
            &quot;id&quot;: 27,
            &quot;title&quot;: &quot;Statistik Pendidikan Kabupaten Kebumen 2024&quot;,
            &quot;release_date&quot;: &quot;2025-09-29T17:00:00.000000Z&quot;,
            &quot;category&quot;: null,
            &quot;cover_url&quot;: &quot;https://web-api.bps.go.id/cover.php?f=q6VHowuV8l1n8RDHj7HhgG1jK1NRUnd3T3U5ekp1Z0FkNld0NlBjMmJIMFNpVlFDdUVqYjloY1hIVEswWlhxaktpNnB2ck1PMlJJZTVrVGxEeC92R2hDS002UHNVb1pvcGxsZFZzNGZ5VEpWOWNhYmlpZHIzakJlMDFWemRsblBmVnJvZ2R6VWplbXgxOXFu&quot;,
            &quot;pdf_url&quot;: &quot;https://web-api.bps.go.id/download.php?f=eEOGRdQT2uYtn01L/YarBGN4ZU1xVERQUkltR2l2a0dlNm0xalZXNVBXUHhONEVWRjhya3ZQT2h3YjNQRWhRQk9kU3RzY2N2VFg2OG9rSUE3UlJSUVFpczNHT2pNZnZkaHhnT3JPWmFVd0lWSWUvTVdVaHRmQ1dIWVk4djVXQjVGb0RkdFBnd3dFem9aT25mVk80bGRwUlMrVzQ1R3FzL3VQUTd2Q05FZ2RaYm9RbnhCT3JmVk5HdGZpUzhrdWdtOExrWWl6cnN0a3YwclVLcDFEczhqYlpzcjlkUEtsbGMxamFDUkJwR1ByVTRlak1XYU9SRzY2dkRnL0xMU2poMDJrdGVHRFBpSENsU25LTW1JNUlaR1FSUXVTNC9ja2FWbDNJMVVnPT0=&quot;,
            &quot;downloads&quot;: &quot;[{\&quot;text\&quot;:\&quot;Unduh Publikasi\&quot;,\&quot;url\&quot;:\&quot;https:\\/\\/web-api.bps.go.id\\/download.php?f=eEOGRdQT2uYtn01L\\/YarBGN4ZU1xVERQUkltR2l2a0dlNm0xalZXNVBXUHhONEVWRjhya3ZQT2h3YjNQRWhRQk9kU3RzY2N2VFg2OG9rSUE3UlJSUVFpczNHT2pNZnZkaHhnT3JPWmFVd0lWSWUvTVdVaHRmQ1dIWVk4djVXQjVGb0RkdFBnd3dFem9aT25mVk80bGRwUlMrVzQ1R3FzL3VQUTd2Q05FZ2RaYm9RbnhCT3JmVk5HdGZpUzhrdWdtOExrWWl6cnN0a3YwclVLcDFEczhqYlpzcjlkUEtsbGMxamFDUkJwR1ByVTRlak1XYU9SRzY2dkRnL0xMU2poMDJrdGVHRFBpSENsU25LTW1JNUlaR1FSUXVTNC9ja2FWbDNJMVVnPT0=\&quot;}]&quot;,
            &quot;link&quot;: &quot;https://kebumenkab.bps.go.id/id/publication/2025/09/30/ffe9f139b0cad7e43483471d/statistik-pendidikan-kabupaten-kebumen-2024-.html&quot;,
            &quot;abstract&quot;: &quot;Statistik Pendidikan Kabupaten Kebumen 2024 menggambarkan kondisi pendidikan di Kabupaten Kebumen berdasarkan hasil Susenas Maret 2024. Data yang disajikan mencakup beberapa indikator utama proses dan capaian pendidikan. Selain itu juga disajikan data hasil registrasi sekolah yang dikumpulkan oleh Kementerian Pendidikan dan Kebudayaan untuk Tahun Ajaran 2023/2024. Data ini memuat informasi mengenai jumlah sekolah, peserta didik, dan jumlah guru.&quot;,
            &quot;created_at&quot;: &quot;2025-10-30T13:33:09.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-10-30T13:33:09.000000Z&quot;
        },
        {
            &quot;id&quot;: 28,
            &quot;title&quot;: &quot;Kecamatan Ayah Dalam Angka 2025&quot;,
            &quot;release_date&quot;: &quot;2025-09-25T17:00:00.000000Z&quot;,
            &quot;category&quot;: null,
            &quot;cover_url&quot;: &quot;https://web-api.bps.go.id/cover.php?f=utJY9x2lhkzCF4pgq83sZE1SWFNhWERydmRZTHh1ZHRINW5qVnRDaXFEODByNyttYjYwM0pwZWlVeTNZTFhVRmZpdW9qSm4vUTNvK2NFQUovSUp5dmFmRnd4ajhxRTg3UmhjRkRSUEhHdFB5TGRISWF5ZHhXdHRWcEFMSDNaV3R6MWZVeXpvdjN5RFhPam90&quot;,
            &quot;pdf_url&quot;: &quot;https://web-api.bps.go.id/download.php?f=hwQc+WQRNtyFXRZF1jCDJGhEQXVkSHJmUHZYenAvQ2xUQ2lPZis0a3RFMEFDZ3J6UEdEeWVSMmJVRGJUMm92czBjbHJ4MHc3Z1RRU1kreThodE1ZQlVKRVhQVUhHY2NwSGdHb01zWitzajdRbUk0dEJGTnRWS3RyL2tqUXVMc015Zlp1dlY5dHk4YXVPVisvcUV3ZktHZERzNzU2RjEwUU9IQnRvVWpsRHA1Z3hXelVldHY5ODJjZDFIZTN2ZTBRbW90Q1BmaDc1TWcrbEtieXJQanAwSHZRRkRxVGlKR0tVak1wNVA1QVU5Y2R2eXNRWURNbUpxTVJObHRjTzY1YkRzUDh0YVcvaktrRC9uK3o=&quot;,
            &quot;downloads&quot;: &quot;[{\&quot;text\&quot;:\&quot;Unduh Publikasi\&quot;,\&quot;url\&quot;:\&quot;https:\\/\\/web-api.bps.go.id\\/download.php?f=hwQc+WQRNtyFXRZF1jCDJGhEQXVkSHJmUHZYenAvQ2xUQ2lPZis0a3RFMEFDZ3J6UEdEeWVSMmJVRGJUMm92czBjbHJ4MHc3Z1RRU1kreThodE1ZQlVKRVhQVUhHY2NwSGdHb01zWitzajdRbUk0dEJGTnRWS3RyL2tqUXVMc015Zlp1dlY5dHk4YXVPVisvcUV3ZktHZERzNzU2RjEwUU9IQnRvVWpsRHA1Z3hXelVldHY5ODJjZDFIZTN2ZTBRbW90Q1BmaDc1TWcrbEtieXJQanAwSHZRRkRxVGlKR0tVak1wNVA1QVU5Y2R2eXNRWURNbUpxTVJObHRjTzY1YkRzUDh0YVcvaktrRC9uK3o=\&quot;}]&quot;,
            &quot;link&quot;: &quot;https://kebumenkab.bps.go.id/id/publication/2025/09/26/f74b08dff24337a192ab198e/kecamatan-ayah-dalam-angka-2025.html&quot;,
            &quot;abstract&quot;: &quot;Kecamatan Ayah Dalam Angka Tahun 2025 merupakan publikasi tahunan yang diterbitkan oleh BPS Kabupaten Kebumen. Disadari bahwa publikasi ini belum sepenuhnya memenuhi harapan pihak pemakai data khususnya para perencana, namun diharapkan dapat membantu melengkapi penyusunan rencana pembangunan di Kabupaten Kebumen.&quot;,
            &quot;created_at&quot;: &quot;2025-10-30T13:33:09.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-10-30T13:33:09.000000Z&quot;
        },
        {
            &quot;id&quot;: 29,
            &quot;title&quot;: &quot;Kecamatan Karanggayam Dalam Angka 2025&quot;,
            &quot;release_date&quot;: &quot;2025-09-25T17:00:00.000000Z&quot;,
            &quot;category&quot;: null,
            &quot;cover_url&quot;: &quot;https://web-api.bps.go.id/cover.php?f=bP43FiIVH+8JHwIQtqbVklV4OUhHd2lmTmtldk4vMUJvM2l3Ulo3L1FiUVU1NnlXYm04eldNUUpPaXlvblZHaENnUnI0WlBabmx5SFBYV3NTUmtNQm5jNURlTGlOZ3hUWEhPd0lKYlkrVzMvZ1FCclkyZEd6WXdaSlkzYlYxNXh1dXZIUEJ0WndxdHFVU3Ux&quot;,
            &quot;pdf_url&quot;: &quot;https://web-api.bps.go.id/download.php?f=ai/paiXXJxgO4YO6l2H7tEo4dnFCcFEvL2hiUlhGdkJRMzlQUkk1SW9razl2SnRwNmdPMis4dEdnMGUrWHRiYVYyN3RCQm8rc3lpRG9ReXJjMlZScG45Rnh2OXp3dTNzM1pFcGxHTU1uVHZmUFRmb1RYSE43M1k0dXZwYzJFQzJhSTluaTJaeGtybDhqZzByQ0dFekkzLzJZTjdFM0lBakZadlZSTHBmSVFqTWZMalp6dG41Q1g4OWZrTjR4d25wUnY4VHRhaDJ6VHAwbm1lYUdtMWJ2bitxRE5adjYzQzA0RWFpZDJzTnFVTC96aGw4THcvYUtWdzVGNlNJaklqRUhXbzZZTHlDeUVPcHYzZTc=&quot;,
            &quot;downloads&quot;: &quot;[{\&quot;text\&quot;:\&quot;Unduh Publikasi\&quot;,\&quot;url\&quot;:\&quot;https:\\/\\/web-api.bps.go.id\\/download.php?f=ai\\/paiXXJxgO4YO6l2H7tEo4dnFCcFEvL2hiUlhGdkJRMzlQUkk1SW9razl2SnRwNmdPMis4dEdnMGUrWHRiYVYyN3RCQm8rc3lpRG9ReXJjMlZScG45Rnh2OXp3dTNzM1pFcGxHTU1uVHZmUFRmb1RYSE43M1k0dXZwYzJFQzJhSTluaTJaeGtybDhqZzByQ0dFekkzLzJZTjdFM0lBakZadlZSTHBmSVFqTWZMalp6dG41Q1g4OWZrTjR4d25wUnY4VHRhaDJ6VHAwbm1lYUdtMWJ2bitxRE5adjYzQzA0RWFpZDJzTnFVTC96aGw4THcvYUtWdzVGNlNJaklqRUhXbzZZTHlDeUVPcHYzZTc=\&quot;}]&quot;,
            &quot;link&quot;: &quot;https://kebumenkab.bps.go.id/id/publication/2025/09/26/cc84a20eb3c0c90082d8cf02/kecamatan-karanggayam-dalam-angka-2025.html&quot;,
            &quot;abstract&quot;: &quot;Kecamatan Karanggayam Dalam Angka Tahun 2025 merupakan publikasi tahunan yang diterbitkan oleh BPS Kabupaten Kebumen. Disadari bahwa publikasi ini belum sepenuhnya memenuhi harapan pihak pemakai data khususnya para perencana, namun diharapkan dapat membantu melengkapi penyusunan rencana pembangunan di Kabupaten Kebumen.&quot;,
            &quot;created_at&quot;: &quot;2025-10-30T13:51:35.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-10-30T13:51:35.000000Z&quot;
        },
        {
            &quot;id&quot;: 30,
            &quot;title&quot;: &quot;Kecamatan Poncowarno Dalam Angka 2025&quot;,
            &quot;release_date&quot;: &quot;2025-09-25T17:00:00.000000Z&quot;,
            &quot;category&quot;: null,
            &quot;cover_url&quot;: &quot;https://web-api.bps.go.id/cover.php?f=/uTk5g1mKnbEcUeFBF4oAzJXamV3VUFadHYrRkpJMThMZlR6NE4wQ0RnRTU1RDF5R1NEY2pRNGNFTURnb01BUTdTSzU1Ry9QZHBPbUxhRW1EUGM2Um9SODZxZUlib3ZBMmNMczNsR1pIbjIvUE1LZCs5TzluTEk1TXZ3VmZNUTRlb1FOcUU3WENMWmFudzRa&quot;,
            &quot;pdf_url&quot;: &quot;https://web-api.bps.go.id/download.php?f=u5TvA8d4Kw5VoHprK0mjc1lQUFpEOTI5anMwbGd6c3pGb3JDVjhZekI1RnM0aVlnbHBHOGc0SFNjcVprL283bjZseFdpQVRkYWM1QXIwNEpYNzBiSTd1S3FqVXd6Y1FWejhMcXZ1d3o1dFdMRkU4bkljemRQNWFWVlF3S0d2TlFQQlk5U2JTeVZ2ZVJsSEhDeFpzVG1YNnd4SWZONVJ5TjNBWnZVVzZDdXNDRVU1aWtmOC9IMndRYVdIbVpRbnR0UVk2SXRmT21CaG1ycldTZzQrbTdmQnJ6ZHRwL3lxREZGL3BzZkczVGkvbk9hQi91MHUvNUNpTW1DVDBCZzFmdS9XNTk2WTA2U1ZyeTFxTTk=&quot;,
            &quot;downloads&quot;: &quot;[{\&quot;text\&quot;:\&quot;Unduh Publikasi\&quot;,\&quot;url\&quot;:\&quot;https:\\/\\/web-api.bps.go.id\\/download.php?f=u5TvA8d4Kw5VoHprK0mjc1lQUFpEOTI5anMwbGd6c3pGb3JDVjhZekI1RnM0aVlnbHBHOGc0SFNjcVprL283bjZseFdpQVRkYWM1QXIwNEpYNzBiSTd1S3FqVXd6Y1FWejhMcXZ1d3o1dFdMRkU4bkljemRQNWFWVlF3S0d2TlFQQlk5U2JTeVZ2ZVJsSEhDeFpzVG1YNnd4SWZONVJ5TjNBWnZVVzZDdXNDRVU1aWtmOC9IMndRYVdIbVpRbnR0UVk2SXRmT21CaG1ycldTZzQrbTdmQnJ6ZHRwL3lxREZGL3BzZkczVGkvbk9hQi91MHUvNUNpTW1DVDBCZzFmdS9XNTk2WTA2U1ZyeTFxTTk=\&quot;}]&quot;,
            &quot;link&quot;: &quot;https://kebumenkab.bps.go.id/id/publication/2025/09/26/cb7dc7ba4bdaaf062a1e6bf7/kecamatan-poncowarno-dalam-angka-2025.html&quot;,
            &quot;abstract&quot;: &quot;Kecamatan Poncowarno Dalam Angka Tahun 2025 merupakan publikasi tahunan yang diterbitkan oleh BPS Kabupaten Kebumen. Disadari bahwa publikasi ini belum sepenuhnya memenuhi harapan pihak pemakai data khususnya para perencana, namun diharapkan dapat membantu melengkapi penyusunan rencana pembangunan di Kabupaten Kebumen.&quot;,
            &quot;created_at&quot;: &quot;2025-10-30T13:51:35.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-10-30T13:51:35.000000Z&quot;
        },
        {
            &quot;id&quot;: 31,
            &quot;title&quot;: &quot;Kecamatan Sadang Dalam Angka 2025&quot;,
            &quot;release_date&quot;: &quot;2025-09-25T17:00:00.000000Z&quot;,
            &quot;category&quot;: null,
            &quot;cover_url&quot;: &quot;https://web-api.bps.go.id/cover.php?f=tQVGRKG5e+s0+mWZ+31yN1ZpTnU0UUcvTHE4ZTQvS05NYWhWS3NHSDBrZ2VOazFFSGFPaytuMG90UGd2K1JQUktiTHlyQkZYSW95aWtSZ2JTVnhjbGdGeDJ1RkN1OERnYnpuaVpnVHpnUzdJUzZUbUoxenBZTU5oWG9IdDc5bGtCYnlIejQ5TW84VmZRTmVP&quot;,
            &quot;pdf_url&quot;: &quot;https://web-api.bps.go.id/download.php?f=KIhs/fmvVESapcyAUx3DQ09XVm9xdjlMSTV0anM2TXc5UTZmVnJ3WlJvT2h5OXh5aHpuR2MyYmNxN0tEMUZ5NDdqWGNJbGdwTHNtT2ZwWko5OVU0SVZPWGlicC9wNGJPbktQYmpSL08zTTQ4aTAyYjR0V2RJWktucHNXWGpIZ2I3QXhSSXJQMXcrcFdVYmNGUE44d3VlVHpZYU1TZEhTTUFaSWRsY3plbnlUenJNYVg5SSt3UmFtcmNLV3FjMytPcG82Mm1mTW5lVGplTE41SVZjOEdRTnpncDNiQngyUVlaZmpQdDNTbmZIWGhMWjhONy80SHNVNThDdDJPR0hoVE9NdFlBK1BMNms4M2F6eUU=&quot;,
            &quot;downloads&quot;: &quot;[{\&quot;text\&quot;:\&quot;Unduh Publikasi\&quot;,\&quot;url\&quot;:\&quot;https:\\/\\/web-api.bps.go.id\\/download.php?f=KIhs\\/fmvVESapcyAUx3DQ09XVm9xdjlMSTV0anM2TXc5UTZmVnJ3WlJvT2h5OXh5aHpuR2MyYmNxN0tEMUZ5NDdqWGNJbGdwTHNtT2ZwWko5OVU0SVZPWGlicC9wNGJPbktQYmpSL08zTTQ4aTAyYjR0V2RJWktucHNXWGpIZ2I3QXhSSXJQMXcrcFdVYmNGUE44d3VlVHpZYU1TZEhTTUFaSWRsY3plbnlUenJNYVg5SSt3UmFtcmNLV3FjMytPcG82Mm1mTW5lVGplTE41SVZjOEdRTnpncDNiQngyUVlaZmpQdDNTbmZIWGhMWjhONy80SHNVNThDdDJPR0hoVE9NdFlBK1BMNms4M2F6eUU=\&quot;}]&quot;,
            &quot;link&quot;: &quot;https://kebumenkab.bps.go.id/id/publication/2025/09/26/c4d6422ff7f26f1b2727d3c7/kecamatan-sadang-dalam-angka-2025.html&quot;,
            &quot;abstract&quot;: &quot;Kecamatan Sadang Dalam Angka Tahun 2025 merupakan publikasi tahunan yang diterbitkan oleh BPS Kabupaten Kebumen. Disadari bahwa publikasi ini belum sepenuhnya memenuhi harapan pihak pemakai data khususnya para perencana, namun diharapkan dapat membantu melengkapi penyusunan rencana pembangunan di Kabupaten Kebumen.&quot;,
            &quot;created_at&quot;: &quot;2025-10-30T13:51:35.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-10-30T13:51:35.000000Z&quot;
        },
        {
            &quot;id&quot;: 32,
            &quot;title&quot;: &quot;Kecamatan Sruweng Dalam Angka 2025&quot;,
            &quot;release_date&quot;: &quot;2025-09-25T17:00:00.000000Z&quot;,
            &quot;category&quot;: null,
            &quot;cover_url&quot;: &quot;https://web-api.bps.go.id/cover.php?f=0LOqiG0Ss9Fz8Pcn+6zBFDhRZEoxOEtRQk03ZnF4SnhxKy9ERkNsY09rM3A0ZHJoalplVkswYm1xZUJ2Q2ZpK0V2YUtBclQybHFCUXVQZ2NMeGxITzJ3THlFTlhPTUF0ejZxcndZSXFGOHZCdlJmZkZ2SUlZa3lNSEFrQmpLSUhpKzBwM29RbjJ3b2RRd1RG&quot;,
            &quot;pdf_url&quot;: &quot;https://web-api.bps.go.id/download.php?f=GjfswSPPB87zBJ/QVKq4SDV6WGRaTnhEUFoyQzcwTEh0QzVwRmRGODhFTXYzRXV2QW9kMW9QTGFwdHpMcXJ6VzVNOXBXQStXaXkzeUE0Yk05bGduUGYyMXlmMjAzdXdIajBhakRpVEViT1NKSUsrNFY5WGx5VDQyWm5WT1BEeVJ5L25VNjdaUzBrcG5ndnJ2RktjWUtWdHBPdTh6MWJ2d2dhYlBFZzFPL1RNTWlqS0xwbkJXOW1OUlZsUmNZRFhVVm14OXl3WDREYlE3OXl1OCt5b0FmWVc4NXN6c1BvUFBlc3h2bTFJeWZDSStnK0oxUVNRWlJydHJzbkJKdllPRmRpYklvSXBuV3V5enhVRmE=&quot;,
            &quot;downloads&quot;: &quot;[{\&quot;text\&quot;:\&quot;Unduh Publikasi\&quot;,\&quot;url\&quot;:\&quot;https:\\/\\/web-api.bps.go.id\\/download.php?f=GjfswSPPB87zBJ\\/QVKq4SDV6WGRaTnhEUFoyQzcwTEh0QzVwRmRGODhFTXYzRXV2QW9kMW9QTGFwdHpMcXJ6VzVNOXBXQStXaXkzeUE0Yk05bGduUGYyMXlmMjAzdXdIajBhakRpVEViT1NKSUsrNFY5WGx5VDQyWm5WT1BEeVJ5L25VNjdaUzBrcG5ndnJ2RktjWUtWdHBPdTh6MWJ2d2dhYlBFZzFPL1RNTWlqS0xwbkJXOW1OUlZsUmNZRFhVVm14OXl3WDREYlE3OXl1OCt5b0FmWVc4NXN6c1BvUFBlc3h2bTFJeWZDSStnK0oxUVNRWlJydHJzbkJKdllPRmRpYklvSXBuV3V5enhVRmE=\&quot;}]&quot;,
            &quot;link&quot;: &quot;https://kebumenkab.bps.go.id/id/publication/2025/09/26/c16c7e13a739410a2a7a9a97/kecamatan-sruweng-dalam-angka-2025.html&quot;,
            &quot;abstract&quot;: &quot;Kecamatan Sruweng Dalam Angka Tahun 2025 merupakan publikasi tahunan yang diterbitkan oleh BPS Kabupaten Kebumen. Disadari bahwa publikasi ini belum sepenuhnya memenuhi harapan pihak pemakai data khususnya para perencana, namun diharapkan dapat membantu melengkapi penyusunan rencana pembangunan di Kabupaten Kebumen.&quot;,
            &quot;created_at&quot;: &quot;2025-10-30T13:51:35.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-10-30T13:51:35.000000Z&quot;
        },
        {
            &quot;id&quot;: 33,
            &quot;title&quot;: &quot;Kecamatan Adimulyo Dalam Angka 2025&quot;,
            &quot;release_date&quot;: &quot;2025-09-25T17:00:00.000000Z&quot;,
            &quot;category&quot;: null,
            &quot;cover_url&quot;: &quot;https://web-api.bps.go.id/cover.php?f=LQyDzOv3LP5KsiWWDR2eO2NKWkxoL01DZ0M2SDZiMmRJTllHbHpXdEdCOExkTGxkQXZobGY1WEZVWXhQdlcvTGZ5a3JvamRzQ0I4dTFBVWxoQ0lCdEpqcE5uQ01qbUpUOXlqeHB2TWdBOXpkVnRtWnpNSHRQZmYyN2o1MnFrT050czdlUmNXMDFRRVVlUkx0&quot;,
            &quot;pdf_url&quot;: &quot;https://web-api.bps.go.id/download.php?f=lmj41r+n4nm1cQQDZolwOXRybkJmM1JmRlEyUTcvYVpzYi9BaGpvQzNOVkFCZzFjQjQ0Y2o4WWZJdUYzWXhpbTdhSVRkZ2hWVUJzNTVaQmEzVGYrL0k2b3ZzQlZ1c0YrU25pT3NieGNpaVBzbnpJVlVJck1ybVRJRFlQM1E1Q0xPRjd1RGd0a1ovc2pSWVQ3WHNUNllnQkFaakhURUx1Y1Yvb2VjTGIrVFl2UG13UnJvNVhhTWlnRWNSQjI4SHJCN2FrWXVuWXNnOVZRNFdxRVQ2MEt1ZG5JVE1aOEhMbUNpRVlEL1FncUhMaTF0V2l5V0Y0SnRVOE0yKzFQVi93NDJnY3pzeTdJNXZkUzNEVUk=&quot;,
            &quot;downloads&quot;: &quot;[{\&quot;text\&quot;:\&quot;Unduh Publikasi\&quot;,\&quot;url\&quot;:\&quot;https:\\/\\/web-api.bps.go.id\\/download.php?f=lmj41r+n4nm1cQQDZolwOXRybkJmM1JmRlEyUTcvYVpzYi9BaGpvQzNOVkFCZzFjQjQ0Y2o4WWZJdUYzWXhpbTdhSVRkZ2hWVUJzNTVaQmEzVGYrL0k2b3ZzQlZ1c0YrU25pT3NieGNpaVBzbnpJVlVJck1ybVRJRFlQM1E1Q0xPRjd1RGd0a1ovc2pSWVQ3WHNUNllnQkFaakhURUx1Y1Yvb2VjTGIrVFl2UG13UnJvNVhhTWlnRWNSQjI4SHJCN2FrWXVuWXNnOVZRNFdxRVQ2MEt1ZG5JVE1aOEhMbUNpRVlEL1FncUhMaTF0V2l5V0Y0SnRVOE0yKzFQVi93NDJnY3pzeTdJNXZkUzNEVUk=\&quot;}]&quot;,
            &quot;link&quot;: &quot;https://kebumenkab.bps.go.id/id/publication/2025/09/26/bc8674230ee56c22c00220af/kecamatan-adimulyo-dalam-angka-2025.html&quot;,
            &quot;abstract&quot;: &quot;Kecamatan Adimulyo Dalam Angka Tahun 2025 merupakan publikasi tahunan yang diterbitkan oleh BPS Kabupaten Kebumen. Disadari bahwa publikasi ini belum sepenuhnya memenuhi harapan pihak pemakai data khususnya para perencana, namun diharapkan dapat membantu melengkapi penyusunan rencana pembangunan di Kabupaten Kebumen.&quot;,
            &quot;created_at&quot;: &quot;2025-10-30T13:51:35.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-10-30T13:51:35.000000Z&quot;
        },
        {
            &quot;id&quot;: 34,
            &quot;title&quot;: &quot;Kecamatan Karanganyar Dalam Angka 2025&quot;,
            &quot;release_date&quot;: &quot;2025-09-25T17:00:00.000000Z&quot;,
            &quot;category&quot;: null,
            &quot;cover_url&quot;: &quot;https://web-api.bps.go.id/cover.php?f=AcUHpLp4jU46Pt8cHjRCZ0pJaUcvcHV4aDVOcEpxeGxJUjA2dUlWaWlqZUFzV0x3eTF2SnZzbzZWTmpMaHNVb29TdStNcXB5STlqNHlmcFBZYlhybjE4cTZlMHIzTnV5MWtSYnU5cGd5VnRJWFJrYzYxcTA0dm51ei9DSXpXUzB3eVFTNFVjSHUvOVRUK3dJ&quot;,
            &quot;pdf_url&quot;: &quot;https://web-api.bps.go.id/download.php?f=lQ+3AQ+etXwG8sEwFNTbNnZKSTNTdXUvSTY3UHhhUDkwNnVjSkpXcUp6WFhmbkZvMmhpb1FnaUNNZFRpczgweTZzaEt1TTByRTNINk9hQnJwZTF0QmVMZjE5WVIwMDdKd3YwTzRoMHc2YUhwZWhod1d2R0NyMjhUdVVoTUptZTBXSExoZ01IOUJ1a2hFWHUwK0Y1bWsvK3I3enhWQkdQSzRZcEtZbnRLSGZ6d1hwb2paTkYxYy9qUnYrVnNTTnNXeTBUeTVRWVhXdWxqRlFZM2VuT1lwV0RwRjVSdjFXTjdpQTlYb25rRTVZSVRBSXRHTENVUWtGaVI3VjFMSVpnZjZ6R2VGUkZpOUN1elFVR1E=&quot;,
            &quot;downloads&quot;: &quot;[{\&quot;text\&quot;:\&quot;Unduh Publikasi\&quot;,\&quot;url\&quot;:\&quot;https:\\/\\/web-api.bps.go.id\\/download.php?f=lQ+3AQ+etXwG8sEwFNTbNnZKSTNTdXUvSTY3UHhhUDkwNnVjSkpXcUp6WFhmbkZvMmhpb1FnaUNNZFRpczgweTZzaEt1TTByRTNINk9hQnJwZTF0QmVMZjE5WVIwMDdKd3YwTzRoMHc2YUhwZWhod1d2R0NyMjhUdVVoTUptZTBXSExoZ01IOUJ1a2hFWHUwK0Y1bWsvK3I3enhWQkdQSzRZcEtZbnRLSGZ6d1hwb2paTkYxYy9qUnYrVnNTTnNXeTBUeTVRWVhXdWxqRlFZM2VuT1lwV0RwRjVSdjFXTjdpQTlYb25rRTVZSVRBSXRHTENVUWtGaVI3VjFMSVpnZjZ6R2VGUkZpOUN1elFVR1E=\&quot;}]&quot;,
            &quot;link&quot;: &quot;https://kebumenkab.bps.go.id/id/publication/2025/09/26/b1b54a10c2aa264829e61003/kecamatan-karanganyar-dalam-angka-2025.html&quot;,
            &quot;abstract&quot;: &quot;Kecamatan Karanganyar Dalam Angka Tahun 2025 merupakan publikasi tahunan yang diterbitkan oleh BPS Kabupaten Kebumen. Disadari bahwa publikasi ini belum sepenuhnya memenuhi harapan pihak pemakai data khususnya para perencana, namun diharapkan dapat membantu melengkapi penyusunan rencana pembangunan di Kabupaten Kebumen.&quot;,
            &quot;created_at&quot;: &quot;2025-10-30T14:34:26.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-10-30T14:34:26.000000Z&quot;
        },
        {
            &quot;id&quot;: 35,
            &quot;title&quot;: &quot;Kecamatan Mirit Dalam Angka 2025&quot;,
            &quot;release_date&quot;: &quot;2025-09-25T17:00:00.000000Z&quot;,
            &quot;category&quot;: null,
            &quot;cover_url&quot;: &quot;https://web-api.bps.go.id/cover.php?f=JMDmZEmo+A+0ctaFNtDok1p0SmtpY0ZybmlpL3JPTmFTUDNWYitLUlY0UW1xOTdRaDZZUzltdFRmYTQralJET1pESVN3MThWbE01WnhIbGEzb2VydzhweWdEKzloUjhjQ0NBU3lLZzdCUVB0QWxUMTQrODBtRGpsMDQveGNnVi9wNjFrTkJ3NEM4YmZobTkw&quot;,
            &quot;pdf_url&quot;: &quot;https://web-api.bps.go.id/download.php?f=RY+eOXnMhCcjI2/S88+zklVESFFtdis1MWNmL0YvUnF1cXgwa3pJUFl0M0l6TnRBTlFQK3FBc1RzVGtrckVwUHFjM0FHSDBrNUdIVXgvdFpDbzZScSthcHRmR2FsUzUwU3JiYldlR0E2d0dzd0UwOUZGUDFuOFJiczBFOCtSOHIySm50dlRYeXczQkcrNG9RYnZ1bkR1YlEwdjhxS3hObXBNbUhZbDVMV2c5SmY5YUtwcmcvMHRXQmdzam9PSXZLRjFLeGp2bGVqaUp2SzFVcVBkaU4yNEJ0YVlPdzdYNFRoNlZZZk9lcDk1WTYyc01QUjVVYkJVUTcwOFNsamJsaHk1SGt1VTBxaUdJSTdrOXE=&quot;,
            &quot;downloads&quot;: &quot;[{\&quot;text\&quot;:\&quot;Unduh Publikasi\&quot;,\&quot;url\&quot;:\&quot;https:\\/\\/web-api.bps.go.id\\/download.php?f=RY+eOXnMhCcjI2\\/S88+zklVESFFtdis1MWNmL0YvUnF1cXgwa3pJUFl0M0l6TnRBTlFQK3FBc1RzVGtrckVwUHFjM0FHSDBrNUdIVXgvdFpDbzZScSthcHRmR2FsUzUwU3JiYldlR0E2d0dzd0UwOUZGUDFuOFJiczBFOCtSOHIySm50dlRYeXczQkcrNG9RYnZ1bkR1YlEwdjhxS3hObXBNbUhZbDVMV2c5SmY5YUtwcmcvMHRXQmdzam9PSXZLRjFLeGp2bGVqaUp2SzFVcVBkaU4yNEJ0YVlPdzdYNFRoNlZZZk9lcDk1WTYyc01QUjVVYkJVUTcwOFNsamJsaHk1SGt1VTBxaUdJSTdrOXE=\&quot;}]&quot;,
            &quot;link&quot;: &quot;https://kebumenkab.bps.go.id/id/publication/2025/09/26/a4286442ecf93aafbade0402/kecamatan-mirit-dalam-angka-2025.html&quot;,
            &quot;abstract&quot;: &quot;Kecamatan Mirit Dalam Angka Tahun 2025 merupakan publikasi tahunan yang diterbitkan oleh BPS Kabupaten Kebumen. Disadari bahwa publikasi ini belum sepenuhnya memenuhi harapan pihak pemakai data khususnya para perencana, namun diharapkan dapat membantu melengkapi penyusunan rencana pembangunan di Kabupaten Kebumen.&quot;,
            &quot;created_at&quot;: &quot;2025-10-30T14:34:26.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-10-30T14:34:26.000000Z&quot;
        },
        {
            &quot;id&quot;: 36,
            &quot;title&quot;: &quot;Kecamatan Gombong Dalam Angka 2025&quot;,
            &quot;release_date&quot;: &quot;2025-09-25T17:00:00.000000Z&quot;,
            &quot;category&quot;: null,
            &quot;cover_url&quot;: &quot;https://web-api.bps.go.id/cover.php?f=4xZqVlZG1SSr+yeslN0obklYb2NGc2prTlYxT3AvWVV4WTU5UWNJZG9pWjlCU3VTbXFLQm5mNkpQN3RyaE1kZitHMVp3Z01nK3pGekdnc3M4d0pLTHlYUlkybUhkM1RuVk1HOXVsNW9TNW8wS3c1TnlFcElESXUrVmVidERsc2hScytvQklWVWcwa0VzcWV4&quot;,
            &quot;pdf_url&quot;: &quot;https://web-api.bps.go.id/download.php?f=q0VqJETj7XY1J96NJXcaAlZjUEttaEUwSFZJRVd0RDRwVFE3WW5DR05XbU1jTUlhcExjTXRzZnRLL3Z5cVVJZGMvY3NUL0N6MDdnek1yRTJaUld1V3pkdDdhRSs5TUkwR2ZKSFFrempWeE9xSSs4YnJzSnJHaWNQT0F6Rys1NGZBNnA3THZ2aEMzeUhnZEkwT1Fwb3R4MmhzbHArNG5vdGxjWGZWOUxXeWZoSDBEVzZaZUhiTy9MdS9qdkw5a1ZCWVZPUzlaVnhkS0txRFFmWk8vTDdJUld6VUtYNjNqV20wWTBBZDhHMmNQenc0d1RtZ0UwOGdkbE45VXNOZHdyQjVjcFRoblhsM0JTZjd6Q1o=&quot;,
            &quot;downloads&quot;: &quot;[{\&quot;text\&quot;:\&quot;Unduh Publikasi\&quot;,\&quot;url\&quot;:\&quot;https:\\/\\/web-api.bps.go.id\\/download.php?f=q0VqJETj7XY1J96NJXcaAlZjUEttaEUwSFZJRVd0RDRwVFE3WW5DR05XbU1jTUlhcExjTXRzZnRLL3Z5cVVJZGMvY3NUL0N6MDdnek1yRTJaUld1V3pkdDdhRSs5TUkwR2ZKSFFrempWeE9xSSs4YnJzSnJHaWNQT0F6Rys1NGZBNnA3THZ2aEMzeUhnZEkwT1Fwb3R4MmhzbHArNG5vdGxjWGZWOUxXeWZoSDBEVzZaZUhiTy9MdS9qdkw5a1ZCWVZPUzlaVnhkS0txRFFmWk8vTDdJUld6VUtYNjNqV20wWTBBZDhHMmNQenc0d1RtZ0UwOGdkbE45VXNOZHdyQjVjcFRoblhsM0JTZjd6Q1o=\&quot;}]&quot;,
            &quot;link&quot;: &quot;https://kebumenkab.bps.go.id/id/publication/2025/09/26/8e663dcf15de6be7710115d6/kecamatan-gombong-dalam-angka-2025.html&quot;,
            &quot;abstract&quot;: &quot;Kecamatan Gombong Dalam Angka Tahun 2025 merupakan publikasi tahunan yang diterbitkan oleh BPS Kabupaten Kebumen. Disadari bahwa publikasi ini belum sepenuhnya memenuhi harapan pihak pemakai data khususnya para perencana, namun diharapkan dapat membantu melengkapi penyusunan rencana pembangunan di Kabupaten Kebumen.&quot;,
            &quot;created_at&quot;: &quot;2025-10-30T14:34:26.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-10-30T14:34:26.000000Z&quot;
        },
        {
            &quot;id&quot;: 37,
            &quot;title&quot;: &quot;Kecamatan Kebumen Dalam Angka 2025&quot;,
            &quot;release_date&quot;: &quot;2025-09-25T17:00:00.000000Z&quot;,
            &quot;category&quot;: null,
            &quot;cover_url&quot;: &quot;https://web-api.bps.go.id/cover.php?f=5P7bfP5sHlZD9Jji9gwLVEl5aWlKTW1ZTVg3cmFFWCs4dG9OSzNXQ2FGOW1TU0tKMU1EVlByMmFHa0o5elhtTmx1TmRzZVpmZjBmdno4VHg4cjFsVnJMVEJEQUZCQUhUeGZRd3UxRG15bFVIb0FjWkhFd0RZWW5vZTlBWUFHNnYwdkF2Z2tNWW4zeHhLNjFw&quot;,
            &quot;pdf_url&quot;: &quot;https://web-api.bps.go.id/download.php?f=Fm5/D6wE7UwWquAdfzHt9klKS20yTWpKazRCK2VUK1haVnhRckRFUXdHaDdITktlazQ4ekcvZUFTc2kreVJ3TDJBR3EzOFZiWDl6eUZpbFNzQndLNkhXa3JuZTBqbVhkRTFNcjJHZ0g0VEhkNWZ5VUR6WDVCTllZTEZjdzJqa2Z1ZU5vNUxOZktiaTdCV2pTSzNjMW9ScnhVRmtmd1pDNVF1cnZBV283UHFoYkwyZ25NNmpkV24vRFRWWlZXWWQ1NmJOSGxGa09UcmYxYzJseUp4anFuRDl3MGpPSVZMY2dKa2lsakN5R0g4aFNrMG9FVThpM2M0aVdrN3FrSCt4cktOUzE4eFU3ZE5aVEJndGE=&quot;,
            &quot;downloads&quot;: &quot;[{\&quot;text\&quot;:\&quot;Unduh Publikasi\&quot;,\&quot;url\&quot;:\&quot;https:\\/\\/web-api.bps.go.id\\/download.php?f=Fm5\\/D6wE7UwWquAdfzHt9klKS20yTWpKazRCK2VUK1haVnhRckRFUXdHaDdITktlazQ4ekcvZUFTc2kreVJ3TDJBR3EzOFZiWDl6eUZpbFNzQndLNkhXa3JuZTBqbVhkRTFNcjJHZ0g0VEhkNWZ5VUR6WDVCTllZTEZjdzJqa2Z1ZU5vNUxOZktiaTdCV2pTSzNjMW9ScnhVRmtmd1pDNVF1cnZBV283UHFoYkwyZ25NNmpkV24vRFRWWlZXWWQ1NmJOSGxGa09UcmYxYzJseUp4anFuRDl3MGpPSVZMY2dKa2lsakN5R0g4aFNrMG9FVThpM2M0aVdrN3FrSCt4cktOUzE4eFU3ZE5aVEJndGE=\&quot;}]&quot;,
            &quot;link&quot;: &quot;https://kebumenkab.bps.go.id/id/publication/2025/09/26/8e3ba82af0ac764ec9f5824a/kecamatan-kebumen-dalam-angka-2025.html&quot;,
            &quot;abstract&quot;: &quot;Kecamatan Kebumen Dalam Angka Tahun 2025 merupakan publikasi tahunan yang diterbitkan oleh BPS Kabupaten Kebumen. Disadari bahwa publikasi ini belum sepenuhnya memenuhi harapan pihak pemakai data khususnya para perencana, namun diharapkan dapat membantu melengkapi penyusunan rencana pembangunan di Kabupaten Kebumen.&quot;,
            &quot;created_at&quot;: &quot;2025-10-30T14:34:26.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-10-30T14:34:26.000000Z&quot;
        }
    ],
    &quot;pagination&quot;: {
        &quot;current_page&quot;: 1,
        &quot;last_page&quot;: 3,
        &quot;per_page&quot;: 15,
        &quot;total&quot;: 31
    }
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-content-publications" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-content-publications"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-content-publications"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-content-publications" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-content-publications">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-content-publications" data-method="GET"
      data-path="api/content/publications"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-content-publications', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-content-publications"
                    onclick="tryItOut('GETapi-content-publications');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-content-publications"
                    onclick="cancelTryOut('GETapi-content-publications');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-content-publications"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/content/publications</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-content-publications"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-content-publications"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-GETapi-homepage-insights-indicators">Ambil nilai terbaru dari multiple datasets untuk insight
Contoh: /api/insights/indicators</h2>

<p>
</p>



<span id="example-requests-GETapi-homepage-insights-indicators">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/homepage/indicators" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/homepage/indicators"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-homepage-insights-indicators">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: &quot;success&quot;,
    &quot;data&quot;: {
        &quot;angkatan_kerja&quot;: {
            &quot;dataset_id&quot;: 21,
            &quot;dataset_name&quot;: &quot;Penduduk Berumur 15 Tahun Ke Atas yang Termasuk Angkatan Kerja Menurut Pendidikan Tertinggi yang Ditamatkan dan Kegiatan Selama Seminggu yang Lalu di Kabupaten Kebumen&quot;,
            &quot;value&quot;: 94.89,
            &quot;year&quot;: 2023,
            &quot;unit&quot;: &quot;Persen&quot;
        },
        &quot;bencana_alam&quot;: {
            &quot;dataset_id&quot;: 22,
            &quot;dataset_name&quot;: &quot;Jumlah Kejadian Bencana Alam Menurut Kecamatan di Kabupaten Kebumen&quot;,
            &quot;value&quot;: 89,
            &quot;year&quot;: 2023,
            &quot;unit&quot;: &quot;Kejadian&quot;
        },
        &quot;dusun_rw_rt&quot;: {
            &quot;dataset_id&quot;: 23,
            &quot;dataset_name&quot;: &quot;Jumlah Dusun, Rukun Warga (RW), dan Rukun Tetangga (RT)  Menurut Kecamatan di Kabupaten Kebumen&quot;,
            &quot;value&quot;: 7288,
            &quot;year&quot;: 2023,
            &quot;unit&quot;: &quot;Unit&quot;
        },
        &quot;beban_ketergantungan&quot;: {
            &quot;dataset_id&quot;: 24,
            &quot;dataset_name&quot;: &quot;Angka Beban Ketergantungan di Kabupaten Kebumen&quot;,
            &quot;value&quot;: 46.38,
            &quot;year&quot;: 2022,
            &quot;unit&quot;: &quot;Persen&quot;
        }
    },
    &quot;timestamp&quot;: &quot;2025-12-09T03:13:05.495980Z&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-homepage-insights-indicators" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-homepage-insights-indicators"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-homepage-insights-indicators"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-homepage-insights-indicators" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-homepage-insights-indicators">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-homepage-indicators" data-method="GET"
      data-path="api/homepage/indicators"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-homepage-insights-indicators', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-homepage-insights-indicators"
                    onclick="tryItOut('GETapi-homepage-insights-indicators');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-homepage-insights-indicators"
                    onclick="cancelTryOut('GETapi-homepage-insights-indicators');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-homepage-insights-indicators"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/homepage/indicators</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-homepage-insights-indicators"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-homepage-insights-indicators"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-GETapi-homepage-grid">Get grid menu of statistics categories with dataset counts</h2>

<p>
</p>

<p>Returns a grid layout (typically 12 items for mobile UI) representing
major statistics categories. Each grid item includes:</p>
<ul>
<li>Title: Display name of the category</li>
<li>Slug: URL-friendly identifier</li>
<li>Dataset count: Number of datasets in that category</li>
</ul>
<p>The grid includes categories like Penduduk, Tenaga Kerja, Pengangguran,
Kemiskinan, IPM, Inflasi, Ekonomi, PDRB, Pendidikan, Perumahan, Pertanian,
and Lainnya (Others).</p>

<span id="example-requests-GETapi-homepage-grid">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/homepage/grid" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/homepage/grid"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-homepage-grid">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: &quot;success&quot;,
    &quot;data&quot;: [
        {
            &quot;title&quot;: &quot;Penduduk&quot;,
            &quot;slug&quot;: &quot;kependudukan&quot;,
            &quot;dataset_count&quot;: 12
        },
        {
            &quot;title&quot;: &quot;Tenaga Kerja&quot;,
            &quot;slug&quot;: &quot;tenaga-kerja&quot;,
            &quot;dataset_count&quot;: 8
        }
    ]
}</code>
 </pre>
            <blockquote>
            <p>Example response (500):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: &quot;error&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-homepage-grid" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-homepage-grid"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-homepage-grid"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-homepage-grid" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-homepage-grid">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-homepage-grid" data-method="GET"
      data-path="api/homepage/grid"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-homepage-grid', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-homepage-grid"
                    onclick="tryItOut('GETapi-homepage-grid');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-homepage-grid"
                    onclick="cancelTryOut('GETapi-homepage-grid');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-homepage-grid"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/homepage/grid</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-homepage-grid"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-homepage-grid"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-GETapi-homepage-grid--slug-">Get detailed list of datasets for a specific grid category</h2>

<p>
</p>

<p>Returns all datasets belonging to a specific category (identified by slug).
Datasets are matched using keywords and subject names configured in GRID_SLOTS.</p>
<p>Available slugs: kependudukan, tenaga-kerja, pengangguran, kemiskinan, rasio-gini,
ipm, inflasi, ekonomi, pdrb, pendidikan, perumahan, pertanian, others</p>

<span id="example-requests-GETapi-homepage-grid--slug-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/homepage/grid/kependudukan?fields=id%2Cdataset_name%2Csubject" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/homepage/grid/kependudukan"
);

const params = {
    "fields": "id,dataset_name,subject",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-homepage-grid--slug-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: &quot;success&quot;,
    &quot;category&quot;: &quot;Penduduk&quot;,
    &quot;datasets&quot;: [
        {
            &quot;id&quot;: 20,
            &quot;dataset_code&quot;: &quot;SP010101&quot;,
            &quot;dataset_name&quot;: &quot;Penduduk menurut kelompok umur dan jenis kelamin&quot;,
            &quot;last_update&quot;: &quot;2023-12-01&quot;,
            &quot;subject&quot;: &quot;Penduduk&quot;
        }
    ]
}</code>
 </pre>
            <blockquote>
            <p>Example response (404):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: &quot;error&quot;,
    &quot;message&quot;: &quot;Grid slot tidak ditemukan&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (500):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: &quot;error&quot;,
    &quot;message&quot;: &quot;Terjadi kesalahan pada server.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-homepage-grid--slug-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-homepage-grid--slug-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-homepage-grid--slug-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-homepage-grid--slug-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-homepage-grid--slug-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-homepage-grid--slug-" data-method="GET"
      data-path="api/homepage/grid/{slug}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-homepage-grid--slug-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-homepage-grid--slug-"
                    onclick="tryItOut('GETapi-homepage-grid--slug-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-homepage-grid--slug-"
                    onclick="cancelTryOut('GETapi-homepage-grid--slug-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-homepage-grid--slug-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/homepage/grid/{slug}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-homepage-grid--slug-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-homepage-grid--slug-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>slug</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="slug"                data-endpoint="GETapi-homepage-grid--slug-"
               value="kependudukan"
               data-component="url">
    <br>
<p>The category slug. Example: <code>kependudukan</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>fields</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="fields"                data-endpoint="GETapi-homepage-grid--slug-"
               value="id,dataset_name,subject"
               data-component="query">
    <br>
<p>optional Comma-separated field names to return. Allowed: id, dataset_code, dataset_name, updated_at, subject, category. Default: id,dataset_code,dataset_name,updated_at. Example: <code>id,dataset_name,subject</code></p>
            </div>
                </form>

                    <h2 id="endpoints-GETapi-datasets-categories">Get all categories with their subjects for navigation</h2>

<p>
</p>

<p>Returns a hierarchical structure of all available BPS data categories
and their corresponding subjects. This is used in Layer 2 of the UI
to display category and subject selection options.</p>
<p>The response is organized as an object where keys are category names
and values contain the list of subjects within that category.</p>

<span id="example-requests-GETapi-datasets-categories">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/datasets/categories" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/datasets/categories"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-datasets-categories">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;Statistik Demografi&quot;: {
        &quot;category&quot;: &quot;Statistik Demografi&quot;,
        &quot;subjects&quot;: [
            &quot;Penduduk&quot;,
            &quot;Migrasi&quot;,
            &quot;Kelahiran&quot;
        ]
    },
    &quot;Statistik Ekonomi&quot;: {
        &quot;category&quot;: &quot;Statistik Ekonomi&quot;,
        &quot;subjects&quot;: [
            &quot;PDRB&quot;,
            &quot;Inflasi&quot;,
            &quot;Perdagangan&quot;
        ]
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (500):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: &quot;Terjadi kesalahan pada server.&quot;,
    &quot;message&quot;: &quot;...&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-datasets-categories" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-datasets-categories"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-datasets-categories"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-datasets-categories" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-datasets-categories">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-datasets-categories" data-method="GET"
      data-path="api/datasets/categories"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-datasets-categories', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-datasets-categories"
                    onclick="tryItOut('GETapi-datasets-categories');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-datasets-categories"
                    onclick="cancelTryOut('GETapi-datasets-categories');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-datasets-categories"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/datasets/categories</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-datasets-categories"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-datasets-categories"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-GETapi-datasets">Get list of datasets filtered by subject or search query</h2>

<p>
</p>

<p>Returns a paginated list of datasets with optional filtering by subject
or full-text search on dataset name. This endpoint is typically used for
the dataset list view in Layer 3 of the UI navigation hierarchy.</p>

<span id="example-requests-GETapi-datasets">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/datasets?subject=Penduduk&amp;q=kelompok+umur&amp;fields=id%2Cdataset_name" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/datasets"
);

const params = {
    "subject": "Penduduk",
    "q": "kelompok umur",
    "fields": "id,dataset_name",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-datasets">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: &quot;success&quot;,
    &quot;count&quot;: 15,
    &quot;data&quot;: [
        {
            &quot;id&quot;: 20,
            &quot;dataset_name&quot;: &quot;Penduduk menurut kelompok umur dan jenis kelamin&quot;,
            &quot;subject&quot;: &quot;Penduduk&quot;,
            &quot;category&quot;: &quot;Statistik Demografi&quot;
        },
        {
            &quot;id&quot;: 21,
            &quot;dataset_name&quot;: &quot;Penduduk menurut kabupaten/kota dan jenis kelamin&quot;,
            &quot;subject&quot;: &quot;Penduduk&quot;,
            &quot;category&quot;: &quot;Statistik Demografi&quot;
        }
    ]
}</code>
 </pre>
            <blockquote>
            <p>Example response (500):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: &quot;error&quot;,
    &quot;message&quot;: &quot;Terjadi kesalahan pada server.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-datasets" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-datasets"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-datasets"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-datasets" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-datasets">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-datasets" data-method="GET"
      data-path="api/datasets"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-datasets', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-datasets"
                    onclick="tryItOut('GETapi-datasets');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-datasets"
                    onclick="cancelTryOut('GETapi-datasets');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-datasets"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/datasets</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-datasets"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-datasets"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>subject</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="subject"                data-endpoint="GETapi-datasets"
               value="Penduduk"
               data-component="query">
    <br>
<p>optional Filter by subject name. Example: <code>Penduduk</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>q</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="q"                data-endpoint="GETapi-datasets"
               value="kelompok umur"
               data-component="query">
    <br>
<p>optional Search by dataset name (partial match). Example: <code>kelompok umur</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>fields</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="fields"                data-endpoint="GETapi-datasets"
               value="id,dataset_name"
               data-component="query">
    <br>
<p>optional Comma-separated field names to return. Allowed: id, dataset_code, dataset_name, subject, category, updated_at. Default: id,dataset_name,subject,category. Example: <code>id,dataset_name</code></p>
            </div>
                </form>

                    <h2 id="endpoints-GETapi-datasets--dataset_id-">Get detailed dataset information with table, chart, and insight data</h2>

<p>
</p>

<p>This endpoint returns comprehensive data for a specific BPS dataset including:</p>
<ul>
<li>Table data with rows and columns formatted for display</li>
<li>Chart data (formatted per handler type - bar, line, etc.)</li>
<li>Insight/summary information</li>
<li>List of available years for the dataset</li>
</ul>
<p>The data format depends on the dataset type detected from its name:</p>
<ul>
<li>Population by Age &amp; Gender</li>
<li>Population by Gender &amp; Region</li>
<li>Population by Age &amp; Region</li>
<li>Gender-based statistics</li>
<li>Category-based statistics</li>
<li>Time series (default)</li>
</ul>

<span id="example-requests-GETapi-datasets--dataset_id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/datasets/5?year=2022&amp;mode=region" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/datasets/5"
);

const params = {
    "year": "2022",
    "mode": "region",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-datasets--dataset_id-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;dataset&quot;: {
        &quot;id&quot;: 20,
        &quot;dataset_code&quot;: &quot;SP010101&quot;,
        &quot;dataset_name&quot;: &quot;Penduduk menurut kelompok umur dan jenis kelamin&quot;,
        &quot;subject&quot;: &quot;Penduduk&quot;,
        &quot;category&quot;: &quot;Statistik Demografi&quot;,
        &quot;unit&quot;: &quot;Jiwa&quot;
    },
    &quot;available_years&quot;: [
        2023,
        2022,
        2021,
        2020
    ],
    &quot;current_year&quot;: 2023,
    &quot;table&quot;: {
        &quot;headers&quot;: [
            &quot;Kelompok Umur&quot;,
            &quot;Laki-Laki&quot;,
            &quot;Perempuan&quot;,
            &quot;Total&quot;
        ],
        &quot;rows&quot;: [
            [
                &quot;0-4 tahun&quot;,
                &quot;5000000&quot;,
                &quot;4800000&quot;,
                &quot;9800000&quot;
            ]
        ]
    },
    &quot;chart&quot;: {
        &quot;type&quot;: &quot;bar&quot;,
        &quot;title&quot;: &quot;Penduduk menurut Kelompok Umur&quot;,
        &quot;data&quot;: {}
    },
    &quot;insights&quot;: [
        &quot;Populasi total di tahun 2023 adalah 275 juta jiwa&quot;
    ]
}</code>
 </pre>
            <blockquote>
            <p>Example response (404):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: &quot;Dataset not found&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-datasets--dataset_id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-datasets--dataset_id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-datasets--dataset_id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-datasets--dataset_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-datasets--dataset_id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-datasets--dataset_id-" data-method="GET"
      data-path="api/datasets/{dataset_id}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-datasets--dataset_id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-datasets--dataset_id-"
                    onclick="tryItOut('GETapi-datasets--dataset_id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-datasets--dataset_id-"
                    onclick="cancelTryOut('GETapi-datasets--dataset_id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-datasets--dataset_id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/datasets/{dataset_id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-datasets--dataset_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-datasets--dataset_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>dataset_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="dataset_id"                data-endpoint="GETapi-datasets--dataset_id-"
               value="5"
               data-component="url">
    <br>
<p>The ID of the dataset. Example: <code>5</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>dataset</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="dataset"                data-endpoint="GETapi-datasets--dataset_id-"
               value="20"
               data-component="url">
    <br>
<p>The dataset ID. Example: <code>20</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>year</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="year"                data-endpoint="GETapi-datasets--dataset_id-"
               value="2022"
               data-component="query">
    <br>
<p>optional The year to retrieve data for. If not provided, uses latest available year. Example: <code>2022</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>mode</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="mode"                data-endpoint="GETapi-datasets--dataset_id-"
               value="region"
               data-component="query">
    <br>
<p>optional Display mode (used by some handlers). Example: <code>region</code></p>
            </div>
                </form>

                    <h2 id="endpoints-GETapi-datasets--dataset_id--history">Get historical data for a specific dataset</h2>

<p>
</p>

<p>Retrieves historical trends and time-series data for a dataset.
The data structure depends on the handler's implementation of getHistoryData().</p>

<span id="example-requests-GETapi-datasets--dataset_id--history">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/datasets/5/history?year=2020" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/datasets/5/history"
);

const params = {
    "year": "2020",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-datasets--dataset_id--history">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;dataset&quot;: {
        &quot;id&quot;: 20,
        &quot;dataset_code&quot;: &quot;SP010101&quot;,
        &quot;dataset_name&quot;: &quot;Penduduk menurut kelompok umur dan jenis kelamin&quot;
    },
    &quot;history&quot;: [
        {
            &quot;year&quot;: 2023,
            &quot;value&quot;: 275000000
        },
        {
            &quot;year&quot;: 2022,
            &quot;value&quot;: 273000000
        }
    ]
}</code>
 </pre>
            <blockquote>
            <p>Example response (404):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: &quot;Dataset not found&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-datasets--dataset_id--history" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-datasets--dataset_id--history"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-datasets--dataset_id--history"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-datasets--dataset_id--history" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-datasets--dataset_id--history">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-datasets--dataset_id--history" data-method="GET"
      data-path="api/datasets/{dataset_id}/history"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-datasets--dataset_id--history', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-datasets--dataset_id--history"
                    onclick="tryItOut('GETapi-datasets--dataset_id--history');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-datasets--dataset_id--history"
                    onclick="cancelTryOut('GETapi-datasets--dataset_id--history');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-datasets--dataset_id--history"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/datasets/{dataset_id}/history</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-datasets--dataset_id--history"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-datasets--dataset_id--history"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>dataset_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="dataset_id"                data-endpoint="GETapi-datasets--dataset_id--history"
               value="5"
               data-component="url">
    <br>
<p>The ID of the dataset. Example: <code>5</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>dataset</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="dataset"                data-endpoint="GETapi-datasets--dataset_id--history"
               value="20"
               data-component="url">
    <br>
<p>The dataset ID. Example: <code>20</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>year</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="year"                data-endpoint="GETapi-datasets--dataset_id--history"
               value="2020"
               data-component="query">
    <br>
<p>optional Filter history by starting year. Example: <code>2020</code></p>
            </div>
                </form>

                    <h2 id="endpoints-GETapi-datasets--dataset_id--insights">Get insight and summary information for a specific dataset</h2>

<p>
</p>

<p>Provides key findings, statistics, and analysis for a dataset.
Insights may include percentage calculations, comparative data, and significant findings.</p>

<span id="example-requests-GETapi-datasets--dataset_id--insights">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/datasets/5/insights?year=2023" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/datasets/5/insights"
);

const params = {
    "year": "2023",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-datasets--dataset_id--insights">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;dataset&quot;: {
        &quot;id&quot;: 20,
        &quot;dataset_code&quot;: &quot;SP010101&quot;,
        &quot;dataset_name&quot;: &quot;Penduduk menurut kelompok umur dan jenis kelamin&quot;
    },
    &quot;insights&quot;: [
        &quot;Populasi total tahun 2023 adalah 275 juta jiwa&quot;,
        &quot;Pertumbuhan populasi 0.7% dibanding tahun lalu&quot;,
        &quot;Rasio gender 50:50 menunjukkan keseimbangan sempurna&quot;
    ]
}</code>
 </pre>
            <blockquote>
            <p>Example response (404):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: &quot;Dataset not found&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-datasets--dataset_id--insights" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-datasets--dataset_id--insights"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-datasets--dataset_id--insights"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-datasets--dataset_id--insights" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-datasets--dataset_id--insights">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-datasets--dataset_id--insights" data-method="GET"
      data-path="api/datasets/{dataset_id}/insights"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-datasets--dataset_id--insights', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-datasets--dataset_id--insights"
                    onclick="tryItOut('GETapi-datasets--dataset_id--insights');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-datasets--dataset_id--insights"
                    onclick="cancelTryOut('GETapi-datasets--dataset_id--insights');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-datasets--dataset_id--insights"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/datasets/{dataset_id}/insights</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-datasets--dataset_id--insights"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-datasets--dataset_id--insights"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>dataset_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="dataset_id"                data-endpoint="GETapi-datasets--dataset_id--insights"
               value="5"
               data-component="url">
    <br>
<p>The ID of the dataset. Example: <code>5</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>dataset</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="dataset"                data-endpoint="GETapi-datasets--dataset_id--insights"
               value="20"
               data-component="url">
    <br>
<p>The dataset ID. Example: <code>20</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>year</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="year"                data-endpoint="GETapi-datasets--dataset_id--insights"
               value="2023"
               data-component="query">
    <br>
<p>optional The year to generate insights for. Example: <code>2023</code></p>
            </div>
                </form>

            

        
    </div>
    <div class="dark-box">
                    <div class="lang-selector">
                                                        <button type="button" class="lang-button" data-language-name="bash">bash</button>
                                                        <button type="button" class="lang-button" data-language-name="javascript">javascript</button>
                            </div>
            </div>
</div>
</body>
</html>
