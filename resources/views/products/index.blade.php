@extends('layouts.fullWidth')

@section('content')
    @php
        $jsonForm = isset($form) ? $form : json_encode(null) ;
    @endphp
        <products-view
        :items="{{$products}}"
        :participants="{{$participants}}"
        :project="{{$project}}"
        ></products-view>

@endsection
