@extends('layouts.default')

@section('content')
    
    <views-entries-totals :users="{{$users}}"></views-entries-totals>
@endsection
