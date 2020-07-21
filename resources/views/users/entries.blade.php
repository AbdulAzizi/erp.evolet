@extends('layouts.profile')

@section('content')
    <views-entries-index class="mt-3" :entries="{{$entries}}"></views-entries-index>
@endsection 