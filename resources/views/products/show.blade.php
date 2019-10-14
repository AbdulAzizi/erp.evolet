@extends('layouts.default')

@section('content')
       <product :product="{{$product}}" :participants="{{$participants}}" />
@endsection
