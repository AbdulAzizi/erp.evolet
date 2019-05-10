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
        {{-- <notification></notification> --}}

        <v-app class="backgroundColor">
            @yield('layout')
        </v-app>
            
    </div>
    
    <script>
		window.Laravel = {!! json_encode([
        	'csrf_token' => csrf_token(),
            'asset_path' => asset(''),
            'auth'       => Auth::user()
    	]); !!}
	</script>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
</body>
</html>
