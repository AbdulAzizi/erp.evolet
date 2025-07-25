<template>
    <component :is="formWrapper.component" v-bind="formWrapper.props" v-model="show">
        <v-form :action="actionUrl" method="POST" ref="form" autocomplete="off" enctype="multipart/form-data">
            <v-card>
                <v-toolbar flat color="primary" dark>
                    <v-toolbar-title class="font-weight-regular">{{title}}</v-toolbar-title>
                </v-toolbar>
                <v-card-text>
                    <form-field
                        v-for="(hiddenField, i) in hiddenLocalFields"
                        :key="i"
                        :field="hiddenField"
                    />
                    <v-container grid-list-lg pa-0>
                        <v-layout wrap>
                            <v-flex v-bind="colCount()" v-for="(field, i) in localFields" :key="i">
                                <form-field
                                    :field="field"
                                    :errors="errors"
                                    :old-inputs="oldInputs"
                                />
                            </v-flex>
                            <v-flex v-bind="colCount()" v-if="newField">
                                <v-dialog width="500">
                                    <template v-slot:activator="{ on }">
                                        <v-btn
                                            v-on="on"
                                            color="primary"
                                            outlined
                                            rounded
                                            height="56"
                                            block
                                        >
                                            <v-icon left>mdi-plus</v-icon>Добавить новое поле
                                        </v-btn>
                                    </template>
                                    <v-card>
                                        <v-toolbar flat color="primary" dark>
                                            <v-toolbar-title class="font-weight-regular">Новое Поле</v-toolbar-title>
                                        </v-toolbar>
                                        <v-card-text class="py-6">
                                            <form-field
                                                :field="{
                                                    type: 'string',
                                                    name: 'name',
                                                    label: 'Название',
                                                }"
                                            />
                                            <form-field
                                                :field="{
                                                    type: 'string',
                                                    name: 'abbreviation',
                                                    label: 'Аббревиатура',
                                                }"
                                            />
                                            <form-field
                                                :field="{
                                                    type: 'select',
                                                    name: 'type_id',
                                                    label: 'Тип поля',
                                                    items: fieldTypes,
                                                    itemText: 'label'
                                                }"
                                            />
                                            <form-field
                                                :field="{
                                                    type: 'boolean',
                                                    name: 'required',
                                                    label: 'Обязательное поле',
                                                }"
                                            />
                                        </v-card-text>
                                    </v-card>
                                </v-dialog>
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-card-text>
                <v-card-actions>
                    <!--//TODO Add dynamic actions -->
                    <v-btn color="primary" text v-if="dialog" @click="show = false">Отмена</v-btn>
                    <v-spacer></v-spacer>
                    <v-btn color="primary" type="submit" @click="submit">Добавить</v-btn>
                </v-card-actions>
            </v-card>
        </v-form>
    </component>
</template>

<script>
function* colCountIterator(colCount) {
    if (!Array.isArray(colCount)) return;

    const colCountLen = colCount.length;

    let i = 0;
    while (i < colCountLen) {
        let xsVal = Math.round(12 / colCount[i]);

        let colPerRow = 0;

        do {
            yield xsVal;
            colPerRow += xsVal;
        } while (colPerRow / 12 !== 1);

        if (i == colCountLen - 1) {
            i = 0;
        } else {
            i++;
        }
    }
}
export default {
    props: {
        newField: {
            required: false,
            default: false
        },
        title: {
            type: String,
            required: true
        },
        fields: {
            type: Array,
            required: true
        },
        dialog: Boolean,
        actionUrl: String,
        method: String,
        width: String,
        activatorEventName: String,
        errors: null,
        oldInputs: null,
        fieldsPerRows: {
            type: Array,
            default: () => {
                return [1];
            },
            required: false
        }
    },
    data() {
        return {
            fieldTypes: [],
            show: this.formHasErrors() ? true : false,

            hiddenLocalFields: [
                {
                    type: "input",
                    name: "_token",
                    value: window.Laravel.csrf_token
                },
                { type: "input", name: "_method", value: this.method },
                ...this.extractFields(this.fields, "hidden")
            ],
            localFields: this.extractFields(this.fields, "shown"),

            fieldPerRowIterator: colCountIterator(this.fieldsPerRows)
        };
    },
    created() {
        if (this.newField) {
            axios.get(this.appPath("/api/fieldtypes")).then(res => {
                console.log(res.data);

                this.fieldTypes = res.data;
            });
        }

        this.addFormsEventActivator();

        this.processLaravelOldInputs();
    },
    beforeUpdate() {
        this.fieldPerRowIterator = colCountIterator(this.fieldsPerRows);
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
            this.hiddenLocalFields = [
                ...this.hiddenLocalFields,
                ...this.extractFields(fields, "hidden")
            ];
            this.localFields = [
                ...this.localFields,
                ...this.extractFields(fields, "shown")
            ];
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
        },
        extractFields(arr, type) {
            switch (type) {
                case "shown":
                    return arr.filter(item => item.type !== "input");
                case "hidden":
                    return arr.filter(item => item.type === "input");
                default:
                    return arr;
            }
        },
        colCount() {
            let xs = {};

            let nextValue = this.fieldPerRowIterator.next().value;

            xs["xs" + nextValue] = true;

            return xs;
        }
    },
    computed: {
        formWrapper() {
            if (!this.dialog) {
                return {
                    component: "div",
                    props: {}
                };
            }

            return {
                component: "v-dialog",
                props: {
                    "max-width": this.width || "600"
                }
            };
        }
    },
    watch: {
        fields(val) {
            this.localFields = this.extractFields(val, "shown");
        }
    }
};
</script>

<style>
</style>
