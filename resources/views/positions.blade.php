@extends('layouts.default')

@section('content')
<positions 
    :divisions="{{$divisions}}"
    :editable="true">
</positions>
@endsection
