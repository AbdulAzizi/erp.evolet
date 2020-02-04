@extends('layouts.default')

@section('content')
    <views-users-index :users="{{ $users }}" :add-user="true" :user-link="true"></views-users-index>
@endsection
