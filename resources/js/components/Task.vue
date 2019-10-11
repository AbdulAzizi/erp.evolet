<template>
  <v-card style="background-color:#f4f5f7;" min-height="60vh">
    <v-row no-gutters>
      <v-col cols="8">
        <v-toolbar dense flat>
          <v-toolbar-title>{{task.title}}</v-toolbar-title>
          <div class="flex-grow-1"></div>
          <v-menu bottom left :offset-y="true">
            <template v-slot:activator="{ on }">
              <v-btn icon v-on="on" x-small>
                <v-icon small>mdi-dots-vertical</v-icon>
              </v-btn>
            </template>
            <v-card>
              <v-list>
                <v-list-item-group>
                  <v-list-item>
                    <v-list-item-content>
                      <v-list-item-title class="body-2" @click="forwardTask">Делегировать</v-list-item-title>
                    </v-list-item-content>
                  </v-list-item>
                </v-list-item-group>
              </v-list>
            </v-card>
          </v-menu>
          <template v-slot:extension>
            <v-tabs v-model="tab">
              <v-tab href="#task" class="ma-0">Задача</v-tab>
              <v-tab href="#messages">Коментарии</v-tab>
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
        <v-tabs-items v-model="tab" style="background-color:#f4f5f7;" class="task-main-content">
          <v-tab-item value="task">
            <v-card-text>
              <p class="font-weight-bold">Описание</p>
              {{task.description ? task.description : ''}}
              <p
                class="font-weight-bold mt-3"
                v-if="Array.isArray(task.polls) && task.polls.length"
                :poll="task.polls[0]"
              >Опрос</p>
              <poll-form
                v-if="Array.isArray(task.polls) && task.polls.length"
                :poll="task.polls[0]"
              />
              <!-- <v-list nav v-if="taskHasActions"> -->
              <v-list nav v-if="false">
                <v-list-item-group color="primary">
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
            </v-card-text>
          </v-tab-item>
          <v-tab-item value="messages">
            <messages :messageable="task" type="Tasks" />
          </v-tab-item>
          <v-tab-item value="history">
            <v-col>
              <history :history="task.history" />
            </v-col>
          </v-tab-item>
        </v-tabs-items>
      </v-col>
      <v-col cols="4" class="white">
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
          <v-list-item>
            <v-list-item-content>
              <task-control-buttons :task="task" />
            </v-list-item-content>
          </v-list-item>

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
    Event.listen("taskStarted", data => {
      return (this.task.status.name = "В процессе");
    });

    Event.listen("stopTask", data => {
      return (this.task.status.name = "Закрытый");
    });

    this.synch();
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
          rules: field.pivot && field.pivot.required ? ["required"] : [true],
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
      return this.task.responsible.position.id === DIVISION_HEAD_POSITION_ID;
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
  min-height: 80vh;
}
</style>
