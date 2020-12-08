@extends('layouts.default')

@section('content')
    <views-users-index :division="{{ $division }}"></views-users-index>
@endsection
