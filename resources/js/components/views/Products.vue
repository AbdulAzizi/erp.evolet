<template>
    <div>
        <div v-if="form">
            <v-fab-transition>
                <v-btn @click="addProduct" dark fab fixed bottom right small color="primary">
                    <v-icon>mdi-plus</v-icon>
                </v-btn>
            </v-fab-transition>
            <dynamic-form 
            :fields="form.fields" 
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
        height="92vh"
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
        items:{
            required: true
        }
    },
    data() {
        return {
            dialog: false,
            headers: [
                { text: "Промо Компания", value: "pc.name", class:["primary", "table-header"] },
                { text: "Страна", value: "country.name", class:["primary", "table-header"] }
            ],
            preparedItems:[]
        };
    },
    methods: {
        addProduct() {
            Event.fire("addProduct");
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
            
            return { country: item.country, pc: item.pc, ...preparedFields }   
        });
        
    },
    computed: {
        preparedFields() {
            if (this.form)
                return this.form.fields.map(field => {
                    field["rules"] = field.pivot.required ? ["required"] : [true];
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
