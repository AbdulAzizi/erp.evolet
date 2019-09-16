@extends('layouts.withoutSidebars')

@section('content')
    <messages-view :commentable="{{$task}}" :users="{{$users}}"></messages-view>
@endsection