@extends('layouts.default')

@section('content')
    <tasks-view 
    :tasks="{{ $tasks }}" 
    :divisions="{{ $divisions }}"
    :errors="{{ $errors }}"
    :tags="{{ $tags }}"
    :statuses="{{ $statuses }}"
    :authuser="{{ $authUser }}"
    ></tasks-view>
@endsection