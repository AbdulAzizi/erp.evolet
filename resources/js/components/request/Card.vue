<template>
  <v-row class="ma-0">
    <v-col cols="12" class="pa-0">
      <v-card flat>
        <v-toolbar flat class="px-6 custom-toolbar">
          <v-toolbar-title class="pa-0 font-weight-bold primary--text">{{ request.type }}</v-toolbar-title>
          <v-spacer></v-spacer>
          <dismiss-request-btn :request="request" />
          <accept-request-btn :request="request" />
          <update-request-btn v-if="auth.id === request.user_id" :request="request" />
          <delete-request-btn v-if="auth.id === request.user_id" :requestId="request.id" />
          <print-request-btn :request="request" />
        </v-toolbar>
        <v-divider></v-divider>
        <v-card-text class="px-5">
          <div class="d-flex justify-space-between flex-row align-center">
            <h3 class="font-weight-bold">Сотрудник</h3>
            <div>
              <v-avatar size="50">
                <v-tooltip bottom>
                  <template v-slot:activator="{ on:tooltip }">
                    <img v-on="{ ...tooltip }" :src="thumb(request.user.img) " alt="avatar" />
                  </template>
                  <span>{{ request.user.name }} {{ request.user.surname }}</span>
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
                dark
                :color="status[request.status].color"
              >{{ status[request.status].text }}</v-chip>
            </div>
          </div>
          <div
            :class="request.type == 'Оборудования' && request.parameters.length > 2 ? 'd-flex justify-start flex-column mt-2' : 'd-flex justify-space-between flex-row align-center mt-2'"
          >
            <h3 class="font-weight-bold">{{ parameterText(request.type) }}</h3>
            <div>
              <v-chip
                small
                :class="request.parameters.length > 2 ? 'mr-1 mt-1 font-weight-bold' : 'ml-1 mt-1 font-weight-bold'"
                dark
                label
                color="primary"
                v-for="(parameter, i) in request.parameters"
                :key="`param-${i}`"
              >{{ parameter.value }}</v-chip>
            </div>
          </div>

          <div v-if="request.description" class="mt-2 d-flex justify-start flex-column">
            <h3 class="font-weight-bold">Описание</h3>
            <p :class="more ? 'ma-0 pt-1' : 'text-truncate ma-0 pt-1'">{{ request.description }}</p>
            <v-btn
              icon
              small
              v-if="request.description.length >= 60"
              absolute
              right
              @click="more = !more"
            >
              <v-icon v-if="!more">mdi-chevron-down</v-icon>
              <v-icon v-if="more">mdi-chevron-up</v-icon>
            </v-btn>
          </div>
          <div
            class="mt-2 d-flex justify-start flex-column"
            v-if="request.status == 3 && request.message"
          >
            <h3 class="font-weight-bold">Сообщение</h3>
            <p :class="msgMore ? 'ma-0 pt-1' : 'text-truncate ma-0 pt-1'">{{ request.message }}</p>
            <v-btn
              icon
              small
              v-if="request.message.length >= 60"
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
  </v-row>
</template>

<script>
export default {
  props: {
    request: {
      required: true
    },
    displayInDialog: {
      required: false
    }
  },
  data() {
    return {
      dialog: false,
      msgMore: false,
      message: null,
      currentRequestID: null,
      localRequest: this.request,
      headers: ["Тип", "Статус", "Действия"],
      more: false,
      status: [
        {
          text: "На рассмотрении",
          color: "orange lighten-1"
        },
        {
          text: "Рассмотрено",
          color: "orange lighten-1"
        },
        {
          text: "Одобрено",
          color: "green lighten-1"
        },
        {
          text: "Отклонено",
          color: "red lighten-1"
        }
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
    cancel() {
      const FORM = this.$refs.messageForm;
      FORM.reset();
      this.dialog = false;
    },
    isHeadOfDivision(userID) {
      return (
        this.auth.id === this.auth.division.head_id &&
        this.auth.id !== userID &&
        !this.isHead()
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