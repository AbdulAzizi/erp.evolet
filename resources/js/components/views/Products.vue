<template>
    <div>
        <div v-if="form">
            <v-fab-transition>
                <v-btn @click="addProduct" dark fab fixed bottom right small color="primary">
                    <v-icon>add</v-icon>
                </v-btn>
            </v-fab-transition>
            <dynamic-form
                :fields="preparedFields"
                :title="form.name"
                activatorEventName="addProduct"
                actionUrl="/products"
                method="post"
            ></dynamic-form>
        </div>

        <v-data-table
            :headers="headers"
            :items="items"
            class="elevation-1"
            item-key="id"
            hide-actions
        >
            <template v-slot:items="props">
                <tr @click="goTo(props.item)">
                    <td>{{ props.item.pc.name }}</td>
                    <td>{{ props.item.country.name }}</td>
                    <td
                        v-for="(field, index) in props.item.fields"
                        :key="'field-'+index"
                    >{{ field.pivot.value }}</td>
                </tr>
            </template>
        </v-data-table>
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
        }
    },
    data() {
        return {
            dialog: false,
            headers: [
                { text: "Промо Компания", value: "pc.name" },
                { text: "Страна", value: "country.name" }
            ]
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
        let fieldsHeaders = this.items[0].fields.map(function(field) {
            return { text: field.label, value: field.pivot.value };
        });

        console.log(fieldsHeaders);

        this.headers = [...this.headers, ...fieldsHeaders];
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
</style>
