<template>
<div>
    <v-dialog
        v-model="taskDialog"
        max-width="700"
        lazy
        >
            <v-card>
                <v-layout>
                    <V-flex xs8>
                        <v-card-title>
                            <h3 class="headline mb-0">{{selectedTask.title}}</h3>
                        </v-card-title>
                        <v-card-text>
                            {{selectedTask.description ? selectedTask.description : ''}}
                        </v-card-text>
                    </V-flex>

                    <v-flex xs4>
                        

                        <v-list subheader>
                            
                            <v-subheader>Участники</v-subheader>
                            
                            <avatars-set 
                            :watchers="selectedTask.watchers"
                            :assignee="selectedTask.responsible"
                            :from="selectedTask.from"
                            class="pl-3">
                            </avatars-set>


                            <v-subheader>Параметры</v-subheader>
                            
                            <v-list-tile avatar>
                                <v-list-tile-avatar>
                                    <v-icon >event</v-icon>
                                </v-list-tile-avatar>
                                <v-list-tile-content>
                                    <v-list-tile-title>{{ moment(selectedTask.deadline).format('D MMMM  Y') }}</v-list-tile-title>
                                    <v-list-tile-sub-title>Дедлайн</v-list-tile-sub-title>
                                </v-list-tile-content>
                            </v-list-tile>

                            <v-list-tile avatar>
                                <v-list-tile-avatar>
                                    <v-icon >event_available</v-icon>
                                </v-list-tile-avatar>
                                <v-list-tile-content>
                                    <v-list-tile-title>{{ moment(selectedTask.created_at).format('D MMMM  Y') }}</v-list-tile-title>
                                    <v-list-tile-sub-title>Дата добавления</v-list-tile-sub-title>
                                </v-list-tile-content>
                            </v-list-tile>

                            <v-list-tile avatar>
                                <v-list-tile-avatar>
                                    <v-icon>beenhere</v-icon>
                                </v-list-tile-avatar>
                                <v-list-tile-content>
                                    <v-list-tile-title>{{ selectedTask.status.name }}</v-list-tile-title>
                                    <v-list-tile-sub-title>Статус</v-list-tile-sub-title>
                                </v-list-tile-content>
                            </v-list-tile>

                            <v-list-tile avatar>
                                <v-list-tile-avatar>
                                    <v-icon>timelapse</v-icon>
                                </v-list-tile-avatar>
                                <v-list-tile-content>
                                    <v-list-tile-title>
<span v-if="moment.duration(moment(parseInt(selectedTask.planned_time)).valueOf()).days()">{{ moment.duration(moment(parseInt(selectedTask.planned_time)).valueOf()).days() }}д</span>
<span v-if="moment.duration(moment(parseInt(selectedTask.planned_time)).valueOf()).hours()">{{ moment.duration(moment(parseInt(selectedTask.planned_time)).valueOf()).hours() }}ч</span>
<span v-if="moment.duration(moment(parseInt(selectedTask.planned_time)).valueOf()).minutes()">{{ moment.duration(moment(parseInt(selectedTask.planned_time)).valueOf()).minutes() }}м</span></v-list-tile-title>
                                    <v-list-tile-sub-title>Время на задачу</v-list-tile-sub-title>
                                </v-list-tile-content>
                            </v-list-tile>

                            <priority :id="selectedTask.priority" classes=" lighten-3"></priority>
                            
                            <v-subheader v-if="selectedTask.tags.length">Теги</v-subheader>

                            <v-chip 
                            color="grey lighten-4" 
                            text-color="grey darken-1"
                            v-for="(tag, index) in selectedTask.tags" 
                            :key="'tag-'+index"
                            small
                            >
                                <v-avatar style="margin-left:-8px" class="mr-0">
                                    <v-icon class="subheading">local_offer</v-icon>
                                </v-avatar>
                                {{ tag.name }}
                            </v-chip>

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
        hide-actions
        >
            <template v-slot:items="props">
                <tr @click="displayTask(props.item)">
                    <td>{{ props.item.title }}</td>
                    <!-- <td v-text="props.item.status ? 'Сделанно' : 'Не сделанно' "></td> -->
                    <td><priority :id="props.item.priority" icon></priority></td>
                    <td>
                        <!-- {{ moment(moment(props.item.planned_time).diff(zero)).format('DDDд kч mм') }} -->
                        <span v-if="moment.duration(moment(parseInt(props.item.planned_time)).valueOf()).days()">{{ moment.duration(moment(parseInt(props.item.planned_time)).valueOf()).days() }}д</span>
                        <span v-if="moment.duration(moment(parseInt(props.item.planned_time)).valueOf()).hours()">{{ moment.duration(moment(parseInt(props.item.planned_time)).valueOf()).hours() }}ч</span>
                        <span v-if="moment.duration(moment(parseInt(props.item.planned_time)).valueOf()).minutes()">{{ moment.duration(moment(parseInt(props.item.planned_time)).valueOf()).minutes() }}м</span>
                    </td>
                    <td>{{props.item.deadline}}</td>
                    <td>
                        <v-avatar size="40">
                            <v-tooltip top v-if="props.item.from_type === 'App\\User'">
                                <template v-slot:activator="{ on:tooltip }">
                                    <img v-on="{ ...tooltip }"  :src="photo(props.item.from.img) " alt="avatar">
                                </template>
                                <span>{{ props.item.from.name }} {{ props.item.from.surname }}</span>
                            </v-tooltip>

                            <v-tooltip top v-else>
                                <template v-slot:activator="{ on:tooltip }">
                                    <img v-on="{ ...tooltip }" :src="photo('green-solo-logo.svg') " alt="avatar">
                                </template>
                                <span>Система</span>
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
            auth:window.Laravel.auth,
            headers: [
                { text: 'Задача', value: 'title' },
                // { text: 'Статус', value: 'status' },
                { text: 'Приоритет', value: 'priority' },
                { text: 'Время на задачу', value: 'planned_time' },
                { text: 'Дедлайн', value: 'deadline' },
                { text: 'От', value: 'from', sort: false},
            ],
            taskDialog:false,
            selectedTask:{
                title:null,
                description:null,
                status:{
                    name:null
                },
                high_priority:null,
                spent_time:null,
                planned_time:null,
                deadline:null,
                tags:[],
                responsible_id:null,
                responsible:{
                    user:{
                        name:null,
                        surname:null,
                        img:null
                    }
                },
                from:{
                    user:{
                        name:null,
                        surname:null,
                        img:null
                    }
                },
            },
            activeTab:null,
            zero:moment('1970-01-01 00:00:00')
        }
    },
    methods:{
        displayTask( task ){
            this.selectedTask = task;
            this.taskDialog = true;
            // console.log(this.selectedTask);
        }
    }
}
</script>

<style>

</style>
