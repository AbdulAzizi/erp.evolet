@extends('layouts.default')

@section('content')
    <products-create-view :form="{{$form}}"></products-create-view>
@endsection