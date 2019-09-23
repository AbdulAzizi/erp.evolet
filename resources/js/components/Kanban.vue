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
                label="Name"
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
              >Add</v-btn>
              <v-btn
                small
                color="red lighten-2"
                dark
                class="float-right"
                @click="updateListForm = null"
              >cancel</v-btn>
            </v-card-text>
          </v-form>
        </v-card>
        <div class="stick-top"></div>
        <v-card color="grey lighten-3" outlined class="scrollable-card">
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
              class="mx-2 mb-2 mt-1"
              :dataId="el.id"
              flat
            >
              <v-card-text>
                <p>{{el.title}}</p>
                <p>
                  <span class="float-left">Progress</span>
                  <span class="mb-2 float-right">{{progressValue}}%</span>
                </p>
                <v-progress-linear v-model="progressValue" color="primary" class="mb-2" />

                <p>
                  <span class="float-left">Due date</span>
                  <span class="float-right">{{el.deadline}}</span>
                </p>
                <br />
                <p>
                  <span class="float-left">Team</span>
                  <span class="float-right">
                    <avatar :user="el.responsible" :size="25" />
                  </span>
                </p>
              </v-card-text>
            </v-card>
          </draggable>
        </v-card>
        <div class="stick-bottom"></div>
      </div>
      <div>
        <v-card color="grey lighten-3" class="card-btn" outlined v-if="!addListForm" height="50px">
          <v-card-text class="pt-3 font-weight-bold" @click="addListForm = true">
            <p>+ Add another list</p>
          </v-card-text>
        </v-card>
        <v-card v-if="addListForm" outlined>
          <v-form ref="listForm">
            <v-card-text class="pa-2">
              <v-text-field
                v-model="listName"
                label="Name"
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
              >Add</v-btn>
              <v-btn
                small
                color="red lighten-2"
                dark
                class="float-right"
                @click="closeNewList"
              >cancel</v-btn>
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
      progressValue: 50,
      loading: false,
      rules: [v => !!v || "Форма должна быть заполнена"],
      dialog: false,
      warning: false
    };
  },
  computed: {
    dragOptions() {
      return {
        animation: 200,
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
          this.statuses.find(elem => {
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
    }
  },
  created() {
    this.mainStatuses.forEach(elem => this.statuses.push(elem));
    this.userStatuses.forEach(elem => this.statuses.push(elem));
    this.prepareStatuses();
    this.preparestatusItems();
    this.lists = this.statuses;
  }
};
</script>
<style>
.ghost {
  transition: transform 1s;
}
.list-group {
  cursor: pointer;
  min-height: 15vh;
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
}
.parent-container > div {
  width: 300px;
  flex: 0 0 auto;
  margin-right: 1%;
  margin-bottom: 1%;
}
.stick-top {
  height: 8px;
  width: 100%;
  background: #eeeeee;
  position: sticky;
  position: -webkit-sticky;
  z-index: 2;
  margin-bottom: -5px;
  border: solid 0.5px #eeeeee;
  border-radius: 10px;
}
.stick-bottom {
  height: 7px;
  width: 100%;
  background: #eeeeee;
  position: sticky;
  position: -webkit-sticky;
  z-index: 2;
  margin-top: -5px;
  border: solid 0.5px #eeeeee;
  border-radius: 10px;
}
</style>