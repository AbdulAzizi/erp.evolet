@extends('layouts.default')

@section('content')
    <tasks-view 
    {{-- :tasks="{{ $tasks }}"  --}}
    :divisions="{{ $divisions }}"
    :errors="{{ $errors }}"
    :authuser="{{ $authUser }}"
    ></tasks-view>
@endsection