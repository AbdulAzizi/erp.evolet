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
                :title="form.name"
                activatorEventName="addProduct"
                actionUrl="/products"
                method="post"
            ></dynamic-form>
        </div>

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
                { text: "Промо Компания", value: "pc", class:["primary", "table-header"] },
                { text: "Страна", value: "country", class:["primary", "table-header"] }
            ],
            preparedItems:[]
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
        let fieldsHeaders = this.items[0].fields.map( function( field ){
            return { text : field.label , value: field.label, class:["primary", "table-header"] }    
        });
        // merge headers
        this.headers = [ ...this.headers, ...fieldsHeaders ];

        // change the structure of items
        this.preparedItems = this.items.map( function( item ){

            let preparedFields = {};
            
            for (const field of item.fields) {
                preparedFields[field.label] = field.pivot.value; 
            }
            
            return { country: item.project.country.name, pc: item.project.pc.name, ...preparedFields }    
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
.table-header{
    /* white-space: nowrap; */
}
</style>
