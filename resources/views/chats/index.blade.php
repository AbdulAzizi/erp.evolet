@extends('layouts.withoutSidebars')

@section('content')
    <chats-view 
    :chats="{{json_encode($chats)}}" 
    :groups="{{$groups}}" 
    :users="{{$users}}"
    />
@endsection