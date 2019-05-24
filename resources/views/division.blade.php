@extends('layouts.default')

@section('content')

{{-- {{dd($division->toArray())}} --}}

@php
$head = $division->head;
@endphp

<v-container>
    <v-layout>
        <v-flex>
            <v-container grid-list-md>
                <v-layout>
                    <v-flex xs10>
                        {{-- //TODO make separate component from here and print them RECURSIVLY --}}
                        <v-expansion-panel expand>
                            <v-expansion-panel-content>
                                <template v-slot:header>
                                    <h1 class="title">{{$division->name}}</h1>
                                </template>
                                <v-container grid-list-lg>
                                    <v-layout row wrap>
                                        <v-flex d-flex xs2 v-for="employee in {{$division->employees}}"
                                            :key="employee.id">
                                            <employee-card :employee="employee" />
                                        </v-flex>
                                    </v-layout>
                                </v-container>
                            </v-expansion-panel-content>
                        </v-expansion-panel>
                    </v-flex>
                    @if ($head)
                    <v-flex>
                        <employee-card :employee="{{$head}}" />
                    </v-flex>
                    @endif
                </v-layout>
            </v-container>
        </v-flex>
    </v-layout>
</v-container>
@endsection