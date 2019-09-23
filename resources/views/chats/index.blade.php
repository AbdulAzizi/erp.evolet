@extends('layouts.withoutSidebars')

@section('content')
    <chats-view :chats="{{$chats}}" :users="{{$users}}"></chats-view>
@endsection