@extends('layouts.default')

@section('content')
    <profile-tasks :user="{{ $user ? $user : $authUser }}"></profile-tasks>
@endsection
