@extends('layouts.default')

@section('content')

    <views-division
    :division="{{$division}}"
    :divisions="{{$divisions}}"
    :is-user-head="{{$isUserHead}}" 
    :is-root="true"
    :jsonPositions="{{$jsonPositions}}"
    >
    </views-division>

@endsection