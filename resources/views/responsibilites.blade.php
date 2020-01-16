@extends('layouts.default')

@section('content')
    <responsibilities :responsibilities="{{$responsibilities}}"></responsibilities>
@endsection
