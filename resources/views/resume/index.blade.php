@extends('layouts.default')

@section('content')
<resumes-view :resumes="{{$resumes}}"></resumes-view>
@endsection
