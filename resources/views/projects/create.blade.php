@extends('layouts.default')

@section('content')
    <v-container>
        <v-row justify="center">
            <v-col cols="6" >
                <projects-create 
                    :users="{{ $users }}" 
                    :pcs="{{ $pcs }}" 
                    :countries="{{ $countries }}" 
                />
            </v-col>
        </v-row>
    </v-container>
@endsection