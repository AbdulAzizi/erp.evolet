<template>
    <v-data-table
        data
        hide-default-footer
        :headers="headers"
        :items="preparedItems"
        sort-by="calories"
        class="elevation-1 factoryProducts"
        :items-per-page="-1"
    >
        <template v-slot:top>
            <v-toolbar flat color="white">
                <v-toolbar-title>Products</v-toolbar-title>
                <v-divider class="mx-4" inset vertical></v-divider>
                <v-spacer></v-spacer>
                <v-dialog v-model="dialog" max-width="500px">
                    <template v-slot:activator="{ on }">
                        <v-btn color="primary" outlined dark class="mb-2" v-on="on">Add Product</v-btn>
                    </template>
                    <v-card v-if="dialog">
                        <v-card-title>
                            <span class="headline">{{ formTitle }}</span>
                        </v-card-title>

                        <v-card-text>
                            <v-container>
                                <v-row>
                                    <v-col cols="12" sm="6" md="4">
                                        <v-text-field v-model="editedItem.name" label="Name"></v-text-field>
                                    </v-col>
                                    <v-col cols="12" sm="6" md="4">
                                        <v-text-field v-model="editedItem.surname" label="Surname"></v-text-field>
                                    </v-col>
                                    <v-col cols="12" sm="6" md="4">
                                        <v-text-field v-model="editedItem.age" label="Age"></v-text-field>
                                    </v-col>
                                </v-row>
                            </v-container>
                        </v-card-text>

                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="blue darken-1" text @click="close">Cancel</v-btn>
                            <v-btn color="blue darken-1" text @click="save">Save</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
            </v-toolbar>
        </template>
        <template v-slot:item.action="{ item }">
            <v-icon small class="mr-2" @click="editItem(item)">mdi-pencil</v-icon>
            <v-icon small @click="deleteItem(item)">mdi-delete</v-icon>
        </template>
        <template v-slot:no-data>
            <v-btn color="primary" @click="initialize">Reset</v-btn>
        </template>
    </v-data-table>
</template>
<script>
export default {
    props: {
        items: {}
    },
    data() {
        return {
            headers: [
                // { text: "Name", value:"МНН" },
                // { text: "Surname", value: "Ф" },
                // { text: "Age", value: "Д" },
                // { text: "Actions", value: "ОПУ" }
            ],
            // items: [
            //     { name: "Ahmad", surname: "Milazi", age: 20 },
            //     { name: "AbdulAziz", surname: "Nurov", age: 27 },
            //     { name: "Anvar", surname: "Dzhaborov", age: 30 }
            // ],
            dialog: false,
            formTitle: null,
            editedItem: {},
            editedIndex: -1,
            preparedItems:[]
        };
    },
    created(){
        this.headers = this.items[0].fields.map(function(field) {
            return {
                text: field.abbreviation,
                value: field.label
            };
        });
        this.preparedItems = this.items.map(function(item) {
            let preparedFields = {};

            for (const field of item.fields) {
                preparedFields[field.label] = field.pivot.value;
            }

            return preparedFields;
        });
    },
    methods: {
        close() {
            this.dialog = false;
        },
        save() {
            if (this.editedIndex > -1) {
                this.items[this.editedIndex] = this.editedItem;
            } else {
                this.items.push(this.editedItem);
            }
            this.close();
            this.clear();
        },
        clear() {
            this.editedItem = {};
            this.editedIndex = -1;
        },
        editItem(item) {
            this.editedItem = item;
            this.editedIndex = this.items.indexOf(item);
            this.dialog = true;
        },
        deleteItem(item) {
            const index = this.items.indexOf(item);
            confirm("Are you sure you want to delete this item?") &&
                this.items.splice(index, 1);
        }
    }
};
</script>
<style>
.factoryProducts 
td {
    white-space: normal ;
}
</style>