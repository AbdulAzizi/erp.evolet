<template>
  <v-dialog v-model="dialog" width="600">
    <v-card>
      <v-card-title v-if="message">{{message}}</v-card-title>
      <v-card-title class="red--text text--lighten-1" v-if="caution">{{cautionMsg}}</v-card-title>
      <v-card-title v-else>Вы уверены что хотите удалить запись?</v-card-title>
      <v-card-actions v-if="caution">
        <v-spacer />
        <v-btn text color="primary" v-if="caution" @click.stop="dialog = false">Закрыть</v-btn>
      </v-card-actions>
      <v-card-actions v-else>
        <v-spacer />
        <v-btn text color="primary" @click.stop="dialog = false">Нет</v-btn>
        <v-btn text :disabled="caution" color="red lighten-2" @click.stop="deleteRecord()">Да</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>
<script>
export default {
  props: {
    visible: {
      required: true
    },
    route: {
      required: true
    },
    message: {
      required: false
    },
    caution: {
      required: false
    },
    cautionMsg: {
      required: false
    }
  },
  methods: {
    deleteRecord() {
      axios
        .delete(this.route)
        .then(res => window.location.reload())
        .catch(err => err.message);
    }
  },
  computed: {
    dialog: {
      get() {
        return this.visible;
      },
      set(value) {
        if (!value) {
          this.$emit("close");
        }
      }
    }
  }
};
</script>