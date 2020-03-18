@extends('layouts.default')

@section('content')

    <views-division
    :division="{{$division}}"
    :is-user-head="{{$isUserHead}}" 
    :is-root="true"
    :jsonPositions="{{$jsonPositions}}"
    >
    </views-division>

@endsection