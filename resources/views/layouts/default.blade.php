@extends('app')

@section('layout')

	<right-drawer></right-drawer>
	
	<navbar></navbar>

	<v-content>
		@yield('content')
	</v-content>
	
	<left-drawer></left-drawer>
	
@endsection