<template>
    <v-container fluid>

        <tasks-table :tasks="tasks"></tasks-table>
        <tasks-add 
        :employees="employees" 
        :errors="errors"
        :tags="tags"
        ></tasks-add>

    </v-container>
</template>

<script>
export default {
    props:['tasks','employees','errors','tags']
}
</script>

<style>

</style>
