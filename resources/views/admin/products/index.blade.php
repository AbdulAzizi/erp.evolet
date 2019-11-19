@extends('layouts.fullWidth')

@section('content')
    <products-admin-view
        :pcs="{{$pcs}}"
        :countries="{{$countries}}"
        :processes="{{$processes}}"
        :fields="{{$fields}}"
    ></products-admin-view>
@endsection