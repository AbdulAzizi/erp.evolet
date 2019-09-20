<template>
  
    <div class="parent-container">
      <div
        v-for="(status, index) in statuses"
        :key="'status-'+index"
      >
        <v-card color="grey lighten-5" class="mb-1" outlined>
          <v-card-text class="pb-0">
            <p>{{status.name}}</p>
          </v-card-text>
        </v-card>
        <v-card color="grey lighten-5" outlined class="scrollable-card">
          <v-card-text v-if="status.length <= 0" class="pa-2 ma-0">
            <p>Move cards here</p>
          </v-card-text>
          <draggable
            class="list-group"
            @start="isDragging = true"
            @end="isDragging = false"
            @add="onAdd($event, status.id)"
            v-bind="dragOptions"
          >
            <v-card
              
              v-for="el in status.items"
              :key="el.id"
              class="ma-2 pb-2"
              elevation="2"
              :dataId="el.id"
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
      </div>
       <div
        style="width: 30vw; flex: 0 0 auto; margin-right: 1%; height: 100% ">
        <v-card color="grey lighten-5" class="card-btn" outlined v-if="!listField">
          <v-card-text class="pb-0" @click="listField = true">
            <p>+ Add another list</p>
          </v-card-text>
        </v-card>
        <v-card v-if="listField" color="grey lighten-5" outlined>
          <v-form ref="listForm">
            <v-card-text class="pb-3 pt-1">
              <v-text-field v-model="listName" label="Name" required :rules="rules" class="mb-1"></v-text-field>
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
</template>
<script>
import draggable from "vuedraggable";
export default {
  components: {
    draggable
  },
  props: ["users", "taskStatuses", "tasks"],
  data() {
    return {
      listField: false,
      listName: null,
      statuses: this.taskStatuses,
      progressValue: 50,
      loading: false,
      rules: [v => !!v || "Форма должна быть заполнена"],
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
        .then(res => res.data)
        .catch(err => err.messages);
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
      let listform = this.$refs.listForm;

      if (listform.validate()) {
        this.loading = true;
        axios
          .post("/api/addStatus", {
            name: this.listName
          })
          .then(res => {
            this.statuses.push(res.data);
            this.loading = false;
            this.closeNewList();
          })
          .catch(err => err.messages);
      }
    },
    closeNewList() {
      this.listField = false;
      this.$refs.listForm.reset();
    }
  },
  created() {
    this.prepareStatuses();
    this.preparestatusItems();
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
  max-height: 70vh;
}
.card-btn {
  cursor: pointer;
}
.parent-container {
  display:flex; 
  overflow-x: auto;
  overflow-y: hidden;
}
.parent-container > div {
  width: 20vw; 
  flex: 0 0 auto; 
  margin-right: 1%; 
  margin-bottom: 1%;
}
</style>