@extends('layouts.default')

@section('content')
    <users-view :users="{{ $users }}"></users-view>
@endsection
