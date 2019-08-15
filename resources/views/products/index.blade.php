@extends('layouts.fullWidth')

@section('content')
    @php
        $jsonForm = isset($form) ? $form : json_encode(null) ;
    @endphp
        <products-view
        :form="{{$jsonForm}}"
        :items="{{$products}}"
        :participants="{{$participants}}"
        ></products-view>

@endsection
