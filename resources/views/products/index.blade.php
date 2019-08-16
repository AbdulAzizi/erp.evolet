@extends('layouts.default')

@section('content')
    @php
        $jsonForm = isset($form) ? $form : json_encode(null) ;
    @endphp
        <products-view 
        :items="{{$products}}"
        :participants="{{$participants}}"
        ></products-view>

@endsection