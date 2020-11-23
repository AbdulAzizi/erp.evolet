<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>ПУРП</title>
{{-- 
    <!-- Scripts -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet"> --}}
    <style>
        *::-webkit-scrollbar-track
        {
            border-radius: 10px;
            background-color: #d9d9d9;
        }

        *::-webkit-scrollbar
        {
            width: 8px;
            background-color: transparent;
        }

        *::-webkit-scrollbar-thumb
        {
            border-radius: 50px;
            background-color: #ababab;
        }
        *::-webkit-scrollbar-thumb:hover
        {
            background-color: #777;
        }

    </style>
</head>
<body style="margin:0">
    
    <div id="app">
        <v-app style="background-color:#e6e6e6;">
            <alerts></alerts>
            @yield('layout')
        </v-app>
            
    </div>
    
    <script>
		window.Laravel = {!! json_encode([
        	'csrf_token' => csrf_token(),
            'asset_path' => asset(''),
            'auth'       => Auth::user(),
            'alerts'    => session()->get('alerts'),
            'currentTask' => isset($currentTask) ? $currentTask : null,
            'userRequests' => isset($userRequests) ? $userRequests : null
        ]); !!}
	</script>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
</body>
</html>
