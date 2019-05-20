<template>
    <v-sheet class="white grey--text text--darken-2">
        <!-- <v-container grid-list-xs class="pa-0">
            <v-layout column>
                <v-flex>
                    {{task.title}}
                </v-flex>
                <v-flex>
                    actions
                </v-flex>
            </v-layout>
        </v-container> -->
        <v-card flat>
            <v-card-text>
                {{task.title}}
            </v-card-text>
            <v-card-actions class="px-3 pt-0 ">

                <v-tooltip bottom v-if="task.high_priority">
                    <template v-slot:activator="{ on }">
                        <v-icon v-on="on" color="orange darken-2" class="mr-1">whatshot</v-icon>
                    </template>
                    <span>Important Task</span>
                </v-tooltip>

                <v-tooltip bottom>
                    <template v-slot:activator="{ on }">
                        <v-icon v-on="on" class="mr-1">schedule</v-icon>
                        <!-- <span v-on="on" class="mr-2">{{task.deadline}}</span> -->
                    </template>
                    <span>Deadline: {{task.deadline}}</span>
                </v-tooltip>

                <v-tooltip bottom>
                    <template v-slot:activator="{ on }">

                        <v-avatar v-on="on" slot="activator" class="">
                            <img :src="'../img/'+task.responsible.user.img+'.jpg'" alt="avatar">
                        </v-avatar>

                    </template>
                    <span>Executer</span>
                </v-tooltip>

            </v-card-actions>
        </v-card>
    </v-sheet>
</template>

<script>
export default {
    props: {
        task: {
            type: Object,
            required: true
        }
    },
    data(){
        return{
        }
    }
}
</script>

<style>

</style>
