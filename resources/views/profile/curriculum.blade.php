@extends('layouts.default')

@section('content')
    @if ($user->resume()->exists())
    <profile-resume :user="{{$user}}" :resume="{{$resume}}"></profile-resume>
    @else
    <profile-resume-create :user="{{$user}}"></profile-resume-create>
    @endif
@endsection
