@extends('layouts.fullWidth')

@section('content')
    <products-admin-view
        :pcs="{{$pcs}}"
        :countries="{{$countries}}"
        :processes="{{$processes}}"
    ></products-admin-view>
@endsection