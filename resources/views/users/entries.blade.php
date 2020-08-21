@extends('layouts.profile')

@section('content')
    <v-container class="py-0">
        <views-entries-index class="mt-3" :entries="{{$entries}}"></views-entries-index>
    </v-container>
@endsection 