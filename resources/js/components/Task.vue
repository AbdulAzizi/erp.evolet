<template>
    <v-card>
        <v-layout>
            <V-flex xs6 class="grey lighten-3" style="position:relative;overflow:hidden;">
                <v-tabs v-model="tab" style="position:absolute; z-index:1;" class="elevation-4">
                    <v-tab href="#task">Задача</v-tab>
                    <v-tab href="#comments">Коментарии</v-tab>
                    <v-tab href="#history">История</v-tab>
                </v-tabs>
                <v-tabs-items
                    v-model="tab"
                    class="grey lighten-3 task-main-content"
                    style="padding-top:48px;"
                >
                    <v-tab-item value="task">
                        <v-card-title>
                            <h3 class="headline mb-0">{{task.title}}</h3>
                        </v-card-title>
                        <v-card-text>
                            {{task.description ? task.description : ''}}
                            <poll-form v-if="Array.isArray(task.polls) && task.polls.length" :poll="task.polls[0]" />
                        </v-card-text>
                    </v-tab-item>
                    <v-tab-item value="comments">
                        <comments :commentable="task" type="Tasks"/>
                    </v-tab-item>
                    <v-tab-item value="history">
                        <v-flex class="ma-4">
                            <history :history="task.history" />
                        </v-flex>
                    </v-tab-item>
                </v-tabs-items>
            </V-flex>

            <v-flex xs3>
                <v-list nav v-if="taskHasActions">
                    <v-subheader>Действия</v-subheader>
                    <v-list-item-group color="primary">
                        <v-list-item v-if="userCanForward">
                            <v-list-item-content>
                                <v-list-item-title @click="forwardTask">Делегировать</v-list-item-title>
                                <dynamic-form
                                    dialog
                                    :fields="[forwardField]"
                                    title="Выберите сотрудника"
                                    :actionUrl="'/tasks/' + task.id"
                                    method="put"
                                    activatorEventName="forwardTask"
                                />
                            </v-list-item-content>
                        </v-list-item>
                        <v-list-item
                            v-for="( tether, i ) in task.from.front_tethers"
                            :key="'list-item-'+i"
                        >
                            <v-list-item-content>
                                <v-list-item-title @click="addProduct">{{ tether.action_text }}</v-list-item-title>
                                <!-- {{preparedFields({...tether.form})}} -->
                                <dynamic-form
                                    width="800"
                                    dialog
                                    :fieldsPerRows="[2]"
                                    :fields="preparedFields(tether.form)"
                                    :title="tether.form.label"
                                    actionUrl="/products"
                                    activatorEventName="addProduct"
                                    method="post"
                                ></dynamic-form>
                            </v-list-item-content>
                        </v-list-item>
                    </v-list-item-group>
                </v-list>
            </v-flex>

            <v-flex xs3>
                <v-list subheader dense tile>
                    <v-subheader>Участники</v-subheader>
                    <avatars-set :items="usersForAvatar" item-hint="role" class="pl-3"></avatars-set>
                    <v-subheader>Параметры</v-subheader>

                    <v-list-item>
                        <v-list-item-avatar>
                            <v-icon>mdi-calendar-clock</v-icon>
                        </v-list-item-avatar>
                        <v-list-item-content>
                            <v-list-item-title>{{ moment(task.deadline).format('D MMMM Y') }}</v-list-item-title>
                            <v-list-item-subtitle>Дедлайн</v-list-item-subtitle>
                        </v-list-item-content>
                    </v-list-item>

                    <v-list-item>
                        <v-list-item-avatar>
                            <v-icon>mdi-calendar-plus</v-icon>
                        </v-list-item-avatar>
                        <v-list-item-content>
                            <v-list-item-title>{{ moment(task.created_at).format('D MMMM Y') }}</v-list-item-title>
                            <v-list-item-subtitle>Дата добавления</v-list-item-subtitle>
                        </v-list-item-content>
                    </v-list-item>

                    <v-list-item>
                        <v-list-item-avatar>
                            <v-icon>mdi-format-list-checks</v-icon>
                        </v-list-item-avatar>
                        <v-list-item-content>
                            <v-list-item-title>{{ task.status.name }}</v-list-item-title>
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
                                    v-if="durObj(task.planned_time).days()"
                                >{{ durObj(task.planned_time).days() }}д</span>
                                <span
                                    v-if="durObj(task.planned_time).hours()"
                                >{{ durObj(task.planned_time).hours() }}ч</span>
                                <span
                                    v-if="durObj(task.planned_time).minutes()"
                                >{{ durObj(task.planned_time).minutes() }}м</span>
                            </v-list-item-title>
                            <v-list-item-subtitle>Время на задачу</v-list-item-subtitle>
                        </v-list-item-content>
                    </v-list-item>

                    <priority :id="task.priority" classes=" lighten-3"></priority>

                    <v-subheader v-if="task.tags.length">Теги</v-subheader>
                    <div class="px-3">
                        <v-chip
                            class="mb-2 mr-2"
                            color="grey lighten-4"
                            text-color="grey darken-1"
                            v-for="(tag, index) in task.tags"
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
</template>

<script>
const DIVISION_HEAD_POSITION_ID = 1;

export default {
    props: {
        item: {
            required: true
        },
        users: Array
    },
    data() {
        return {
            task: {
                watchers: [],
                title: null,
                description: null,
                status: {
                    name: null
                },
                priority: null,
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
                },
                polls: []
            },
            dialog: false,
            preparedForm: null,
            tab: null,
            forwardField: {
                name: "responsible_id",
                type: "users",
                label: "Сотрудники",
                users: this.users,
                rules: ["required"]
            }
        };
    },
    created() {
        this.synch();
        console.log(this.task);
    },
    watch: {
        item(v) {
            this.synch();
        }
    },
    methods: {
        addProduct() {
            Event.fire("addProduct");
        },
        forwardTask() {
            Event.fire("forwardTask");
        },
        synch() {
            if (this.item) {
                this.task = this.item;
            }
        },
        preparedFields(form) {
            return form.fields.map(field => {
                return {
                    ...field,
                    rules:
                        field.pivot && field.pivot.required
                            ? ["required"]
                            : [true],
                    type: this.getDynamicFieldsType(field.type.name)
                };
            });
        },
        getDynamicFieldsType(laravelType) {
            // TODO Make a normal adapter or refactor to use same types in vue and laravel
            switch (laravelType) {
                case "list":
                    return "autocomplete";
                    break;
                default:
                    return laravelType;
                    break;
            }
        }
    },
    computed: {
        taskHasActions() {
            return this.taskHasBPActions || this.userCanForward;
        },
        taskHasBPActions() {
            return this.task.from.front_tethers;
        },
        userCanForward() {
            return (
                this.task.responsible.position.id === DIVISION_HEAD_POSITION_ID
            );
        },
        usersForAvatar() {
            let users = this.task.watchers.map(watcher => {
                watcher["role"] = "Наблюдатель";
                return watcher;
            });

            this.task.responsible["role"] = "Исполнитель";
            this.task.from["role"] = "Постановщик";

            users.push(this.task.responsible, this.task.from);

            return users;
        }
    }
};
</script>

<style>
.task-main-content {
    /* max-height: 20rem; */
    overflow-y: auto;
}
</style>
