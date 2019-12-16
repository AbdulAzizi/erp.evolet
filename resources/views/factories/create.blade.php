@extends('layouts.default')

@section('content')
    <factories-create :countries="{{$countries}}" />
@endsection
