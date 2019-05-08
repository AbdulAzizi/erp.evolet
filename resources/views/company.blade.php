@extends('layouts.default')

@section('content')
    <v-container >
        <v-layout justify-center>
            <v-flex sm8>
                <v-card>
                    <v-treeview 
                    :items="{{$company}}"
                    item-text="abbreviation"
                    activatable
                    open-on-click
                    >
                        <template v-slot:label="{ item }">
                            @{{ item.abbreviation }} - @{{item.name}}
                        </template>
                    </v-treeview>
                </v-card>
            </v-flex>
        </v-layout>
    </v-container>
@endsection
