<template>
  <v-dialog v-model="dialog" width="500" persistent>
    <template v-slot:activator="{ on }">
      <v-btn v-if="show" icon color="red lighten-1" v-on="on" dark small depressed>
        <v-icon>mdi-close-outline</v-icon>
      </v-btn>
    </template>
    <v-toolbar color="primary" dense dark flat>
      <v-toolbar-title class="font-weight-bold">Причина отказа</v-toolbar-title>
    </v-toolbar>
    <v-card tile>
      <v-form ref="messageForm">
        <v-card-text>
          <v-textarea
            v-model="message"
            placeholder="Напишите причину отказа"
            filled
            rounded
            hide-details
          ></v-textarea>
        </v-card-text>
      </v-form>
      <v-card-actions class="px-5 pb-3">
        <v-spacer />
        <v-btn text class="font-weight-bold" color="primary" @click="cancel()">Отмена</v-btn>
        <v-btn
          depressed
          class="font-weight-bold"
          color="primary"
          :disabled="!message"
          @click="dismiss()"
        >Отправить</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>
<script>
export default {
  props: {
    request: {
      required: true
    }
  },
  data() {
    return {
      dialog: false,
      message: null
    };
  },
  methods: {
    dismiss() {
      axios
        .post(`/api/requests/${this.request.id}/changeStatus`, {
          status: 3,
          message: this.message
        })
        .then(res => {
          Event.fire("requestStatusChanged", res.data);
          this.message = null;
          this.dialog = false;
        });
    },
    cancel() {
      this.message = null;
      this.dialog = false;
    }
  },
  computed: {
    show() {
      return (
        (this.role.headOfDivision && this.request.status == 0) ||
        (this.role.ceo && this.request.status < 2)
      );
    },
    role() {
      const headOfDivision =
        this.auth.id === this.auth.division.head_id && !ceo;
      const ceo = this.auth.positions.some(
        el => el.name == "ОУПС" || el.name == "РВЗ"
      );
      return {
        headOfDivision: headOfDivision,
        ceo: ceo
      };
    }
  }
};
</script>