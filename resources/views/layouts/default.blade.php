@extends('app')

@section('layout')

	{{-- <right-drawer></right-drawer> --}}
	
	<left-drawer></left-drawer>
	
	<navbar :user="{{$authUser}}"></navbar>

	<v-content>
		@yield('content')
	</v-content>
	
	
	
@endsection