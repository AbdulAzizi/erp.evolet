@extends('layouts.default')

@section('content')

@php
    $userAttributes = $employee ? $employee->user : $authUser;
    $employeeAttributes = $employee ? $employee : $authUser->employee;
@endphp

<v-container grid-list-md>
    <v-layout justify-center>
        <v-flex>
            <v-card>
                <v-card-text>
                    <v-container>
                        <v-layout align-center>
                            <v-flex xs2 lg2 xl1 class="mr-3">
                                <v-img :src="photo('{{ $userAttributes->img }}')" style="border-radius: 100%"
                                    class="elevation-3 " />
                            </v-flex>
                            <v-flex>
                                <h6 class="headline ">{{$userAttributes->name}} {{$userAttributes->surname}}</h6>
                                <h6 class="subheading grey--text text--darken-2">{{$employeeAttributes->position->name}} -
                                    {{$employeeAttributes->responsibility->name}}</h6>
                                <h6 class="subheading grey--text text--darken-2">{{$employeeAttributes->division->name}}</h6>
                            </v-flex>
                        </v-layout>
                    </v-container>
                    <v-tabs grow slider-color="primary">
                        <v-tab>
                            Some
                        </v-tab>
                        <v-tab>
                            Some
                        </v-tab>
                        <v-tab>
                            Some
                        </v-tab>
                        <v-tab>
                            Some
                        </v-tab>
                        <v-tab>
                            Some
                        </v-tab>
                        <v-tab>
                            Some
                        </v-tab>
                    </v-tabs>
                </v-card-text>
            </v-card>
        </v-flex>
    </v-layout>
</v-container>
@endsection