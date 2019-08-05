<template>
    <div>
        <div v-if="form">
            <v-fab-transition>
                <v-btn @click="addProduct" dark fab fixed bottom large right color="primary">
                    <v-icon>mdi-plus</v-icon>
                </v-btn>
            </v-fab-transition>
            <dynamic-form
                :fields="preparedFields"
                :title="form.label"
                activatorEventName="addProduct"
                actionUrl="/products"
                method="post"
            ></dynamic-form>
        </div>

        <v-tabs v-model="tab" right background-color="secondary" color="primary" dark>
            <v-tab href="#products">Продукты</v-tab>
            <v-tab href="#participants">Участники</v-tab>
        </v-tabs>
        <v-tabs-items v-model="tab" class="transparent">
            <v-tab-item value="products">
                <v-data-table
                    :headers="headers"
                    :items="preparedItems"
                    item-key="id"
                    hide-default-footer
                    :items-per-page="-1"
                    :fixed-header="true"
                    height="calc(100vh - 48px)"
                    dense
                />
            </v-tab-item>
            <v-tab-item value="participants">
                <v-container>
                    <v-layout justify-center>
                        <v-card class="mx-auto" max-width="300" tile>
                            <v-list disabled>
                                <v-list-item-group color="primary">
                                    <v-list-item v-for="(participant, i) in participants.project_participant" :key="i">
                                        <v-list-item-avatar>
                                            <v-img :src="photo(participant.img)"></v-img>
                                        </v-list-item-avatar>
                                        <v-list-item-content>
                                            <v-list-item-title>{{participant.role.name}}</v-list-item-title>
                                            <v-list-item-subtitle>{{participant.participant.name}} {{participant.participant.surname}}</v-list-item-subtitle>
                                        </v-list-item-content>
                                    </v-list-item>
                                </v-list-item-group>
                            </v-list>
                        </v-card>
                    </v-layout>
                </v-container>
            </v-tab-item>
        </v-tabs-items>
    </div>
</template>

<script>
export default {
    props: {
        form: {
            required: false
        },
        items: {
            required: true
        },
        participants: {
            required: true
        }
    },
    data() {
        return {
            tab: null,
            dialog: false,
            headers: [
                {
                    text: "Промо Компания",
                    value: "pc",
                    class: ["primary", "table-header"]
                },
                {
                    text: "Страна",
                    value: "country",
                    class: ["primary", "table-header"]
                }
            ],
            preparedItems: []
        };
    },
    methods: {
        addProduct() {
            Event.fire("addProduct");
        },
        getDynamicFieldsType(laravelType) {
            //TODO Make a normal adapter or refactor to use same types in vue and laravel
            switch (laravelType) {
                case "list":
                    return "autocomplete";
                    break;
                default:
                    return laravelType;
                    break;
            }
        }
    },
    created() {
        // prepare headers
        let fieldsHeaders = this.items[0].fields.map(function(field) {
            return {
                text: field.label,
                value: field.label,
                class: ["primary", "table-header"]
            };
        });
        // merge headers
        this.headers = [...this.headers, ...fieldsHeaders];

        // change the structure of items
        this.preparedItems = this.items.map(function(item) {
            let preparedFields = {};

            for (const field of item.fields) {
                preparedFields[field.label] = field.pivot.value;
            }

            return {
                country: item.project.country.name,
                pc: item.project.pc.name,
                ...preparedFields
            };
        });
    },
    computed: {
        preparedFields() {
            if (this.form)
                return this.form.fields.map(field => {
                    field["rules"] =
                        field.pivot && field.pivot.required
                            ? ["required"]
                            : [true];

                    field["type"] = this.getDynamicFieldsType(field.type.name);

                    return field;
                });
        }
    }
};
</script>

<style>
.table-header {
    /* white-space: nowrap; */
}
</style>
