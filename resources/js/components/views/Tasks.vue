<template>
    <v-container fluid>

        <tasks-table :tasks="tasks"></tasks-table>

        <tasks-add 
        :users="users" 
        :errors="errors"
        :tags="tags"
        />

    </v-container>
</template>

<script>
export default {
    props:['tasks','users','errors','tags']
}
</script>

<style>

</style>
