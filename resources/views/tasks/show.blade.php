@extends('layouts.default')

@section('content')
    <v-container>
        <v-row justify="center">
            <v-col md="11" align-self="center">
                <task :item="{{$task}}"></task>
            </v-col>
        </v-row>
    </v-container>
@endsection