@extends('app')

@section('layout')

	<navbar :user="{{$authUser}}"></navbar>

	<v-main>
        @yield('content')
	</v-main>

@endsection
