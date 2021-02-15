@extends('layouts.default')

@section('content')
    <views-users-fired :users="{{$users}}"></views-users-fired>
@endsection
