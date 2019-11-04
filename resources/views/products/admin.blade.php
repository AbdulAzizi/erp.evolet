@extends('layouts.fullWidth')

@section('content')
    <products-admin-view
        :items="{{$products}}"
    ></products-admin-view>
@endsection