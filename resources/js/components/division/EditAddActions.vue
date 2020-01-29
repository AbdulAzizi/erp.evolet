<template>
  <div>
    <v-dialog width="440" v-model="warningDialog">
      <v-card>
        <v-card-title class="headline">Вы действительно хотите удалить полномочие?</v-card-title>
        <v-card-actions>
          <v-spacer />
          <v-btn text color="red lighten-2" @click="warningDialog = !warningDialog">нет</v-btn>
          <v-btn text color="primary" @click="deletePosition()">да</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-menu bottom left>
      <template v-slot:activator="{ on }">
        <v-btn v-on="on" dark icon small>
          <v-icon>mdi-dots-vertical</v-icon>
        </v-btn>
      </template>
      <v-list>
        <v-list-item dense @click="editPosition()">Изменить</v-list-item>
        <v-list-item dense @click="warningDialog = !warningDialog">Удалить</v-list-item>
      </v-list>
    </v-menu>
  </div>
</template>
<script>
export default {
  props: ["position"],
  data() {
    return {
      formDialog: false,
      warningDialog: false,
      disabled: true,
    };
  },
  methods: {
    editPosition() {
      Event.fire("editPosition", this.position.id);
    },
    displayDeleteWarning() {
      this.deleteWarningDialog = true;
    },
    deletePosition() {
      axios
        .delete(`/api/delete/position/${this.position.id}`)
        .then(res => {
          Event.fire("deletePosition", this.position.id);
          this.warningDialog = false;
        })
        .catch(err => err.messages);
    },
  }
};
</script>