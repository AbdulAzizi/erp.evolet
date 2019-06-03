<template>
    <v-dialog
    v-model="assigneeDialog"
    width="500"
    >
        <template v-slot:activator="{ on:dialog }">
            <v-tooltip top>
                <template v-slot:activator="{ on:tooltip }">
                    
                    <v-btn v-on="{ ...tooltip, ...dialog }" flat round min-width="0" style="min-width:0" class="ma-0 grey--text px-2 text--darken-1">
                        <v-icon :color="assignees.length ? 'primary' : '' " >person</v-icon>

                        <v-avatar size="30" v-for="(assignee, key) in assignees" :key="'assignee-'+key">
                            <img :src="'../img/'+assignee.user.img+'.jpg'">
                        </v-avatar>
                    </v-btn>

                    <input type="hidden" name="assignees" :value="JSON.stringify(pluck(assignees, 'id'))">
                
                </template>
                <span>Исполнители</span>
            </v-tooltip>
        </template>
        <v-card>
            <v-card-text>
                <user-selector 
                :employees="employees"
                name="assignees" 
                label="Исполнители" 
                icon="person"
                hint="У каждого исполнителя будет своя отдельная задача"
                ></user-selector>
            </v-card-text>
        </v-card>
    </v-dialog>

</template>

<script>
export default {
    props:['employees'],
    data(){
        return{
            assigneeDialog:false,
            assignees:[],
        }
    },
    created(){
        Event.listen('assignees',(data)=>{
            this.assignees = data;
        });
    }
}
</script>

<style>

</style>
