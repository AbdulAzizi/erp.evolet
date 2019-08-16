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
                            : [true],
                    type:
                        field.type.name == "list"
                            ? "autocomplete"
                            : field.type.name
                };
            });
        }
    },
    created(){
        console.log(this.preparedFields);
        
    }
};
</script>
<style>
</style>