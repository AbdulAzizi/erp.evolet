@extends('app')

@section('layout')

	{{-- <right-drawer :users="{{$users}}"></right-drawer> --}}

	<left-drawer></left-drawer>

	<navbar :user="{{$authUser}}"></navbar>

	<v-content>
        <v-container fluid class="pb-0">
            @yield('content')
        </v-container>
	</v-content>



@endsection
