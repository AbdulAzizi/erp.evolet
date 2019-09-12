@extends('layouts.withoutSidebars')

@section('content')
    <chats-view :commentable="{{$task}}" :users="{{$users}}"></chats-view>
@endsection