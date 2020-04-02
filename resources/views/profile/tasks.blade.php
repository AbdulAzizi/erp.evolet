@extends('layouts.profile')

@section('content')

        <views-profile-tasks 
        :timesets="{{$timesets}}" 
        :tasks="{{$tasks}}"
        :user="{{$user}}"
        ></views-profile-tasks>
@endsection 