@extends('layouts.profile')

@section('content')
    <users-show :user="{{ $user }}" :authuser="{{ Auth::user() }}"></users-show>
@endsection
