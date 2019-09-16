@extends('layouts.default')

@section('content')
    <profile-responsibility 
    :user="{{ $user }}"
    ></profile-responsibility>
@endsection