@extends('layouts.default')

@section('content')
    <users-show :user="{{ $user ? $user : $authUser }}"></users-show>
@endsection
