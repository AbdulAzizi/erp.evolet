<template>
    <v-container>
        <tasks-table :tasks="tasks" :employees="employees"></tasks-table>
    </v-container>
</template>

<script>
export default {
    props:['tasks','employees']
}
</script>

<style>

</style>
