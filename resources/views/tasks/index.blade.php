@extends('layouts.default')

@section('content')
    <tasks-view 
    :tasks="{{ $tasks }}" 
    :employees="{{ $employees }}" 
    :errors="{{ $errors }}"
    :tags="{{ $tags }}"
    ></tasks-view>
@endsection