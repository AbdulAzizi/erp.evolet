@extends('layouts.default')

@section('content')
    <v-btn color="success">
        Success
        <v-icon right dark>contact_support</v-icon>
    </v-btn>
@endsection

{{-- @extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    You are logged in!
                </div>
                <v-btn color="success">Success</v-btn>
            </div>
        </div>
    </div>
</div>
@endsection --}}
