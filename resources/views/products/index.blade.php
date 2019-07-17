@extends('layouts.default')

@section('content')
    @php
        $jsonForm = isset($form) ? $form : json_encode(null) ;
    @endphp
        <products-view :form="{{$jsonForm}}" :products="{{$products}}"></products-view>

@endsection