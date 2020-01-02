<template>
  <div>
    <v-dialog v-model="dialog" v-if="dialog" width="400">
      <v-card v-if="warning">
        <v-card-title class="headline">Невозможно удалить лист</v-card-title>
        <v-card-text>Убедитесь что все карточки пернесены в другой лист</v-card-text>
        <v-card-actions class="ma-0">
          <v-btn color="primary" @click="dialog = false" small text class="pl-0">ОК</v-btn>
        </v-card-actions>
      </v-card>
      <v-card v-if="!warning">
        <v-card-title class="headline">Удалить лист</v-card-title>
        <v-card-text>Вы уверены что хотите удалить лист?</v-card-text>
        <v-card-actions class="ma-0">
          <v-btn color="red lighten-2" small dark @click="deleteStatus(acceptBeforeDelete)">Удалить</v-btn>
          <v-spacer></v-spacer>
          <v-btn
            color="primary"
            @click="dialog = false, acceptBeforeDelete = false"
            small
            outlined
          >Отмена</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-dialog v-model="cardDialog" v-if="cardDialog" width="800px">
      <task :item="selectedTask" :users="users"></task>
    </v-dialog>
    <div class="parent-container">
      <div v-for="(status, index) in statuses" :key="index">
        <v-hover v-slot:default="{hover}" v-if="updateListForm !== status.id">
          <v-card color="grey lighten-3" class="mb-1" outlined elevation="0">
            <v-toolbar color="grey lighten-3" outlined elevation="0" dense>
              <v-toolbar-title class="grey--text body-2 font-weight-bold">{{status.name}}</v-toolbar-title>
              <v-spacer></v-spacer>
              <v-btn
                v-if="hover && status.user_id == authuser.id"
                icon
                small
                class="grey lighten-3"
                @click="listDelete(status)"
              >
                <v-icon small dark>mdi-delete</v-icon>
              </v-btn>
              <v-btn
                v-if="hover && status.user_id == authuser.id"
                icon
                small
                class="grey lighten-3"
                @click="updateList(status.id, status.name)"
              >
                <v-icon small dark>mdi-pencil</v-icon>
              </v-btn>
            </v-toolbar>
          </v-card>
        </v-hover>
        <v-card :key="status.id" v-if="updateListForm == status.id" class="mb-1" outlined>
          <v-form ref="updateForm">
            <v-card-text class="pa-2">
              <v-text-field
                v-model="updateListName"
                label="Название"
                required
                :rules="rules"
                class="pb-2"
                solo
                flat
                hide-details
                outlined
                :value="status.name"
              ></v-text-field>
              <v-btn
                small
                color="primary"
                :loading="loading"
                :disabled="loading"
                @click="listUpdate(status.id)"
              >изменить</v-btn>
              <v-btn
                small
                color="red lighten-2"
                dark
                class="float-right"
                @click="updateListForm = null"
              >отмена</v-btn>
            </v-card-text>
          </v-form>
        </v-card>
        <v-card
          color="grey lighten-3"
          outlined
          class="scrollable-card"
          style="border-top:8px solid red;border-bottom:8px solid red;"
        >
          <draggable
            class="list-group"
            @start="isDragging = true"
            @end="isDragging = false"
            @add="onAdd($event, status.id)"
            @remove="onRemove($event, status.id)"
            v-bind="dragOptions"
            :scroll-sensitivity="300"
            :force-fallback="true"
            :scroll-speed="50"
            :sort="true"
          >
            <v-card
              v-for="el in status.items"
              :key="el.id"
              class="mx-2 mb-2"
              :dataId="el.id"
              flat
              @click="showSelectedTask(el)"
            >
              <v-system-bar :color="priorities[el.priority].color" height="5px"></v-system-bar>
              <v-card-text class="pa-2">
                <p class="font-weight-bold mb-1">{{el.title}}</p>
                <p class="mb-1">
                  <v-tooltip bottom z-index="100">
                    <template v-slot:activator="{ on }">
                      <v-icon small v-if="el.watchers.length > 0" v-on="on">mdi-eye-outline</v-icon>
                    </template>
                    <span>У этого задания есть наблюдатели</span>
                  </v-tooltip>
                  <v-tooltip bottom z-index="100">
                    <template v-slot:activator="{ on }">
                      <span v-on="on">
                        <v-icon small>mdi-clock-outline</v-icon>
                        <span class="caption">{{moment(el.deadline).format('D MMMM')}}</span>
                      </span>
                    </template>
                    <span>Дедлайн</span>
                  </v-tooltip>
                  <v-tooltip bottom z-index="100">
                    <template v-slot:activator="{ on }">
                      <v-icon small v-if="el.description" v-on="on">mdi-sort-variant</v-icon>
                    </template>
                    <span>У этого задания есть описание</span>
                  </v-tooltip>
                </p>
                <p class="mb-1">
                  <avatars-set :items="usersForAvatar(el)" :size="30" item-hint="role" />
                </p>
                <p class="mt-4 mb-0">
                  <v-chip
                    class="mb-2 mr-2"
                    color="grey lighten-4"
                    text-color="grey darken-1"
                    v-for="(tag, index) in el.tags"
                    :key="'tag-'+index"
                    small
                  >{{ tag.name }}</v-chip>
                </p>
              </v-card-text>
            </v-card>
          </draggable>
        </v-card>
      </div>
      <div>
        <v-card
          color="grey lighten-3"
          class="card-btn"
          outlined
          v-show="!addListForm"
          height="50px"
        >
          <v-card-text class="pt-3 font-weight-bold" @click="addListForm = true">
            <p>+ Добавить новый лист</p>
          </v-card-text>
        </v-card>
        <v-card v-if="addListForm" outlined>
          <v-form ref="listForm">
            <v-card-text class="pa-2">
              <v-text-field
                v-model="listName"
                label="Название"
                required
                :rules="rules"
                class="pb-2"
                solo
                flat
                hide-details
                outlined
              ></v-text-field>
              <v-btn
                small
                color="primary"
                :loading="loading"
                :disabled="loading"
                @click="addList"
              >добавить</v-btn>
              <v-btn
                small
                color="red lighten-2"
                dark
                class="float-right"
                @click="closeNewList"
              >отмена</v-btn>
            </v-card-text>
          </v-form>
        </v-card>
      </div>
    </div>
  </div>
