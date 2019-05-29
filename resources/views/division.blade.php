@extends('layouts.default')

@section('content')

{{-- {{dd($division->toArray())}} --}}

<v-container fluid ma-0 grid-list-lg>
    <v-layout>
        <v-flex xs10>
            {{-- //TODO make separate component from here and print them RECURSIVLY --}}
            <division :division="{{$division[0]}}" />
        </v-flex>
        @if ($division[0]->head)
        <v-flex>
            <employee-card :employee="{{$division[0]->head}}" />
        </v-flex>
        @endif
    </v-layout>
</v-container>
@endsection