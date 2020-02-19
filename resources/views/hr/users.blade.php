@extends('layouts.default')

@section('content')
    {{-- <views-users-index :users="{{ $users }}" :add-user="true" :user-link="true"></views-users-index> --}}
    <views-hr-users :division="{{$division}}"></views-hr-users>
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
