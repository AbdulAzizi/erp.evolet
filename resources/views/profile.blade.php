@extends('layouts.default')

@section('content')
    <profile-view :user="{{ $user ? $user : $authUser }}" />
@endsection