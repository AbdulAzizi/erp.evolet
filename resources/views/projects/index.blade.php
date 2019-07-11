@extends('layouts.default')

@section('content')
    <projects-view :projects="{{ $projects }}" />
@endsection