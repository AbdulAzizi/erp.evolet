@extends('layouts.default')

@section('content')

<v-container>
    <v-layout justify-center>
        <v-flex class="">
            <v-card>
                <v-card-text>
                    <v-layout align-center>
                        <v-flex xs1>
                            <v-avatar size="130">
                                <v-img :src="'../img/{{ $employee->user->img }}.jpg'" />
                            </v-avatar>
                        </v-flex>
                        <v-flex >
                            <h6 class="title">{{$employee->user->name}} {{$employee->user->surname}}</h6>
                        </v-flex>
                    </v-layout>
                </v-card-text>
            </v-card>
        </v-flex>
    </v-layout>
</v-container>
@endsection
