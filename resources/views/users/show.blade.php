@extends('layouts.default')

@section('content')
<users-show :user="{{ $user }}" :authuser="{{ Auth::user() }}"></users-show>
@endsection
