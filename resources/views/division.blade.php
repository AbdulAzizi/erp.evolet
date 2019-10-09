@extends('layouts.default')

@section('content')

<division
    :division="{{$division}}" 
    :is-user-head="{{$isUserHead}}" 
    :is-root="true"
    :jsonResponsibilities="{{$jsonResponsibilities}}"
    :ildInputs="{{$oldInputs}}"
    :errors="{{ $errors }}"
    :user="{{$authUser}}"
    ></division>

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