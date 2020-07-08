@extends('app')

@section('layout')

	{{-- <right-drawer></right-drawer> --}}

	<left-drawer></left-drawer>

	<navbar :user="{{$authUser}}"></navbar>

	<v-main>
        @yield('content')
	</v-main>

@endsection
