<template>
    <v-card style="background-color:#f4f5f7;" min-height="60vh">
        <v-row no-gutters>
            <v-col cols="9">
                <v-toolbar dense flat>
                    <v-toolbar-title>{{task.responsibility_description.title}}</v-toolbar-title>
                    <div class="flex-grow-1"></div>
                    <v-menu bottom left :offset-y="true">
                        <template v-slot:activator="{ on }">
                            <v-btn icon v-on="on">
                                <v-icon>mdi-dots-vertical</v-icon>
                            </v-btn>
                        </template>
                        <v-card>
                            <v-list dense>
                                <v-list-item
                                    class="body-2"
                                    @click="forwardTask"
                                    v-if="userCanForward"
                                >Делегировать</v-list-item>
                                <v-list-item
                                    class="body-2"
                                    @click="markAsUnread(task)"
                                >Отметить как непрочитанное</v-list-item>
                            </v-list>
                        </v-card>
                    </v-menu>
                    <template v-slot:extension>
                        <v-tabs v-model="tab">
                            <v-tab href="#task" class="ma-0">Задача</v-tab>
                            <v-tab href="#messages">Комментарии</v-tab>
                            <v-tab href="#history">История</v-tab>

                            <dynamic-form
                                v-if="userCanForward"
                                dialog
                                :fields="[forwardField]"
                                title="Выберите сотрудника"
                                :actionUrl="'/tasks/' + task.id"
                                method="put"
                                activatorEventName="forwardTask"
                            />
                        </v-tabs>
                    </template>
                </v-toolbar>
                <v-tabs-items
                    v-model="tab"
                    style="background-color:#f4f5f7;"
                    class="task-main-content"
                >
                    <v-tab-item value="task">
                        <v-card-text>
                            <div v-if="task.description" class="pb-3">
                                <div class="font-weight-medium pb-1">Описание</div>
                                <div v-html="task.description"></div>
                            </div>
                            <div v-if="task.question_tasks.length" class="pb-3">
                                <div class="font-weight-medium pb-1">Опрос</div>
                                <poll-form
                                    :questionTask="task.question_tasks[0]"
                                    :disabled="false"
                                />
                            </div>
                            <template v-if="task.from.front_tethers">
                                <div
                                    v-if="task.from.front_tethers.length > 1 && task.question_tasks.length === 0"
                                >
                                    <div class="font-weight-medium pb-1">Действия</div>
                                    <!-- <v-list nav v-if="taskHasActions"> -->
                                    <v-list nav>
                                        <v-list-item-group color="primary">
                                            <v-list-item
                                                v-for="( tether, index ) in task.from.front_tethers"
                                                :key="'list-item-'+index"
                                                :href="tether.forms.length == 0 ? appPath(`/products/${task.products[0].id}/changeTo/${tether.to_process_id}`) : null"
                                            >
                                                <v-list-item-content
                                                    v-if="tether.forms.length != 0"
                                                >
                                                    <v-list-item-title
                                                        @click="displayForm(index)"
                                                    >{{ tether.action_text }}</v-list-item-title>
                                                    <!-- {{preparedFields({...tether.form})}} -->
                                                    <dynamic-form
                                                        width="800"
                                                        dialog
                                                        :fieldsPerRows="tether.forms[0].fields.length > 1 ? [2] : [1]"
                                                        :fields="preparedFields(tether.forms[0])"
                                                        :title="tether.forms[0].label"
                                                        :actionUrl=" appPath(`products/${task.products[0].id}/changeTo/${tether.to_process_id}`)"
                                                        :activatorEventName="`display_form_${index}`"
                                                        method="post"
                                                    ></dynamic-form>
                                                </v-list-item-content>
                                                <v-list-item-content v-else>
                                                    <v-list-item-title>{{ tether.action_text }}</v-list-item-title>
                                                </v-list-item-content>
                                            </v-list-item>
                                        </v-list-item-group>
                                    </v-list>
                                </div>
                            </template>

                            <div v-if="task.forms.length != 0">
                                <div class="font-weight-medium pb-1">Форма</div>
                                <dynamic-form
                                    width="800"
                                    :fieldsPerRows="[2]"
                                    :fields="preparedFields(task.forms[0])"
                                    :title="task.forms[0].label"
                                    :actionUrl="appPath(`products/${task.products[0].id}/changeToNext`)"
                                    method="post"
                                ></dynamic-form>
                            </div>
                        </v-card-text>
                    </v-tab-item>
                    <v-tab-item value="messages">
                        <messages
                            v-if="task.products"
                            :messageable="task.products[0]"
                            type="App\Product"
                        />
                        <messages v-else :messageable="task" type="App\Task" />
                    </v-tab-item>
                    <v-tab-item value="history">
                        <v-col>
                            <history :history="task.history" />
                        </v-col>
                    </v-tab-item>
                </v-tabs-items>
            </v-col>
            <v-col cols="3" class="white">
                <v-list subheader dense tile class="pl-5">
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
                                <span>{{durObj(task.planned_time)}}</span>
                            </v-list-item-title>
                            <v-list-item-subtitle>Время на задачу</v-list-item-subtitle>
                        </v-list-item-content>
                    </v-list-item>

                    <priority :id="task.priority" classes=" lighten-3"></priority>

                    <task-control-buttons v-if="userIsTaskOwner" class="mr-5" :task="task" />

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
            </v-col>
        </v-row>
    </v-card>
</template>

<script>
const DIVISION_HEAD_POSITION__LEVEL_ID = 1;

export default {
    props: {
        item: {
            required: true
        }
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
                users: [],
                rules: ["required"]
            }
        };
    },
    created() {
        this.synch();

        Event.listen(`tasks/${this.task.id}/status/changed`, status => {
            this.task.status = status;
        });
        this.markTaskAsRead();
    },
    watch: {
        item(v) {
            this.synch();
        }
    },
    methods: {
        displayForm(index) {
            Event.fire(`display_form_${index}`);
        },
        forwardTask() {
            axios.get("/api/users").then(res => {
                this.forwardField.users = res.data;
            });
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
            // return [ ...fields, { type: "input", name: "product_id", value: form.pivot.product_id } ];
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
        },

        markAsUnread(task) {
            axios
                .put(`/api/task/mark/${task.id}`, { readed: 0 })
                .then(res => res.data)
                .catch(err => err.messages);
        },
        markTaskAsRead() {
            axios
                .post("/api/notifications/mark", {
                    task_id: this.item.id,
                    user_id: this.item.responsible.id
                })
                .then(res => {
                    this.item.readed = 1;
                });
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
                this.task.responsible.position_level.id ===
                DIVISION_HEAD_POSITION__LEVEL_ID
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
        },
        userIsTaskOwner(){
            return this.auth.id == this.task.responsible_id;
        }
    }
};
</script>

<style>
.task-main-content {
    /* max-height: 20rem; */
    overflow-y: auto;
    min-height: 80vh;
}
</style>
