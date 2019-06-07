<template>
<div>
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

        <v-data-table
        :headers="headers"
        :items="tasks"
        class="elevation-1"
        item-key="id"
        >
            <template v-slot:items="props">
                <tr @click="displayTask(props.item)">
                    <td>{{ props.item.title }}</td>
                    <td v-text="props.item.status ? 'Сделанно' : 'Не сделанно' "></td>
                    <td><priority :id="props.item.priority"></priority></td>
                    <td>
                        <!-- {{ moment(moment(props.item.planned_time).diff(zero)).format('DDDд kч mм') }} -->
                        <span v-if="moment.duration(moment(parseInt(props.item.planned_time)).valueOf()).days()">{{ moment.duration(moment(parseInt(props.item.planned_time)).valueOf()).days() }}д</span>
                        <span v-if="moment.duration(moment(parseInt(props.item.planned_time)).valueOf()).hours()">{{ moment.duration(moment(parseInt(props.item.planned_time)).valueOf()).hours() }}ч</span>
                        <span v-if="moment.duration(moment(parseInt(props.item.planned_time)).valueOf()).minutes()">{{ moment.duration(moment(parseInt(props.item.planned_time)).valueOf()).minutes() }}м</span>
                    </td>
                    <td>{{props.item.deadline}}</td>
                    <td>
                        <v-avatar size="40">
                            <v-tooltip top v-if="props.item.from.user">
                                <template v-slot:activator="{ on:tooltip }">
                                    <img v-on="{ ...tooltip }"  :src="photo(props.item.from.user.img) " alt="avatar">
                                </template>
                                <span>{{ props.item.from.user.name }} {{ props.item.from.user.surname }}</span>
                            </v-tooltip>

                            <v-tooltip top v-else>
                                <template v-slot:activator="{ on:tooltip }">
                                    <img v-on="{ ...tooltip }" :src="photo('green-solo-logo.svg') " alt="avatar">
                                </template>
                                <span>Систеема</span>
                            </v-tooltip>
                        </v-avatar>
                    </td>
                </tr>
            </template>

        </v-data-table>

    
</div>
</template>

<script>
import moment from 'moment';

export default {
    props:['tasks'],
    data(){
        return {
            moment:moment,
            headers: [
                { text: 'Задача', value: 'title' },
                { text: 'Статус', value: 'status' },
                { text: 'Приоритет', value: 'priority' },
                { text: 'Время на задачу', value: 'planned_time' },
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
            activeTab:null,
            zero:moment('1970-01-01 00:00:00'),
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
