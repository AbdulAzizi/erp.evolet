@extends('layouts.profile')

@section('content')
    <views-users-set-tasks :tasks="{{ $tasks }}"></views-users-set-tasks>
@endsection
