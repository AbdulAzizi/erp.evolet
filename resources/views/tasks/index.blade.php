@extends('layouts.default')

@section('content')
    {{-- <v-container >
        <v-layout justify-center>
            <v-flex sm8> --}}
                {{-- <tasks :alltasks="{{ $tasks }}" :employees="{{ $employees }}"></tasks> --}}
                <tasks-view :tasks="{{ $tasks }}" :employees="{{ $employees }}"></tasks-view>
            {{-- </v-flex>
        </v-layout>
    </v-container> --}}
@endsection