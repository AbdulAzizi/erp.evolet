<template>
  <v-card style="background-color:#f4f5f7;" min-height="60vh">
    <v-row no-gutters>
      <v-col cols="9">
        <v-toolbar dense flat>
          <task-title :task="task" :edit="edit" />
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
                  v-if="isTaskAuthor || isHisHead"
                >Делегировать</v-list-item>
                <v-list-item class="body-2" @click="markAsUnread(task)">Отметить как непрочитанное</v-list-item>
                <v-list-item
                  class="body-2"
                  v-if="isTaskAuthor"
                  @click="deleteTaskDialog = true"
                >Удалить задачу</v-list-item>
                <v-list-item
                  v-if="isTaskResponsible || isTaskAuthor"
                  class="body-2"
                  @click="edit = !edit"
                >
                  {{
                  edit
                  ? "Закончить изменения"
                  : "Изменить"
                  }}
                </v-list-item>
              </v-list>
            </v-card>
          </v-menu>
          <template v-slot:extension>
            <v-tabs v-model="tab">
              <v-tab href="#task" class="ma-0">Задача</v-tab>
              <v-tab href="#messages">Комментарии</v-tab>
              <v-tab href="#history">История</v-tab>

              <dynamic-form
                v-if="isTaskAuthor || isHisHead"
                dialog
                :fields="forwardFields"
                title="Выберите сотрудника"
                :actionUrl="appPath(`tasks/${task.id}/forward`)"
                method="post"
                activatorEventName="forwardTask"
              />
            </v-tabs>
          </template>
        </v-toolbar>
        <v-tabs-items v-model="tab" style="background-color:#f4f5f7;" class="task-main-content">
          <v-tab-item value="task">
            <v-card-text>
              <div v-if="task.read_at" class="pb-3">
                <div class="font-weight-medium pb-1 grey--text">
                  Просмотрено:
                  {{
                  moment(item.read_at)
                  .local()
                  .format("lll")
                  }}
                </div>
              </div>

              <task-description :task="task" :edit="edit && isTaskAuthor" />

              <div v-if="task.question_tasks.length" class="pb-3">
                <div class="font-weight-medium pb-1">Опрос</div>
                <poll-form :questionTask="task.question_tasks[0]" :disabled="false" />
              </div>
              <template v-if="task.from.front_tethers">
                <div
                  v-if="
                                        task.from.front_tethers.length > 1 &&
                                            task.question_tasks.length === 0
                                    "
                >
                  <div class="font-weight-medium pb-1">Действия</div>
                  <!-- <v-list nav v-if="taskHasActions"> -->
                  <v-list nav>
                    <v-list-item-group color="primary">
                      <v-list-item
                        v-for="(tether, index) in task
                                                    .from.front_tethers"
                        :key="'list-item-' + index"
                        :href="
                                                    tether.forms.length == 0
                                                        ? appPath(
                                                              `/products/${
                                                                  task
                                                                      .products[0]
                                                                      .id
                                                              }/changeTo/${
                                                                  tether.to_process_id
                                                              }`
                                                          )
                                                        : null
                                                "
                      >
                        <v-list-item-content
                          v-if="
                                                        tether.forms.length != 0
                                                    "
                        >
                          <v-list-item-title
                            @click="
                                                            displayForm(index)
                                                        "
                          >
                            {{
                            tether.action_text
                            }}
                          </v-list-item-title>
                          <!-- {{preparedFields({...tether.form})}} -->
                          <dynamic-form
                            width="800"
                            dialog
                            :fieldsPerRows="
                                                            tether.forms[0]
                                                                .fields.length >
                                                            1
                                                                ? [2]
                                                                : [1]
                                                        "
                            :fields="
                                                            preparedFields(
                                                                tether.forms[0]
                                                            )
                                                        "
                            :title="
                                                            tether.forms[0]
                                                                .label
                                                        "
                            :actionUrl="
                                                            appPath(
                                                                `products/${
                                                                    task
                                                                        .products[0]
                                                                        .id
                                                                }/changeTo/${
                                                                    tether.to_process_id
                                                                }`
                                                            )
                                                        "
                            :activatorEventName="
                                                            `display_form_${index}`
                                                        "
                            method="post"
                          ></dynamic-form>
                        </v-list-item-content>
                        <v-list-item-content v-else>
                          <v-list-item-title>
                            {{
                            tether.action_text
                            }}
                          </v-list-item-title>
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
                  :actionUrl="
                                        appPath(
                                            `products/${
                                                task.products[0].id
                                            }/changeToNext`
                                        )
                                    "
                  method="post"
                ></dynamic-form>
              </div>
            </v-card-text>
          </v-tab-item>
          <v-tab-item value="messages">
            <messages v-if="task.products" :messageable="task.products[0]" type="App\Product" />
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
          <!-- <avatars-set
                        :items="usersForAvatar"
                        item-hint="role"
                        class="pl-3"
          ></avatars-set>-->
          <tasks-participants :task="task" :edit="edit" />
          <v-subheader>Параметры</v-subheader>

          <tasks-deadline :task="item" :edit="edit && isTaskAuthor" />

          <tasks-planned-time :task="task" :edit="edit && isTaskAuthor" />

          <tasks-priority :task="task" :edit="edit" />

          <v-list-item>
            <v-list-item-avatar>
              <v-icon>mdi-format-list-checks</v-icon>
            </v-list-item-avatar>
            <v-list-item-content>
              <v-list-item-title>
                {{
                task.status.name
                }}
              </v-list-item-title>
              <v-list-item-subtitle>Статус</v-list-item-subtitle>
            </v-list-item-content>
          </v-list-item>

          <v-list-item>
            <v-list-item-avatar>
              <v-icon>mdi-calendar-plus</v-icon>
            </v-list-item-avatar>
            <v-list-item-content>
              <v-list-item-title>
                {{
                moment(item.created_at)
                .local()
                .format("DD-MM-Y hh:mm")
                }}
              </v-list-item-title>
              <v-list-item-subtitle>Дата добавления</v-list-item-subtitle>
            </v-list-item-content>
          </v-list-item>

          <task-control-buttons v-if="isTaskResponsible" class="mr-5" :task="task" />

          <tasks-tags :task="task" :edit="edit" />
        </v-list>
      </v-col>
    </v-row>
    <delete-record
      :visible="deleteTaskDialog"
      @close="deleteTaskDialog = false"
      redirect="/tasks?all=true"
      :route="`/api/tasks/${task.id}`"
    ></delete-record>
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
      forwardFields: [
        {
          name: "responsible_id",
          type: "users",
          label: "Сотрудники",
          users: [],
          rules: ["required"]
        },
        {
          name: "reason",
          type: "text",
          label: "Укажите причину делегирования",
          rules: ["required"]
        }
      ],
      deleteTaskDialog: false,
      edit: false
    };
  },
  created() {
    this.synch();

    Event.listen(`tasks/${this.task.id}/status/changed`, status => {
      this.task.status = status;
    });
    Event.listen(
      `tasks/${this.task.id}/responsibilitydescription/changed`,
      description => {
        this.task.responsibility_description_id = description.id;
        this.task.responsibility_description = description;
      }
    );
    Event.listen("tagDeleted", tagId => {
      this.task.tags.forEach((tag, index) => {
        if (tag.id == tagId) {
          this.task.tags.splice(index, 1);
        }
      });
    });
    Event.listen("taskTagsUpdated", tags => {
      this.task.tags = tags;
    });
    Event.listen("taskPriorityChanged", priority => {
      this.task.priority = priority;
    });
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
        this.forwardFields[0].users = res.data;
      });
      Event.fire("forwardTask");
    },
    synch() {
      if (this.item) {
        Object.assign(this.task, this.item);
      }
    },
    preparedFields(form) {
      return form.fields.map(field => {
        return {
          ...field,
          rules: field.pivot && field.pivot.required ? ["required"] : [true],
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
    displayForm(index) {
      Event.fire(`display_form_${index}`);
    }
  },
  computed: {
    taskHasActions() {
      return this.taskHasBPActions || this.isHisHead;
    },
    taskHasBPActions() {
      return this.task.from.front_tethers;
    },
    isTaskAuthor() {
      return this.auth.id === this.task.from_id;
    },
    isTaskResponsible() {
      return this.auth.id === this.task.responsible_id;
    },
    isHisHead() {
      return (
        this.auth.position_level.name == "Руководитель" &&
        this.task.responsible.division_id == this.auth.division_id
      );
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
