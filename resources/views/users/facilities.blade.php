@extends('layouts.profile')

@section('content')
<views-facilities-index class="mt-3" :facilities="{{$facilities}}" :userid="{{$userId}}"></views-facilities-index>
@endsection 