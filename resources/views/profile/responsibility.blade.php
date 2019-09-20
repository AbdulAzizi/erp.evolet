@extends('layouts.default')

@section('content')
    <profile-responsibility 
        :user="{{ $user }}"
        :responsibilities="{{ $division ? $division->responsibilities : null }}"
    ></profile-responsibility>
@endsection