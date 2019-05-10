@extends('layouts.clean')

@section('content')


    
<v-container fluid fill-height>
    <v-layout align-center justify-center>
        <v-flex xs12 sm8 md4>
            <v-card>
                <v-form
                action="{{ route('login') }}"
                method="POST"
                >
                    @csrf
                    <v-toolbar dark flat color="primary">

                        <v-toolbar-title>Авторизация</v-toolbar-title>

                        <v-spacer></v-spacer>
                        {{-- <v-tooltip bottom>
                            <v-btn 
                            icon
                            slot="activator"
                            >
                                <v-icon>assignment</v-icon>
                            </v-btn>
                            <span>Регистрация</span>
                        </v-tooltip> --}}
                    </v-toolbar>
                    <v-card-text>
                        

                            <input 
                            type="hidden" 
                            name="remember" 
                            value="1" />

                            <v-text-field
                            name="email"
                            label="Email"
                            prepend-icon="person"
                            ></v-text-field>
                    
                            <v-text-field
                            name="password"
                            type="password"
                            label="Пароль"
                            prepend-icon="lock"
                            ></v-text-field>
                        
                    </v-card-text>
                    <v-card-actions>
                        <v-btn flat color="primary">Забыл пароль</v-btn>
                        <v-spacer></v-spacer>
                        <v-btn type="submit" color="primary">Вход</v-btn>
                    </v-card-actions>
                </v-form>
            </v-card>
        </v-flex>
    </v-layout>
</v-container>
    


{{-- <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Login') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Login') }}
                                </button>

                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> --}}
@endsection
