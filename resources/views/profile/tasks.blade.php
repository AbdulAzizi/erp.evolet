@extends('layouts.profile')

@section('content')

        <views-profile-tasks :timesets="{{$timesets}}" :tasks="{{$tasks}}" ></profile-tasks>
@endsection 