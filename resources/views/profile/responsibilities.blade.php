@extends('layouts.default')

@section('content')
    <profile-responsibility 
        :user="{{ $user }}"
        :responsibilities="{{ $user->responsibilities }}"
    ></profile-responsibility>
@endsection