@extends('layouts.default')

@section('content')
<resume-show :resume="{{$resume}}" :user="{{$user}}"></resume-show>
@endsection
