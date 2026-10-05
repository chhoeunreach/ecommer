<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>404 Not Found</title>
    <style>
        html, body {
            height: 100%;
            margin: 0;
        }
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fff;
            color: #444;
            font-family: Arial, Helvetica, sans-serif;
            text-align: center;
            padding: 0 16px;
        }
        .code {
            font-size: clamp(120px, 22vw, 240px);
            font-weight: 700;
            line-height: 1;
            margin: 0;
        }
        .title {
            font-size: clamp(32px, 5vw, 56px);
            font-weight: 700;
            margin: 40px 0 24px;
        }
        .message {
            font-size: clamp(16px, 2.2vw, 26px);
            margin: 0;
        }
    </style>
</head>
<body>
    <main>
        <h1 class="code">404</h1>
        <h2 class="title">Not Found</h2>
        <p class="message">The resource requested could not be found on this server!</p>
    </main>
</body>
</html>
