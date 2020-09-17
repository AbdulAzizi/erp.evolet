@extends('layouts.default')

@section('content')
    <tasks-view 
    :errors="{{ $errors }}"
    :authuser="{{ $authUser }}"
    ></tasks-view>
@endsection