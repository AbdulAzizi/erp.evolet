<template>
    <div>
        <v-container grid-list-lg fluid pa-0>
            <profile-banner :user="user" />
            <v-layout row wrap>
                <v-flex xs6 md3>
                    <stats-card
                    color="blue"
                    icon="mdi-calendar-text"
                    title="Новые задачи"
                    value="45"
                    >
                    </stats-card>
                </v-flex>
                <v-flex xs6 md3>
                    <stats-card
                    color="orange"
                    icon="mdi-progress-clock"
                    title="В процессе"
                    value="12"
                    >
                    </stats-card>
                </v-flex>
                <v-flex xs6 md3>
                    <stats-card
                    color="green"
                    icon="mdi-calendar-multiple-check"
                    title="Завершенно"
                    value="30"
                    >
                    </stats-card>
                </v-flex>
                <v-flex xs6 md3>
                    <stats-card
                    color="red"
                    icon="mdi-clock-alert-outline"
                    title="Просрочено"
                    value="4"
                    >
                    </stats-card>
                </v-flex>
                <v-flex xs6 md3>
                    <stats-card
                    color="indigo "
                    icon="mdi-share"
                    title="Делегировано"
                    value="11"
                    >
                    </stats-card>
                </v-flex>
                <v-flex xs6 md3>
                    <stats-card
                    color="pink"
                    icon="mdi-debug-step-over"
                    title="Перенесено"
                    value="5"
                    >
                    </stats-card>
                </v-flex>
                <v-flex xs6 md3>
                    <stats-card
                    color="purple"
                    icon="mdi-calendar-multiple"
                    title="Всего задач"
                    value="97"
                    >
                    </stats-card>
                </v-flex>
                <v-flex xs6 md3>
                    <stats-card
                    color="primary"
                    icon="mdi-calendar-clock"
                    title="Время на задачи"
                    value="7д 12ч 34м"
                    >
                    </stats-card>
                </v-flex>
            </v-layout>
            <v-layout row wrap>
                <v-flex xs3>
                    <card color="primary" title="Задачи" text="По преоритетам">
                        <v-list dense>
                            <template v-for="(priority,key) in priorities">
                                <v-list-item href="#" :key="'list-item-'+key">
                                <v-list-item-action>
                                    <v-icon :color="priority.color">mdi-flag-variant</v-icon>
                                </v-list-item-action>
                                <v-list-item-content>
                                    <v-list-item-title>{{priority.title}}</v-list-item-title>
                                </v-list-item-content>
                                <v-list-item-action>
                                <v-list-item-action-text>{{priority.count}}</v-list-item-action-text>
                                </v-list-item-action>
                                </v-list-item>
                                <v-divider v-if="key < priorities.length-1" :key="key"></v-divider>
                            </template>
                        </v-list>
                    </card>
                </v-flex>
            </v-layout>
        </v-container>
    </div>
</template>
<script>
export default {
    props: {
        user: {
            required: true
        }
    },
    data() {
        return {
            priorities: [
                { color: "red", title: "Высокий", count: 24 },
                { color: "yellow", title: "Средний", count: 30 },
                { color: "blue", title: "Низкий", count: 11 }
            ]
        }
    }
}
</script>
