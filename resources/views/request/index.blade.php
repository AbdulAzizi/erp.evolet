@extends('layouts.default')

@section('content')
    <views-requests-index :requests="{{$requests}}"></views-requests-index>
@endsection