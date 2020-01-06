<template>
  <div>
    <v-menu bottom left>
      <template v-slot:activator="{ on }">
        <v-btn class="float-right" icon v-on="on">
          <v-icon small>mdi-dots-vertical</v-icon>
        </v-btn>
      </template>
      <v-list dense>
        <v-list-item @click="addFormDialog = true" v-if="localTask.forms.length < 1">Добавить форму</v-list-item>
        <v-list-item @click="editDialog = true">Изменить</v-list-item>
        <v-list-item @click="deleteDialog = true">Удалить</v-list-item>
      </v-list>
    </v-menu>
    <v-dialog width="400" v-model="deleteDialog">
      <process-task-delete :task="localTask" />
    </v-dialog>
    <v-dialog v-model="editDialog" width="600">
      <process-task-edit :task="localTask" />
    </v-dialog>
    <v-dialog width="600" v-model="addFormDialog" persistent>
      <process-task-add-form :taskId="localTask.id" />
    </v-dialog>
  </div>
</template>

<script>
export default {
  props: ["task"],
  data() {
    return {
      dialog: false,
      localTask: this.task,
      deleteDialog: false,
      editDialog: false,
      addFormDialog: false
    };
  },
  created() {
    Event.listen("cancelEdit", data => {
      this.editDialog = false;
    });
    Event.listen("processTaskUpdated", data => {
      this.editDialog = false;
    });
    Event.listen("processTaskCreated", data => {
      this.localTask = data;
    });
    Event.listen("formAdded", data => {
      this.addFormDialog = false;
    });
    Event.listen("cancelSubmit", data => {
      this.addFormDialog = false;
    })
  }
};
</script>