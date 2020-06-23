@extends('layouts.default')

@section('content')
    <views-timesets-index 
    :users="{{$users}}" 
    :timesets="{{$timesets}}"
    from="{{$from}}"
    to="{{$to}}"
    ></views-timesets-index>
@endsection
