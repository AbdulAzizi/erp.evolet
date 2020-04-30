@extends('layouts.default')

@section('content')
    <views-timesets-index :users="{{$users}}" :timesets="{{$timesets}}"></views-timesets-index>
@endsection
