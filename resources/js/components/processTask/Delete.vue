<template>
  <div>
      <v-card>
        <v-card-title class="headline">Вы хотите удалить задачу?</v-card-title>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="red lighten-2" text @click="dialog = false">Отмена</v-btn>
          <v-btn color="primary" text @click="deleteTask()">Удалить</v-btn>
        </v-card-actions>
      </v-card>
  </div>
</template>
<script>
export default {
  props: ["task"],
  data() {
    return {
      deleteDialog: false
    };
  },
  methods: {
    deleteTask() {        
      axios
        .delete(`/api/process/task/delete/${this.task.id}`)
        .then(res => {
          this.deleteDialog = false;
          Event.fire("processTaskDeleted", this.task.id);
          Event.fire("notify", [`Задача ${this.task.title} удалена`]);
        })
        .catch(err => err.messages);
    }
  }
};
</script>