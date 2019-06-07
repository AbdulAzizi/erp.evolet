@extends('layouts.clean')

@section('content')
<v-container fluid fill-height>
    <v-layout align-center justify-center>
        <v-flex xs12 sm8 md4>
            <v-layout justify-center class="mb-5">
                <v-flex xs8>
                    <v-img :src="'/img/dark-logo.png'" />
                </v-flex>
            </v-layout>
            <v-card>
                <v-form action="{{ route('password.update') }}" method="POST">
                    @csrf
                    <v-toolbar dark flat color="primary">

                        <v-toolbar-title>Установка пароля</v-toolbar-title>

                        <v-spacer></v-spacer>
                    </v-toolbar>
                    <v-card-text>
                        <input type="hidden" name="token" value="{{ $token }}">

                        <v-text-field name="email" label="Email" prepend-icon="person" :error-messages="{{ json_encode($errors->get('email')) }}"></v-text-field>

                        <v-text-field name="password" type="password" label="Пароль" prepend-icon="lock" :error-messages="{{ json_encode($errors->get('password')) }}"></v-text-field>

                        <v-text-field name="password_confirmation" type="password" label="Повторите пароль" prepend-icon="lock"></v-text-field>

                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn type="submit" color="primary">Применить</v-btn>
                    </v-card-actions>
                </v-form>
            </v-card>
        </v-flex>
    </v-layout>
</v-container>
@endsection