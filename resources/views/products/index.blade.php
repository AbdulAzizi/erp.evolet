@extends('layouts.fullWidth')

@section('content')
    @php
        $jsonForm = isset($form) ? $form : json_encode(null) ;
    @endphp
        <products-view
        :items="{{$products}}"
        :participants="{{$participants ? $participants : json_encode(null)}}"
        :project="{{$project ? $project : json_encode(null)}}"
        ></products-view>

@endsection
