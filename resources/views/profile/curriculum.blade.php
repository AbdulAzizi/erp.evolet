@extends('layouts.default')

@section('content')
    @if ($user->resume()->exists())
    <profile-resume :user="{{$user}}"
                    ></profile-resume>
    @else
        @if (!$user->resume()->exists() && auth()->id() == $user->id )
        <profile-resume-create :user="{{$user}}"></profile-resume-create>
        @else
        <profile-error :user="{{$user}}"></profile-error>
        @endif
    @endif
@endsection
