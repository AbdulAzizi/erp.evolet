<template>
    <v-container>
        <dynamic-form
            width="800"
            :fieldsPerRows="[2]"
            :fields="preparedFields"
            :title="form.label"
            activatorEventName="addProduct"
            actionUrl="/products"
            method="post"
            :dialog="false"
        ></dynamic-form>
    </v-container>
</template>
<script>
export default {
    props: ["form"],
    computed: {
        preparedFields() {
            return this.form.fields.map(field => {
                return {
                    ...field,
                    rules:
                        field.pivot && field.pivot.required
                            ? ["required"]
                            : [],
                    type:
                        field.type.name == "list"
                            ? "autocomplete"
                            : field.type.name,
                    multiple: field.pivot && field.pivot.multiple ? true : false
                };
            });
        }
    },
    created(){
        // console.log(this.preparedFields);
        
    }
};
</script>
<style>
</style>