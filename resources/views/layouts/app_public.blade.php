<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- <title>Pointrush</title> --}}

    <!-- HTML Meta Tags -->
    <title>Pointrush | @yield('title')</title>
    <meta name="description" content="@yield('caption')">

    <!-- Facebook Meta Tags -->
    <meta property="og:url" content="@yield('url')">
    <meta property="og:type" content="website">
    <meta property="og:title" content="Pointrush | @yield('title')"></title>
<meta property="og:description" content="@yield('caption')">
    <meta property="og:image" content="https://www.pointrush.nl/images/pointrush.png">

    <!-- Twitter Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta property="twitter:domain" content="pointrush.nl">
    <meta property="twitter:url" content="@yield('url')">
    <meta name="twitter:title" content="Pointrush | @yield('title')"></title>
<meta name="twitter:description" content="@yield('caption')">
    <meta name="twitter:image" content="https://www.pointrush.nl/images/pointrush.png">

    <!-- Meta Tags Generated via https://www.opengraph.xyz -->


    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">

</head>

<body>
    <div id="app">

        <main class="py-4">
            @yield('content')
        </main>
    </div>

    <!-- Scripts -->
    <script>
        @php
        $timezone = config('app.timezone');
        $time = new \DateTime('now', new DateTimeZone($timezone));
        $timezoneOffset = $time->format('O');
        echo 'window.z = "' . $timezoneOffset . '";' . PHP_EOL;
        @endphp
        var textClaimSetting = '{{ __('basic.track_settings') }}';
        var textRemove = '{{ __('basic.remove') }}';
        var textNone = '{{ __('basic.none') }}';
        window.translations = {!! Cache::get('translations') !!};

        function trans(key, replace = {}) {
            let translation = key.split('.').reduce((t, i) => t[i] || null, window.translations);

            for (var placeholder in replace) {
                translation = translation.replace(`:${placeholder}`, replace[placeholder]);
            }

            return translation;
        }

    </script>
    @if (!in_array(Route::currentRouteName(), ['showlogs', 'showlogssingle']))

        <script src="{{ asset('js/admin.js') }}" defer></script>
    @endif
    @yield('scripts')

    <script>
        // @see https://docs.headwayapp.co/widget for more configuration options.
        var HW_config = {
          selector: ".bedge-widget", // CSS selector where to inject the badge
          account:  "7klkPJ"
        }
      </script>
      <script async src="https://cdn.headwayapp.co/widget.js"></script>

</body>

</html>
