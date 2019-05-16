@extends('app')

@section('layout')

	{{-- <right-drawer></right-drawer> --}}
	
	<left-drawer></left-drawer>
	
	<navbar :user="{{ Auth::user()->load(['employee.division','employee.responsibility']) }}"></navbar>

	<v-content>
		@yield('content')
	</v-content>
	
	
	
@endsection