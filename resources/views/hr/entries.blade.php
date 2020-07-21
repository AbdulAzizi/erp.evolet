@extends('layouts.default')

@section('content')
    <views-entries-add></views-entries-add>
    <views-entries-index class="mt-3" :entries="{{$entries}}"></views-entries-index>
@endsection
