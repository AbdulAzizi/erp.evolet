@extends('layouts.default')

@section('content')
    <file-cards :items="{{$files}}"/>
@endsection