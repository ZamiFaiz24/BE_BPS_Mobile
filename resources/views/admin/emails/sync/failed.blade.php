<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sync Failed Notification</title>
    <style>
        /* Add your custom styles here */
        body {
            font-family: Arial, sans-serif;
            background-color: #f9fafb;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header img {
            max-width: 100px;
        }
        .content {
            margin-bottom: 20px;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
        }
        .button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Notifikasi Sinkronisasi Gagal</h1>
        </div>
        <div class="content">
            <p>Halo,</p>
            <p>Sinkronisasi data Anda telah gagal. Silakan periksa pengaturan dan coba lagi.</p>
            <center>
                <a href="{{ route('admin.settings.index') }}" class="button">Buka Halaman Pengaturan</a>
            </center>
        </div>
        <div class="footer">
            <p>Terima kasih,</p>
            <p>Tim Kami</p>
        </div>
    </div>
</body>
</html>