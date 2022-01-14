<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- HTML Meta Tags -->
    <title>Pointrush @yield('title')</title>
    <meta name="description" content="@yield('caption')">

    <!-- Facebook Meta Tags -->
    <meta property="og:url" content="@yield('url')">
    <meta property="og:type" content="website">
    <meta property="og:title" content="Pointrush @yield('title')">
    <meta property=" og:description" content="@yield('caption')">
    <meta property="og:image" content="https://www.pointrush.nl/images/pointrush.png">

    <!-- Twitter Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta property="twitter:domain" content="pointrush.nl">
    <meta property="twitter:url" content="@yield('url')">
    <meta name="twitter:title" content="Pointrush @yield('title')">
    <meta name=" twitter:description" content="@yield('caption')">
    <meta name="twitter:image" content="https://www.pointrush.nl/images/pointrush.png">

    <!-- Meta Tags Generated via https://www.opengraph.xyz -->

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

</head>

<body>

    @yield('content')

</body>

</html>
