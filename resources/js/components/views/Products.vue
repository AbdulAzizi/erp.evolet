<template>
    <div>
        <v-dialog v-model="dialog" max-width="700" lazy v-if="form">
            <template v-slot:activator="{ on }">
                <v-fab-transition>
                    <v-btn v-on="on" dark fab fixed bottom right small color="primary">
                        <v-icon>add</v-icon>
                    </v-btn>
                </v-fab-transition>
            </template>
            <v-card>
                <v-form action="/tasks" method="post" ref="form">
                    <v-toolbar flat color="primary" dark>
                        <v-toolbar-title class="font-weight-regular">{{form.name}}</v-toolbar-title>
                    </v-toolbar>

                    <v-container>
                        <v-layout row wrap>
                            <v-flex xs12 v-for="(field,index) in form.fields" :key="'field-'+index">
                                <form-field
                                    :field="{
                                        type: field.type,
                                        name: field.name,
                                        label: field.label
                                    }"
                                />
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-form>
            </v-card>
        </v-dialog>hELLO
    </div>
</template>

<script>
export default {
    props: {
        form: {
            required: false
        }
    },
    data() {
        console.log(this.form);

        return {
            dialog: false
        };
    }
};
</script>

<style>
</style>
