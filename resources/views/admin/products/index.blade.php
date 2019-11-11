@extends('layouts.fullWidth')

@section('content')
    <products-admin-view
        :pcs="{{$pcs}}"
        :countries="{{$countries}}"
    ></products-admin-view>
@endsection