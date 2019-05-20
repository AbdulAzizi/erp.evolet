<template>
    <div>
        
        <v-tabs
        v-model="activeTab"
        color="primary"
        dark
        slider-color="white"
        >
            <v-tab
            v-for="(taskCategory,key) in alltasks"
            :key="'tab'+key "
            ripple
            >
                {{taskCategory.name}}
            </v-tab>

            <v-spacer></v-spacer>

            <v-btn icon>
                <v-icon>view_list</v-icon>
            </v-btn>

            <v-tab-item
            v-for="(taskCategory,key) in alltasks"
            :key="'tabItem'+key"
            class="grey lighten-4 pt-2"
            >
                <!-- <v-sheet tile class="grey lighten-4 pb-2 px-2" v-for="(task,key) in taskCategory.data" :key="'task'+key">
                    <task :task="task"></task>
                </v-sheet> -->
                <v-data-table
                :headers="headers"
                :items="taskCategory.data"
                class="elevation-1"
                item-key="id"
                >
                    <template v-slot:items="props">
                        <tr @click="displayTask(props.item)">
                            <td>{{ props.item.title }}</td>
                            <td>{{ props.item.description }}</td>
                            <td v-text="props.item.status ? 'Сделанно' : 'Не сделанно' "></td>
                            <td v-text="props.item.high_priority ? 'Высокий' : 'Обычный' "></td>
                            <td>{{props.item.deadline}}</td>
                            <td v-text="props.item.from.user ? `${props.item.from.user.name} ${props.item.from.user.surname}` : 'Система' "></td>
                        </tr>
                    </template>

                </v-data-table>

            </v-tab-item>
            
        </v-tabs>

        <v-dialog
        v-model="taskDialog"
        max-width="700"
        >
            <v-card>
                <v-layout>
                    <V-flex xs8>
                        <v-card-title>
                            <h3 class="headline mb-0">{{selectedTask.title}}</h3>
                        </v-card-title>
                        <v-card-text>
                            Hello Dude
                        </v-card-text>
                    </V-flex>

                    <v-flex xs4>

                        <v-list subheader>
                            <v-subheader>Участники</v-subheader>

                            <v-list-tile avatar>
                                <v-list-tile-avatar>
                                    
                                    <v-img
                                    v-if="selectedTask.from.user && selectedTask.from.user.img"
                                    class="elevation-3"
                                    :src="'../img/'+selectedTask.from.user.img+'.jpg'" alt="avatar"
                                    ></v-img>

                                    <v-img
                                    class="elevation-3"
                                    v-else
                                    :src="'../img/green-solo-logo.svg'" 
                                    alt="avatar"
                                    ></v-img>

                                    <!-- <img
          v-else
          :src="'../img/green-solo-logo.svg'"
          style="border-radius:0;"
          alt="avatar"
          class="pa-1 pt-2"
        > -->

                                </v-list-tile-avatar>
                                <v-list-tile-content>
                                    <v-list-tile-title v-if="selectedTask.from.user">{{selectedTask.from.user.name}} {{selectedTask.from.user.surname}}</v-list-tile-title>
                                    <v-list-tile-title v-else>Система</v-list-tile-title>
                                    <v-list-tile-sub-title>Постановщик</v-list-tile-sub-title>
                                </v-list-tile-content>
                            </v-list-tile>
<!-- 
                            <v-list-tile avatar>
                                <v-list-tile-avatar>
                                    <v-img
                                    class="elevation-3"
                                    :src="'../img/'+selectedTask.assignee.image+'.jpg'" alt="avatar"
                                    ></v-img>
                                </v-list-tile-avatar>
                                <v-list-tile-content>
                                    <v-list-tile-title>{{selectedTask.assignee.name}} {{selectedTask.assignee.surname}}</v-list-tile-title>
                                    <v-list-tile-sub-title>Исполнитель</v-list-tile-sub-title>
                                </v-list-tile-content>
                            </v-list-tile> -->

                            <v-subheader>Параметры</v-subheader>
                            
                            <!-- <v-list-tile avatar>
                                <v-list-tile-avatar>
                                    <v-icon class="blue white--text">assignment</v-icon>
                                </v-list-tile-avatar>
                                <v-list-tile-content>
                                    <v-list-tile-title>{{ moment(selectedTask.created_at).format('D MMMM  Y') }}</v-list-tile-title>
                                    <v-list-tile-sub-title>Дата создания</v-list-tile-sub-title>
                                </v-list-tile-content>
                            </v-list-tile> -->

                            <!-- <v-list-tile avatar>
                                <v-list-tile-avatar>
                                    <v-icon :class="priorities[selectedTask.priority].color + ' white--text'">
                                        {{priorities[selectedTask.priority].icon}}
                                    </v-icon>
                                </v-list-tile-avatar>
                                <v-list-tile-content>
                                    <v-list-tile-title>{{priorities[selectedTask.priority].label}}</v-list-tile-title>
                                    <v-list-tile-sub-title>Преоритет</v-list-tile-sub-title>
                                </v-list-tile-content>
                            </v-list-tile> -->

                            <!-- <v-list-tile avatar>
                                <v-list-tile-avatar>
                                    <v-icon :class="selectedTask.status.color + ' white--text'">
                                    </v-icon>
                                </v-list-tile-avatar>
                                <v-list-tile-content>
                                    <v-list-tile-title>
                                        {{ selectedTask.status.title }}
                                    </v-list-tile-title>
                                    <v-list-tile-sub-title>Статус</v-list-tile-sub-title>
                                </v-list-tile-content>
                            </v-list-tile> -->

                        </v-list>
                    </v-flex>
                </v-layout>
            </v-card>
        </v-dialog>

    </div>           
</template>

<script>
export default {
    props:['alltasks'],
    data(){
        return {
            headers: [
                { text: 'Задача', value: 'title' },
                { text: 'Описание', value: 'description' },
                { text: 'Статус', value: 'status' },
                { text: 'Приоритет', value: 'high_priority' },
                { text: 'Дедлайн', value: 'deadline' },
                { text: 'От', value: 'from', sort: false},
            ],
            taskDialog:false,
            selectedTask:{
                title:null,
                description:null,
                status:null,
                high_priority:null,
                spent_time:null,
                planned_time:null,
                deadline:null,
                responsible_id:null,
                from:{
                    user:{
                        name:null,
                        surname:null,
                        img:null
                    }
                },
            },
            activeTab:null
        }
    },
    methods:{
        displayTask( task ){
            this.selectedTask = task;
            this.taskDialog = true;
        }
    }
}
</script>

<style>

</style>
