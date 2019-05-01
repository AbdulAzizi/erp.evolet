<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
{{-- 
    <!-- Scripts -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet"> --}}
</head>
<body>
    
    <div id="app">
        <v-app>
            {{-- <notification></notification> --}}

            <main class="py-4">
                @yield('layout')
            </main>
        </v-app>
    </div>
    
    <script>
		window.Laravel = {!! json_encode([
        	'csrf_token' => csrf_token(),
        	'asset_path' => asset('')
    	]); !!}
	</script>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
</body>
</html>
