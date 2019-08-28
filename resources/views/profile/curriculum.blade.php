@extends('layouts.default')

@section('content')

<profile-resume :user="{{$user}}" :permit="{{auth()->id()}}"></profile-resume>


@endsection
