@extends('layouts.default')

@section('content')
    @php
        $jsonForm = isset($form) ? $form : json_encode(null) ;
    @endphp
        <products-view :form="{{$jsonForm}}" :items="{{$products}}"></products-view>

@endsection