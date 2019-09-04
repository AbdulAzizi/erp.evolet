@extends('layouts.default')

@section('content')
    <v-container>
        <v-row>
            <v-col  align-self="center">
            <task :item="{{$task}}" :users="{{$users}}"></task>
            </v-col>
        </v-row>
    </v-container>
@endsection