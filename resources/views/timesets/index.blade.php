@extends('layouts.default')

@section('content')
    <views-timesets-index :divisions="{{$divisions}}">
    </views-timesets-index>
@endsection
