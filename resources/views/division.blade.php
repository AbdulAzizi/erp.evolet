@extends('layouts.default')

@section('content')

@php
$isUserHead = false;

if($division[0]->head){
$isUserHead = $division[0]->head->id === $authUser->id;
}

$isUserHead = json_encode($isUserHead);
$oldInputs = json_encode(Session::getOldInput());
$jsonPositions = json_encode($positions);
$jsonResponsibilities = json_encode($responsibilities);


@endphp

<v-container fluid ma-0 grid-list-lg>
    <v-layout>
        <v-flex xs10>
            <v-expansion-panels class="divisions">
                <division :division="{{$division[0]}}" :is-user-head="{{$isUserHead}}" :is-root="true" />
            </v-expansion-panels>
        </v-flex>
    </v-layout>
</v-container>


<dynamic-form title="Новый сотрудник" dialog activator-event-name="addUser" action-url="/users" method="POST"
    :errors="{{$errors}}" :old-inputs="{{$oldInputs}}" :fields="[
        {
            type: 'string',
            name: 'name',
            label: 'Имя',
            rules: ['required']
        },
        {
            type: 'string',
            name: 'surname',
            label: 'Фамилия',
            rules: ['required']
        },
        {
            type: 'string',
            name: 'email',
            label: 'Email',
            rules: ['required']
        },
        {
            type: 'select',
            name: 'positionId',
            label: 'Должность',
            items: {{$jsonPositions}},
            rules: ['required']
        },
        {
            type: 'autocomplete',
            name: 'responsibilityId',
            label: 'Полномочия',
            items: {{$jsonResponsibilities}},
            multiple: true,
            rules: ['required']
        },
    ]"></dynamic-form>

<dynamic-form title="Добавить в структуру" dialog activator-event-name="addDivision" action-url="/divisions" method="POST"
    :errors="{{$errors}}" :old-inputs="{{$oldInputs}}" :fields="[
        {
            type: 'string',
            name: 'name',
            label: 'Название',
            rules: ['required']
        },
        {
            type: 'string',
            name: 'abbreviation',
            label: 'Аббревиатура',
        }
    ]"></dynamic-form>

@endsection