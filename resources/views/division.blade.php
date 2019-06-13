@extends('layouts.default')

@section('content')

@php
    // dd($division->toArray());
    $isUserHead = false;

    if($division[0]->head){
        $isUserHead = $division[0]->head->id === $authUser->id;
    }
    $isUserHead = json_encode($isUserHead);
    
@endphp

<v-container fluid ma-0 grid-list-lg>
    <v-layout>
        <v-flex xs10>
            <division :division="{{$division[0]}}" :is-user-head="{{$isUserHead}}" :is-root="true"/>
</v-flex>
</v-layout>
</v-container>

<add-user-dialog :responsibilities="{{$responsibilities}}" :positions="{{$positions}}" :errors="{{$errors}}"
    :oldinputs="{{json_encode(Session::getOldInput())}}" />

@endsection