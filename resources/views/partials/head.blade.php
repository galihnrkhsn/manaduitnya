<meta charset="utf-8">
{{-- viewport-fit=cover: wajib agar env(safe-area-inset-bottom) bekerja di iPhone --}}
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="theme-color" content="#FFFFFF">

<title>{{ config('app.name', 'ManaDuitnya') }}</title>

{{-- PWA --}}
<link rel="manifest" href="/manifest.webmanifest">
<link rel="apple-touch-icon" href="/icons/icon-192.png">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-title" content="ManaDuitnya">
<meta name="apple-mobile-web-app-status-bar-style" content="default">

{{-- Font --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

@vite(['resources/css/app.css', 'resources/js/app.js'])
