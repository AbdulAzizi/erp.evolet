@extends('layouts.default')

@section('content')
    <tasks-view 
    :tasks="{{ $tasks }}" 
    :users="{{ $users }}"
    :errors="{{ $errors }}"
    :tags="{{ $tags }}"
    ></tasks-view>
@endsection