</template>
<script>
import draggable from "vuedraggable";
export default {
  components: {
    draggable
  },
  props: ["users", "taskStatuses", "tasks", "authuser"],
  data() {
    return {
      updateListForm: null,
      addListForm: false,
      listName: null,
      updateListName: null,
      mainStatuses: this.taskStatuses.filter(elem => !elem.user_id),
      userStatuses: this.taskStatuses.filter(
        elem => elem.user_id === this.authuser.id
      ),
      acceptBeforeDelete: null,
      statuses: [],
      loading: false,
      rules: [v => !!v || "Форма должна быть заполнена"],
      dialog: false,
      warning: false,
      cardDialog: false,
      selectedTask: null,
      check: [],
      priorities: [
        { id: 0, label: "Низкий", color: "green lighten-3" },
        { id: 1, label: "Средний", color: "blue lighten-3" },
        { id: 2, label: "Высокий", color: "red lighten-3" }
      ]
    };
  },
  computed: {
    dragOptions() {
      return {
        animation: 250,
        group: "description",
        disabled: false,
        ghostClass: "ghost"
      };
    }
  },
  methods: {
    onAdd(element, statusId) {
      let id = element.item.getAttribute("dataId");
      axios
        .put(`/api/change-task-status/${id}`, {
          statusId: statusId
        })
        .then(res => {
          this.statuses.forEach(elem => {
            if (elem.id == statusId) {
              this.tasks.find(task => {
                if (task.id == id) {
                  elem.items.push(task);
                }
              });
            }
          });
          Event.fire("taskStatusChanged", res.data);
        })
        .catch(err => err.messages);
    },
    onRemove(element, statusId) {
      let id = element.item.getAttribute("dataId");
      this.statuses.forEach(elem => {
        elem.items.forEach((item, index) => {
          if (item.id == id) {
            elem.items.splice(index, 1);
          }
        });
      });
    },
    prepareStatuses() {
      this.tasks.forEach(task => {
        if (!this.statuses.find(status => status.id == task.status.id))
          this.statuses.push({ items: [] });
      });
    },
    preparestatusItems() {
      this.statuses.forEach(status => {
        status.items = this.tasks.filter(task => task.status_id == status.id);
      });
    },
    addList() {
      let listForm = this.$refs.listForm;

      if (listForm.validate()) {
        this.loading = true;
        axios
          .post("/api/addStatus", {
            name: this.listName,
            auth_user_id: this.authuser.id
          })
          .then(res => {
            this.statuses.push(res.data);
            this.prepareStatuses();
            this.preparestatusItems();
            this.loading = false;
            this.closeNewList();
          })
          .catch(err => err.messages);
      }
    },
    closeNewList() {
      this.addListForm = false;
      this.$refs.listForm.reset();
    },
    updateList(id, name) {
      this.updateListForm = id;
      this.updateListName = name;
    },
    listUpdate(id) {
      let listForm = this.$refs.updateForm;
      if (listForm[0].validate()) {
        this.loading = true;
        axios
          .put(`/api/change-status-name/${id}`, {
            name: this.updateListName
          })
          .then(res => {
            this.statuses.forEach(elem => {
              if (elem.id == id) {
                elem.name = this.updateListName;
              }
            });
            this.loading = false;
            this.updateListForm = null;
          })
          .catch(err => err.messages);
      }
    },
    listDelete(status) {
      this.dialog = true;
      this.acceptBeforeDelete = status;
      if (status.items.length > 0) {
        this.warning = true;
      } else {
        this.warning = false;
      }
    },
    deleteStatus(status) {
      axios.delete(`/api/delete-status/${status.id}`).then(res => {
        this.statuses.forEach((elem, index) => {
          if (elem.id == status.id) {
            this.statuses.splice(index, 1);
            this.dialog = false;
          }
        });
      });
    },
    showSelectedTask(task) {
      let checkTask = this.check.some(elem => elem.id == task.id);
      if (!checkTask) {
        axios.get(`/api/select-task/${task.id}`, {}).then(res => {
          this.check.push(res.data);
          this.selectedTask = res.data;
          this.cardDialog = true;
        });
      } else {
        this.check.forEach(elem => {
          if (elem.id === task.id) {
            this.selectedTask = elem;
            this.cardDialog = true;
          }
        });
      }
    },
    usersForAvatar(task) {
      let users = task.watchers.map(watcher => {
        watcher["role"] = "Наблюдатель";
        return watcher;
      });

      task.responsible["role"] = "Исполнитель";
      task.from["role"] = "Постановщик";

      users.push(task.responsible, task.from);

      return users;
    }
  },
  created() {
    this.mainStatuses.forEach(elem => this.statuses.push(elem));
    this.userStatuses.forEach(elem => this.statuses.push(elem));
    this.prepareStatuses();
    this.preparestatusItems();
  },
};
</script>
<style>
.ghost {
  opacity: 0;
}
.list-group {
  cursor: pointer;
  min-height: 11vh;
  list-style: none;
}
.scrollable-card {
  overflow: auto;
  position: relative;
  max-height: 65vh;
  overflow-x: hidden;
}
.card-btn {
  cursor: pointer;
}
.parent-container {
  display: flex;
  overflow-x: auto;
  overflow-y: hidden;
  height: 100%;
  position: relative;
  min-height: calc(100vh - 125px);
}
.parent-container::-webkit-scrollbar {
  height: 8px;
}
.parent-container > div {
  width: 300px;
  flex: 0 0 auto;
  margin-right: 1%;
  margin-bottom: 1%;
}
</style>