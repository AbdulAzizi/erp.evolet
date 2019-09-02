@extends('layouts.default')

@section('content')

<human-resources-view

:user="{{auth()->user()}}"
:amount-of-users="{{$amountOfUsers}}"
:amount-of-resumes="{{$amountOfResumes}}"/>

@endsection
