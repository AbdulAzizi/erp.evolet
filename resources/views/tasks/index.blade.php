@extends('layouts.default')

@section('content')
    {{-- <v-container >
        <v-layout justify-center>
            <v-flex sm8> --}}
                <tasks :alltasks="{{ $allTasks }}"></tasks>
            {{-- </v-flex>
        </v-layout>
    </v-container> --}}
@endsection