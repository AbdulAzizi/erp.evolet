@extends('layouts.default')

@section('content')
    <profile-position 
        :user="{{ $user }}"
        :positions="{{ $user->positions }}"
    ></profile-position>
@endsection