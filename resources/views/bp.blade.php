@extends('layouts.default')

@section('content')
    <bp :processes="{{$processes ?: json_encode(null)}}" />
@endsection