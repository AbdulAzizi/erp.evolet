<template>
    <v-dialog v-model="show" max-width="600">
        <v-form action="users" method="POST">
            <v-card>
                <v-toolbar flat color="primary" dark>
                    <v-toolbar-title class="font-weight-regular">Новый сотрудник</v-toolbar-title>
                </v-toolbar>
                <v-card-text>
                    <form-field
                        v-for="(field, i) in formFields"
                        :key="i"
                        :field="field"
                        :errors="errors"
                        :old-inputs="oldinputs"
                    />
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="primary" flat="flat" @click="show = false">Отмена</v-btn>
                    <v-btn color="primary" type="submit">Добавить</v-btn>
                </v-card-actions>
            </v-card>
        </v-form>
    </v-dialog>
</template>

<script>
//TODO use same design as in tasks/Add
export default {
    props: ["positions", "responsibilities", "errors", "oldinputs"],
    data() {
        return {
            show: Array.isArray(this.errors) ? false : true,
            csrf: window.Laravel.csrf_token,

            divisionId: null
        };
    },
    created() {
        Event.listen("addUser", data => {
            this.show = true;
            this.divisionId = data.divisionId;
        });
    },
    computed: {
        formFields() {
            return [
                { type: "input", name: "_token", value: this.csrf },
                { type: "input", name: "divisionId", value: this.divisionId },
                {
                    type: "string",
                    name: "name",
                    label: "Имя"
                },
                {
                    type: "string",
                    name: "surname",
                    label: "Фамилия"
                },
                {
                    type: "string",
                    name: "email",
                    label: "Email"
                },
                {
                    type: "select",
                    name: "positionId",
                    label: "Должность",
                    items: this.positions,
                },
                {
                    type: "autocomplete",
                    name: "responsibilityId",
                    label: "Полномочия",
                    items: this.responsibilities,
                    multiple: true
                },
            ];
        }
    }
};
</script>

<style>
</style>
