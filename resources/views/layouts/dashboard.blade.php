<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>
    @include('partials.styles-global')
</head>
<body>
    <div class="container-scroller">
        {{-- NAVBAR --}}
        @include('partials.navbar')

        <div class="container-fluid page-body-wrapper">
            {{-- SIDEBAR --}}
            @include('partials.sidebar')

            <div class="main-panel">
                <div class="content-wrapper">
                    {{-- CONTENT --}}
                    @yield('content')
                </div>
                {{-- FOOTER --}}
                @include('partials.footer')
            </div>
        </div>
    </div>

    @include('partials.scripts-global')
</body>
</html>