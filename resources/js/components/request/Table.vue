<template>
  <v-row>
    <v-col cols="4" v-for="item in localRequests" :key="item.id">
      <v-card flat>
        <v-toolbar flat class="px-6 custom-toolbar">
          <v-toolbar-title class="pa-0 font-weight-bold primary--text">{{ item.type }}</v-toolbar-title>
          <v-spacer></v-spacer>
          <update-request-btn :request="item" />
          <delete-request-btn :requestId="item.id" />
        </v-toolbar>
        <v-divider class="mx-5"></v-divider>
        <v-card-text class="px-5">
          <div class="d-flex justify-space-between flex-row align-center">
            <h3 class="font-weight-bold">Статус</h3>
            <v-chip
              class="font-weight-bold"
              label
              small
              :color="status[item.status].color"
            >{{ status[item.status].text }}</v-chip>
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

          <div class="mt-2 d-flex justify-start flex-column">
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
              <v-icon>mdi-chevron-down</v-icon>
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
    requests: {
      required: true
    }
  },
  data() {
    return {
      localRequests: this.requests,
      headers: ["Тип", "Статус", "Действия"],
      more: false,
      status: [
        {
          text: "На рассмотрении",
          color: "orange"
        },
        {
          text: "Одобрено",
          color: "green"
        },
        { text: "Отклонено", color: "red" }
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
    }
  }
};
</script>

<style>
.custom-toolbar .v-toolbar__content {
  padding: 0 !important;
}
</style>