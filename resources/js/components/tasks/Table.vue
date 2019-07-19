<template>
    <div>
        <v-dialog v-model="taskDialog" max-width="700">
            <v-card>
                <v-layout>
                    <V-flex xs8>
                        <v-card-title>
                            <h3 class="headline mb-0">{{selectedTask.title}}</h3>
                        </v-card-title>
                        <v-card-text>{{selectedTask.description ? selectedTask.description : ''}}</v-card-text>
                    </V-flex>

                    <v-flex xs4>
                        <v-list subheader>
                            <v-subheader>Участники</v-subheader>

                            <avatars-set
                                :watchers="selectedTask.watchers"
                                :assignee="selectedTask.responsible"
                                :from="selectedTask.from"
                                class="pl-3"
                            ></avatars-set>

                            <v-subheader>Параметры</v-subheader>

                            <v-list-item>
                                <v-list-item-avatar>
                                    <v-icon>mdi-calendar-clock</v-icon>
                                </v-list-item-avatar>
                                <v-list-item-content>
                                    <v-list-item-title>{{ moment(selectedTask.deadline).format('D MMMM Y') }}</v-list-item-title>
                                    <v-list-item-subtitle>Дедлайн</v-list-item-subtitle>
                                </v-list-item-content>
                            </v-list-item>

                            <v-list-item>
                                <v-list-item-avatar>
                                    <v-icon>mdi-calendar-plus</v-icon>
                                </v-list-item-avatar>
                                <v-list-item-content>
                                    <v-list-item-title>{{ moment(selectedTask.created_at).format('D MMMM Y') }}</v-list-item-title>
                                    <v-list-item-subtitle>Дата добавления</v-list-item-subtitle>
                                </v-list-item-content>
                            </v-list-item>

                            <v-list-item>
                                <v-list-item-avatar>
                                    <v-icon>mdi-format-list-checks</v-icon>
                                </v-list-item-avatar>
                                <v-list-item-content>
                                    <v-list-item-title>{{ selectedTask.status.name }}</v-list-item-title>
                                    <v-list-item-subtitle>Статус</v-list-item-subtitle>
                                </v-list-item-content>
                            </v-list-item>

                            <v-list-item>
                                <v-list-item-avatar>
                                    <v-icon>mdi-timelapse</v-icon>
                                </v-list-item-avatar>
                                <v-list-item-content>
                                    <v-list-item-title>
                                        <span
                                            v-if="durObj(selectedTask.planned_time).days()"
                                        >{{ durObj(selectedTask.planned_time).days() }}д</span>
                                        <span
                                            v-if="durObj(selectedTask.planned_time).hours()"
                                        >{{ durObj(selectedTask.planned_time).hours() }}ч</span>
                                        <span
                                            v-if="durObj(selectedTask.planned_time).minutes()"
                                        >{{ durObj(selectedTask.planned_time).minutes() }}м</span>
                                    </v-list-item-title>
                                    <v-list-item-subtitle>Время на задачу</v-list-item-subtitle>
                                </v-list-item-content>
                            </v-list-item>

                            <priority :id="selectedTask.priority" classes=" lighten-3"></priority>

                            <v-subheader v-if="selectedTask.tags.length">Теги</v-subheader>
                            <div class="px-3">
                                <v-chip
                                    class="mb-2 mr-2"
                                    color="grey lighten-4"
                                    text-color="grey darken-1"
                                    v-for="(tag, index) in selectedTask.tags"
                                    :key="'tag-'+index"
                                    small
                                >
                                    <v-avatar style="margin-left:-8px" class="mr-0" left>
                                        <v-icon class="body-1">mdi-tag</v-icon>
                                    </v-avatar>
                                    {{ tag.name }}
                                </v-chip>
                            </div>
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
            hide-default-footer
            @click:row="displayTask"
        >
            <template v-slot:item.priority="{ item }">
                <priority :id="item.priority" icon></priority>
            </template>

            <template v-slot:item.planned_time="{ item }">
                <span
                    v-if="durObj(item.planned_time).days()"
                >{{ durObj(item.planned_time).days() }}д</span>
                <span
                    v-if="durObj(item.planned_time).hours()"
                >{{ durObj(item.planned_time).hours() }}ч</span>
                <span
                    v-if="durObj(item.planned_time).minutes()"
                >{{ durObj(item.planned_time).minutes() }}м</span>
            </template>

            <template v-slot:item.deadline="{ item }">{{ moment(item.deadline).fromNow() }}</template>

            <template v-slot:item.from="{ item }">
                <avatar :user="item.from" />
            </template>

            <template v-slot:item.created_at="{ item }">{{ moment(item.created_at).fromNow() }}</template>

            <template v-slot:item.status="{ item }">{{ item.status.name }}</template>
        </v-data-table>
    </div>
</template>

<script>
export default {
    props: ["tasks"],
    data() {
        return {
            headers: [
                { text: "Задача", value: "title" },
                // { text: 'Статус', value: 'status' },
                { text: "Приоритет", value: "priority" },
                { text: "Время на задачу", value: "planned_time" },
                { text: "Дедлайн", value: "deadline" },
                { text: "От", value: "from", sort: false },
                { text: "CreatedAt", value: "created_at" },
                { text: "Статус", value: "status" }
            ],
            taskDialog: false,
            selectedTask: {
                title: null,
                description: null,
                status: {
                    name: null
                },
                high_priority: null,
                spent_time: null,
                planned_time: null,
                deadline: null,
                tags: [],
                responsible_id: null,
                responsible: {
                    user: {
                        name: null,
                        surname: null,
                        img: null
                    }
                },
                from: {
                    user: {
                        name: null,
                        surname: null,
                        img: null
                    }
                }
            },
            activeTab: null
        };
    },
    methods: {
        displayTask(task) {
            this.selectedTask = task;
            this.taskDialog = true;
        },
        duration() {}
    },
    created() {
    }
};
</script>

<style>
</style>
