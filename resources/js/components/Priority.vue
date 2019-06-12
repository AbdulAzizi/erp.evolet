<template>
    <div>
        <v-tooltip top v-if="icon">
            <template v-slot:activator="{ on:tooltip }">
                <v-icon v-on="{ ...tooltip }" :color="priorities[id].color" :class="classes">flag</v-icon>
            </template>
            <span>{{priorities[id].label}} Приоритет</span>
        </v-tooltip>
        <v-list-tile avatar v-else>
            <v-list-tile-avatar>
                <v-icon :color="priorities[id].color" :class="classes">flag</v-icon>
            </v-list-tile-avatar>
            <v-list-tile-content>
                <v-list-tile-title>{{priorities[id].label}}</v-list-tile-title>
                <v-list-tile-sub-title>Преоритет</v-list-tile-sub-title>
            </v-list-tile-content>
        </v-list-tile>
    </div>
</template>

<script>
export default {
    props:{
        id:{
            required:true
        },
        classes:{
            required:false
        },
        icon:{
            required:false,
            type: Boolean,
            default: false
        }
    },
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
