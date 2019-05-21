<template>

    <v-dialog
    v-model="dialog"
    max-width="700"
    >
        <template v-slot:activator="{ on }">
            <v-fab-transition>
                <v-btn
                v-on="on"
                dark
                fab
                fixed
                bottom
                right
                small
                color="primary"
                >
                    <v-icon>add</v-icon>
                </v-btn>
            </v-fab-transition>
        </template>

        <v-card>
            <v-toolbar flat color="primary" dark>
                <v-toolbar-title class="font-weight-regular">Новая задача</v-toolbar-title>
            </v-toolbar>

            <v-card-text>

                <v-text-field
                    label="Название"
                ></v-text-field>

                <v-textarea
                    rows="3"
                    label="Указания"
                ></v-textarea>

                <user-selector :employees="employees" label="taskAssignee"></user-selector>

                
                <v-subheader class="px-0 caption">Приоритет</v-subheader>
                
                <v-toolbar dense flat color="white" class="priority">
                    <v-btn-toggle 
                    v-model="task.priority" 
                    class="grey lighten-3"
                    >
                        <v-btn 
                        v-for="(priority,key) in priorities"
                        :key="'priority'+key"  
                        :value="key"
                        flat
                        >
                            {{priority.label}}
                        </v-btn>
                    </v-btn-toggle>
                </v-toolbar>

                <v-subheader class="px-0 caption">Планирование времени на задачу</v-subheader>
                
                
            </v-card-text>

            <v-card-actions>
                <v-btn
                color="grey darken-1"
                flat
                @click="dialog = false"
                >
                    Отмена
                </v-btn>

                <v-spacer></v-spacer>
                
                <v-btn
                color="primary"
                flat
                @click="dialog = false"
                >
                    Добавить
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
    
</template>

<script>
export default {
    props:['employees'],
    data(){
        return {
            task:{
                title:null,
                priority:1
            },
            dialog:false,
            priorities:[
				{label:"Низкий",icon:"trending_down",color:"green"},
				{label:"Средний",icon:"trending_flat",color:"blue"},
				{label:"Высокий",icon:"trending_up",color:"red"}
			]
        }
    }
}
</script>

<style>
.priority .v-toolbar__content{
    padding-left: 0;
}
.priority .v-btn--active{
    background-color: #b8cf41;
}
.priority .v-btn--active .v-btn__content{
    color: white;
}
</style>
