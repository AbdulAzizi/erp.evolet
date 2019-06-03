<template>
    <v-tooltip top>
        <template v-slot:activator="{ on:tooltip }">
            <v-icon v-on="{ ...tooltip }" :color="priorities[id].color">flag</v-icon>
        </template>
        <span>{{priorities[id].label}} Приоритет</span>
    </v-tooltip>
</template>

<script>
export default {
    props:['id'],
    data(){
        return {
            priorities:[
                { id:0, label:"Низкий", color:"light-blue lighten-4" },
                { id:1, label:"Средний", color:"amber lighten-1" },
                { id:2, label:"Высокий", color:"red" }
            ]
        }
    }
}
</script>

<style>

</style>
