@extends('layouts.default')

@section('content')
    <views-entries-add></views-entries-add>
    <views-entries-totals class="mt-3" :users="{{$users}}"></views-entries-totals>
@endsection
