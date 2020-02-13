@extends('layouts.default')

@section('content')

    <views-division
    :division="{{$division}}"
    :divisions="{{$divisions}}"
    :is-user-head="{{$isUserHead}}" 
    :is-root="true"
    :jsonPositions="{{$jsonPositions}}"
    :ildInputs="{{$oldInputs}}"
    :errors="{{ $errors }}"
    :user="{{$authUser}}">
    </views-division>



<dynamic-form title="Добавить в структуру" dialog activator-event-name="addDivision" action-url="/divisions" method="POST"
     :fields="[
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