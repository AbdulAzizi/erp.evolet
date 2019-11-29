<template>
    <div>
        <dynamic-form :fields="preparedFields" title="Forms" :fieldsPerRows="[2]"></dynamic-form>
        <!-- <v-autocomplete
            label="Components"
            :items="[
              {id:1,name:'first'},
              {id:2,name:'second'},
              {id:3,name:'third'},
            ]"
            itemText="name"
            itemValue="id"
            :value="2"
        ></v-autocomplete>-->
    </div>
</template>

<script>
export default {
    props: ["product"],
    data() {
        return {
            localProduct: this.product,
            preparedForms: []
        };
    },
    computed: {
        preparedFields() {
            return this.localProduct.fields_with_lists.map(field => {
                return {
                    ...field,
                    rules:
                        field.pivot && field.pivot.required
                            ? ["required"]
                            : [true],
                    type:
                        field.type.name == "list"
                            ? "autocomplete"
                            : field.type.name,
                    value:
                        field.type.name == "list" ||
                        field.type.name == "many-to-many-list"
                            ? +field.pivot.value
                            : field.pivot.value
                };
            });
        }
    },
    created() {
        // console.log(this.preparedFields);
    }
};
</script>