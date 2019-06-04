@extends('layouts.default')

@section('content')

@php
    $isEmployeeHead = $division[0]->head->id === $authUser->employee->id;
    $isEmployeeHead = json_encode($isEmployeeHead);
    
@endphp

{{-- {{dd($positions->toArray())}} --}}

<v-container fluid ma-0 grid-list-lg>
    <v-layout>
        <v-flex xs10>
            <division :division="{{$division[0]}}" :is-employee-head="{{$isEmployeeHead}}" />
        </v-flex>
    </v-layout>
</v-container>

<add-employee-dialog 
    :responsibilities="{{$responsibilities}}" 
    :positions="{{$positions}}" 
    :errors="{{$errors}}"
    :oldinputs="{{json_encode(Session::getOldInput())}}"
/>

@endsection