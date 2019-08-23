<template>
    <div>
        <v-tabs v-model="tab" right background-color="secondary" color="primary" dark>
            <v-tab href="#products">Продукты</v-tab>
            <v-tab href="#participants">Участники</v-tab>
            <v-btn
                small
                class="align-self-center mx-4 primary"
                :href="appPath('products/create?'+
                getParam(['pc_id','country_id','project_id']))"
            >Новый продукт</v-btn>
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
                                    <v-list-item
                                        v-for="(participant, i) in participants.project_participant"
                                        :key="i"
                                    >
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
        getParam(params) {
            let url = new URL(window.location.href);
            return params
                .map(function(param) {
                    return param + "=" + url.searchParams.get(param);
                })
                .join("&");
        }
    },
    created() {
        if (this.items.length != 0) {
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
        }
    }
};
</script>

<style>
.table-header,
tr {
    white-space: nowrap;
}
</style>
