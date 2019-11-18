@extends('layouts.default')

@section('content')
<edit-product-forms :product="{{$product}}"></edit-product-forms>
@endsection