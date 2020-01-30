@extends('layouts.default')

@section('content')
    <views-users-index :users="{{ $users }}"></views-users-index>
@endsection
