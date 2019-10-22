@extends('layouts.default')

@section('content')
                <projects-create 
                    :users="{{ $users }}" 
                    :pcs="{{ $pcs }}" 
                    :countries="{{ $countries }}" 
                />
@endsection