@extends('layouts.default')

@section('content')
    <views-users-index :users="{{ $users }}" :add-user="{{ json_encode(true) }}"></views-users-index>
@endsection
