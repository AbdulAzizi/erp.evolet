<template>
    <v-container fluid>

        <tasks-table :tasks="tasks"></tasks-table>
        <tasks-add :users="users" :errors="errors"></tasks-add>

    </v-container>
</template>

<script>
export default {
    props:['tasks','users','errors']
}
</script>

<style>

</style>
