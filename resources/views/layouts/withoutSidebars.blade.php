@extends('app')

@section('layout')

	<navbar :user="{{$authUser}}"></navbar>

	<v-content>
        @yield('content')
	</v-content>

@endsection
