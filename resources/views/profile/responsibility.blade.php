@extends('layouts.default')

{{-- @php
dd($responsibilities);
@endphp --}}


@section('content')
<profile-responsibility :user="{{ $user }}" :responsibilities="{{ $responsibilities }}"></profile-responsibility>
@endsection