<template>
  <v-dialog width="500" v-model="dialog" persistent>
    <template v-slot:activator="{ on }">
      <v-btn icon small v-on="on">
        <v-icon>mdi-trash-can-outline</v-icon>
      </v-btn>
    </template>
    <v-card>
      <v-card-title>Действительно хотите удалить заявку?</v-card-title>
      <v-card-actions>
          <v-spacer />
        <v-btn class="font-weight-bold" text color="primary" @click="dialog = false">Отмена</v-btn>
        <v-btn class="font-weight-bold" dark depressed color="red lighten-2" @click="remove()">Удалить</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>
<script>
export default {
  props: {
    requestId: {
      required: true
    }
  },
  data() {
    return {
      dialog: false
    };
  },
  methods: {
    remove() {
      axios
        .delete(`/api/requests/${this.requestId}`)
        .then(res => Event.fire("requestRemoved", this.requestId))
        .catch(err => Event.fire('notify',['Ошибка при удалении']));
    }
  }
};
</script>