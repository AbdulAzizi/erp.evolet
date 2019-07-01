<template>
    <v-dialog v-model="show" :max-width="width || '600'">
        <v-form :action="actionUrl" :method="method" ref="form">
            <v-card>
                <v-toolbar flat color="primary" dark>
                    <v-toolbar-title class="font-weight-regular">{{title}}</v-toolbar-title>
                </v-toolbar>
                <v-card-text>
                    <form-field
                        v-for="(field, i) in localFields"
                        :key="i"
                        :field="field"
                        :errors="errors"
                        :old-inputs="oldInputs"
                    />
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <!--//TODO Add dynamic actions -->
                    <v-btn color="primary" flat="flat" @click="show = false">Отмена</v-btn>
                    <v-btn color="primary" type="submit" @click="submit">Добавить</v-btn>
                </v-card-actions>
            </v-card>
        </v-form>
    </v-dialog>
</template>

<script>
export default {
    props: {
        title: {
            type: String,
            required: true
        },
        fields: {
            type: Array,
            required: true
        },
        actionUrl: String,
        method: String,
        width: String,
        activatorEventName: String,
        errors: null,
        oldInputs: null
    },
    data() {
        return {
            show: this.formHasErrors() ? true : false,

            localFields: [
                {
                    type: "input",
                    name: "_token",
                    value: window.Laravel.csrf_token
                },
                ...this.fields
            ]
        };
    },
    created() {
        this.addFormsEventActivator();

        this.processLaravelOldInputs();
    },
    methods: {
        formHasErrors() {
            const hasErrorsInProps = this.errors && !Array.isArray(this.errors);

            if (!hasErrorsInProps) return false;

            const fieldsWithErrors = this.fields.filter(
                field => this.errors[field.name]
            );

            if (fieldsWithErrors.length === 0) return false;

            return true;
        },
        addFormsEventActivator() {
            if (!this.activatorEventName) return;

            Event.listen(this.activatorEventName, data => {
                this.show = true;
                this.checkAndAddNewFieldsFrom(data);
            });
        },
        checkAndAddNewFieldsFrom(data) {
            if (!Array.isArray(data)) return;

            const fieldsFromData = data.filter(
                d => d && d.hasOwnProperty("type")
            );

            this.addNewFields(fieldsFromData);
        },
        addNewFields(fields) {
            this.localFields = [...this.localFields, ...fields];
        },
        processLaravelOldInputs() {
            if (!this.oldInputs) return;

            for (const inputName in this.oldInputs) {
                if (this.oldInputs.hasOwnProperty(inputName)) {
                    const inputValue = this.oldInputs[inputName];

                    const field = this.localFields.filter(
                        field => field.name === inputName
                    );

                    if (field.length === 0) {
                        this.addNewFields([
                            {
                                type: "input",
                                name: inputName,
                                value: inputValue
                            }
                        ]);
                    }
                }
            }
        },

        submit(e) {
            this.formHasErrors = false;
            this.formHasErrors = !this.$refs.form.validate();

            if (!this.formHasErrors) return;

            e.preventDefault();
        }
    }
};
</script>

<style>
</style>
