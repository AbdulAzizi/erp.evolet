@extends('layouts.default')

@section('content')
    <v-container>
        <v-layout justify-center>
            <v-flex xs8>
                <task :item="{{$task}}"></task>
            </v-flex>
        </v-layout>
    </v-container>
@endsection