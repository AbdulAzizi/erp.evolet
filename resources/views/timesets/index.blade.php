@extends('layouts.default')

@section('content')
    {{-- <views-timesets-index 
    :users="{{$users}}"
    :divisions="{{$divisions}}">
    </views-timesets-index> --}}
    <views-timesets-index-new
    :users="{{$users}}"
    :divisions="{{$divisions}}"
    >
    </views-timesets-index-new>
@endsection
