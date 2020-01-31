<template>
    <div class="products">
        <v-tabs v-model="tab" background-color="secondary" color="primary" dark>
            <p
                class="pa-4 white--text"
                v-if="project"
            >{{project.country.name}} · {{project.pc.name}}</p>
            <v-spacer></v-spacer>
            <v-tab href="#products">Продукты</v-tab>
            <v-tab href="#participants" v-if="participants">Участники</v-tab>
            <v-btn
                v-if="canCreate()"
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
                    height="calc(100vh - 96px)"
                    dense
                >
                    <template v-slot:item="{ item }">
                        <tr @click="showProduct(item)">
                            <template v-for="(values,index) in item">
                                <td v-if="index!='id'" :key="index">
                                    <span v-if="values.length < 2">{{values[0]}}</span>
                                    <v-chip
                                        outlined
                                        class="primary mr-1"
                                        small
                                        v-else
                                        v-for="(value,valueIndex) in values"
                                        :key="valueIndex"
                                    >{{value}}</v-chip>
                                </td>
                            </template>
                        </tr>
                    </template>
                </v-data-table>
            </v-tab-item>
            <v-tab-item value="participants" v-if="participants">
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
            required: false
        },
        project: {
            required: false
        }
    },
    data() {
        return {
            tab: null,
            headers: [
                {
                    text: "Страна",
                    value: "country",
                    class: ["primary", "table-header"]
                },
                {
                    text: "Промо Компания",
                    value: "pc",
                    class: ["primary", "table-header"]
                }
            ],
            preparedItems: [],
            productDialog: false,
            selectedProduct: null,
            usersThatCanCreate: ["Куратор Портфеля ПК стран", "ПК", "НО"]
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
        },
        showProduct(product) {
            window.location.href = `products/${product.id}`;
        },
        canCreate() {
            let canCreate = false;

            this.auth.positions.forEach(position => {
                if (this.usersThatCanCreate.includes(position.label) && this.project)
                    canCreate = true;
            });

            return canCreate;
        }
    },
    created() {
        if (this.items.length === 0) return;

        // prepare headers
        let fieldsHeaders = this.items[0].fields.map(function(field) {
            return {
                text: field.abbreviation,
                value: field.label,
                class: ["primary", "table-header"]
            };
        });
        // merge headers
        this.headers = [...this.headers, ...fieldsHeaders];

        // change the structure of items
        this.items.forEach(item => {
            let preparedFields = {};

            for (const field of item.fields) {
                if (preparedFields[field.label]) {
                    preparedFields[field.label].push(field.pivot.value);
                } else {
                    preparedFields[field.label] = [field.pivot.value];
                }
            }

            this.preparedItems.push({
                id: item.id,
                country: item.project ? [item.project.country.name] : [],
                pc: item.project ? [item.project.pc.name] : [],
                ...preparedFields
            });
        });
    }
};
</script>

<style>
.products .table-header,
tr {
    white-space: nowrap;
}
</style>
