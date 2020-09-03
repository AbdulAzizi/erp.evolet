@extends('layouts.profile')

@section('content')
    <v-container fluid class="pa-0" style="max-width:1200px;">
        <views-entries-index class="mt-3" :entries="{{$entries}}"></views-entries-index>
    </v-container>
@endsection 