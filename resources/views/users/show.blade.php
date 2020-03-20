@extends('layouts.profile')

@section('content')
    <users-show :statuses="{{ $statuses }}"></users-show>
@endsection
