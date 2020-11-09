<template>
  <v-row>
    <v-col cols="4" v-for="item in localRequests" :key="item.id">
      <v-card flat>
        <v-toolbar flat class="px-6 custom-toolbar">
          <v-toolbar-title class="pa-0 font-weight-bold primary--text">{{ item.type }}</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-btn
            icon
            color="red lighten-1"
            dark
            small
            depressed
            v-if="isHeadOfDivision(item.user_id) && !item.verified && item.status == 0 || isHead() && item.status == 0"
            @click="getCurrentRequestID(item.id)"
          >
            <v-icon>mdi-close-outline</v-icon>
          </v-btn>
          <v-btn
            icon
            class="ml-2"
            color="green lighten-1"
            dark
            small
            depressed
            v-if="isHeadOfDivision(item.user_id) && !item.verified && item.status == 0 || isHead() && item.status == 0"
            @click="verify(item.id)"
          >
            <v-icon>mdi-check-outline</v-icon>
          </v-btn>
          <update-request-btn v-if="auth.id === item.user_id" :request="item" />
          <delete-request-btn v-if="auth.id === item.user_id" :requestId="item.id" />
          <v-icon
            color="green lighten-1"
            v-if="isHeadOfDivision(item.user_id) && item.status == 0 && item.verified && !isHead()"
          >mdi-check-all</v-icon>
        </v-toolbar>
        <v-divider></v-divider>
        <v-card-text class="px-5">
          <div class="d-flex justify-space-between flex-row align-center">
            <h3 class="font-weight-bold">Сотрудник</h3>
            <div>
              <v-avatar size="50">
                <v-tooltip bottom>
                  <template v-slot:activator="{ on:tooltip }">
                    <img v-on="{ ...tooltip }" :src="thumb(item.user.img) " alt="avatar" />
                  </template>
                  <span>{{ item.user.name }} {{ item.user.surname }}</span>
                </v-tooltip>
              </v-avatar>
            </div>
          </div>
          <div class="mt-2 d-flex justify-space-between flex-row align-center">
            <h3 class="font-weight-bold">Статус</h3>
            <div>
              <v-chip
                class="font-weight-bold"
                label
                small
                :color="status[item.status].color"
              >{{ status[item.status].text }}</v-chip>
            </div>
          </div>
          <div
            :class="item.type == 'Оборудования' && item.parameters.length > 2 ? 'd-flex justify-start flex-column mt-2' : 'd-flex justify-space-between flex-row align-center mt-2'"
          >
            <h3 class="font-weight-bold">{{ parameterText(item.type) }}</h3>
            <div>
              <v-chip
                small
                :class="item.parameters.length > 2 ? 'mr-1 mt-1 font-weight-bold' : 'ml-1 mt-1 font-weight-bold'"
                dark
                label
                color="primary"
                v-for="(parameter, i) in item.parameters"
                :key="`param-${i}`"
              >{{ parameter.value }}</v-chip>
            </div>
          </div>

          <div v-if="item.description" class="mt-2 d-flex justify-start flex-column">
            <h3 class="font-weight-bold">Описание</h3>
            <p :class="more ? 'ma-0 pt-1' : 'text-truncate ma-0 pt-1'">{{ item.description }}</p>
            <v-btn
              icon
              small
              v-if="item.description.length >= 60"
              absolute
              right
              @click="more = !more"
            >
              <v-icon v-if="!more">mdi-chevron-down</v-icon>
              <v-icon v-if="more">mdi-chevron-up</v-icon>
            </v-btn>
          </div>
          <div class="mt-2 d-flex justify-start flex-column" v-if="item.status == 2 && item.message">
            <h3 class="font-weight-bold">Сообщение</h3>
            <p :class="msgMore ? 'ma-0 pt-1' : 'text-truncate ma-0 pt-1'">{{ item.message }}</p>
            <v-btn
              icon
              small
              v-if="item.message.length >= 60"
              absolute
              right
              @click="msgMore = !msgMore"
            >
              <v-icon v-if="!msgMore">mdi-chevron-down</v-icon>
              <v-icon v-if="msgMore">mdi-chevron-up</v-icon>
            </v-btn>
          </div>
        </v-card-text>
      </v-card>
    </v-col>
    <v-dialog v-model="dialog" width="500" persistent>
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
              hide-details="auto"
              :rules="required"
            ></v-textarea>
          </v-card-text>
        </v-form>
        <v-card-actions class="px-5 pb-3">
          <v-spacer />
          <v-btn text class="font-weight-bold" color="primary" @click="cancel()">Отмена</v-btn>
          <v-btn depressed class="font-weight-bold" color="primary" @click="decline()">Отправить</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-row>
</template>

<script>
export default {
  props: {
    requests: {
      required: true
    }
  },
  data() {
    return {
      dialog: false,
      msgMore: false,
      message: null,
      currentRequestID: null,
      localRequests: this.requests,
      headers: ["Тип", "Статус", "Действия"],
      more: false,
      required: [v => !!v || "Форма должна быть заполнена"],
      status: [
        {
          text: "На рассмотрении",
          color: "orange"
        },
        {
          text: "Одобрено",
          color: "green"
        },
        { text: "Отклонено", color: "red lighten-1" }
      ]
    };
  },
  methods: {
    parameterText(type) {
      switch (type) {
        case "Повышение зарплаты":
        case "Аванс":
          return "Сумма";
          break;
        case "Оборудования":
          return "Наименования";
          break;
        default:
          return "Период";
          break;
      }
    },
    verify(requestID) {
      axios
        .post(`/api/requests/${requestID}/verify`, {
          isHead: this.isHead()
        })
        .then(res => Event.fire("requestVerified", res.data))
        .catch(err => console.error(err.message));
    },
    decline() {
      const FORM = this.$refs.messageForm;
      if (FORM.validate())
        axios
          .post(`/api/requests/${this.currentRequestID}/changeStatus`, {
            status: 2,
            message: this.message
          })
          .then(res => {
            Event.fire("requestStatusChanged", res.data);
            this.dialog = false;
            FORM.reset();
          })
          .catch(err => this.cancel());
    },
    cancel() {
      const FORM = this.$refs.messageForm;
      FORM.reset();
      this.dialog = false;
    },
    isHeadOfDivision(userID) {
      return (
        this.auth.id === this.auth.division.head_id && this.auth.id !== userID
      );
    },
    isHead() {
      return (
        (this.auth.id === this.auth.division.head_id &&
          this.auth.positions.some(el => el.name === "ОУПС")) ||
        (this.auth.id === this.auth.division.head_id &&
          this.auth.positions.some(el => el.name === "РВЗ"))
      );
    },
    getCurrentRequestID(requestID) {
      this.currentRequestID = requestID;
      this.dialog = true;
    }
  },
  created() {}
};
</script>

<style>
.custom-toolbar .v-toolbar__content {
  padding: 0 !important;
}
</style>