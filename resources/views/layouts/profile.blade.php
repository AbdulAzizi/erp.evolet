@extends('app')

@section('layout')
	<left-drawer></left-drawer>

	<navbar :user="{{$authUser}}"></navbar>

	<v-main>
        <v-container fluid class="pb-0">
            <profile-banner 
            :user="{{ $user }}" 
            :editable="{{ $user->id == $authUser->id ? 'true' : 'false' }}"
            ></profile-banner>
            @yield('content')
        </v-container>
	</v-main>
@endsection
