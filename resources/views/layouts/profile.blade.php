@extends('app')

@section('layout')
	<left-drawer></left-drawer>

	<navbar :user="{{$authUser}}"></navbar>

	<v-content>
        <v-container fluid class="pb-0">
            <profile-banner :user="{{ $user }}" :authuser="{{$authUser}}"></profile-banner>
            @yield('content')
        </v-container>
	</v-content>
@endsection
