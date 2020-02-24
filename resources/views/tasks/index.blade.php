@extends('layouts.default')

@section('content')
    <tasks-view 
    :tasks="{{ $tasks }}" 
    :divisions="{{ $divisions }}"
    :errors="{{ $errors }}"
    :statuses="{{ $statuses }}"
    :authuser="{{ $authUser }}"
    ></tasks-view>
@endsection