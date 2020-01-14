<template>
    <v-container>
        <dynamic-form
            width="800"
            :fieldsPerRows="[2]"
            :fields="prepareFields(form.fields)"
            :title="form.label"
            :actionUrl=" appPath(`factories`)"
            method="post"
            :newField="true"
        ></dynamic-form>
    </v-container>
</template>
<script>
export default {
    props: {
        form:{}
    },
    created(){
        console.log(this.form);
    }
};
</script>
<style>
</style>