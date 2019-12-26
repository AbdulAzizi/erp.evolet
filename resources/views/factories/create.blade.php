@extends('layouts.default')

@section('content')
    <views-factories-create :countries="{{$countries}}" />
@endsection
