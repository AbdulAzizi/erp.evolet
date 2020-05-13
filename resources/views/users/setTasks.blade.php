@extends('layouts.profile')

@section('content')
<views-users-set-tasks :fromtasks="{{ $fromTasks }}" :totasks="{{ $toTasks }}"></views-users-set-tasks>
@endsection
