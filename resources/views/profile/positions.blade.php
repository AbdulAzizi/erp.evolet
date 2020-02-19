@extends('layouts.profile')

@section('content')
    <positions
    :user="{{$user}}"
    editable="{{$editable}}"
    />
@endsection 