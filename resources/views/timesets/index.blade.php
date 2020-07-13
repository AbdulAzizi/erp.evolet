@extends('layouts.default')

@section('content')
    <views-timesets-index 
    :users="{{$users}}"
    :divisions="{{$divisions}}">
    </views-timesets-index>
@endsection
