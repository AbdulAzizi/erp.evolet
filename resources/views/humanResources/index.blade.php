@extends('layouts.default')

@section('content')

<human-resources-view
:user="{{auth()->user()}}"
:resumes="{{$resumes}}"/>

@endsection
