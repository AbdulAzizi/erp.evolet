@extends('layouts.default')

@section('content')
<positions 
    :positions="{{$positions}}" 
    :user="{{$authUser}}" 
    :divisions="{{$divisions}}">
</positions>
@endsection
