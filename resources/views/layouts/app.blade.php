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
    <link href="{{ asset('vendor/wcolpick-2.5.1/wcolpick/wcolpick.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">

</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/admin') }}" style="margin-right: auto;">
                    Pointrush
                </a>

                <div class="bedge-widget d-flex justify-content-end bedge-wdget-container" style="margin-top: 5px;">
                   </div>

                <button class="navbar-toggler" type="button" data-toggle="collapse"
                    data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">

                         {{-- <div class="bedge-widget d-none d-md-block" style="margin-top: 5px">

                            </div> --}}

                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="/admin/changepassword">
                                        Change Password
                                    </a>
                                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                             document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                        style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

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
