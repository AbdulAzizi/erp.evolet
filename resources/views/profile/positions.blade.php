@extends('layouts.profile')

@section('content')
    <positions :positions="{{ $user->positions }}" />
@endsection