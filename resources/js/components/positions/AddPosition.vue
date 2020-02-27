<template>
    <v-dialog eager width="600" v-model="dialog">
        <template v-slot:activator="{ on }">
            <v-btn
                depressed
                v-on="on"
                color="primary"
            >Создать должность</v-btn>
        </template>
        <v-card>
            <v-toolbar dark flat color="primary">
                <v-toolbar-title>Создать должность</v-toolbar-title>
            </v-toolbar>
            <v-card-text class="pa-4 pb-0">
                <v-form ref="form">
                    <form-field
                        :field="{
                            type: 'string',
                            label: 'Должность',
                            name: 'position',
                            rules: ['required']
                        }"
                        v-model="position"
                    />
                </v-form>
            </v-card-text>
            <v-card-actions>
                <v-spacer />
                <v-btn text color="primary" @click="cancel()">отмена</v-btn>
                <v-btn dark color="primary" @click="submitForm()">создать</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>
<script>
export default {
    props: {
        divisionId: {
            required: true
        }
    },
    data() {
        return {
            position: null,
            dialog: false
        };
    },
    methods: {
        submitForm() {
            const form = this.$refs.form;
            if (form.validate()) {
                axios
                    .post("/api/create/position", {
                        divisionId: this.divisionId,
                        position: this.position
                    })
                    .then(res => {
                        Event.fire("newPositionCreated", {
                            divisionId: this.divisionId,
                            position: res.data
                        });
                        this.dialog = false;
                        form.reset();
                    })
                    .catch(err => err.messages);
            }
        },
        cancel() {
            this.dialog = false;
            const form = this.$refs.form;
            form.reset();
        }
    }
};
</script>