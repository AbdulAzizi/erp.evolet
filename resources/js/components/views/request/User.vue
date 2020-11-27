<template>
  <div>
    <v-card outlined>
      <v-card-text class="d-flex justify-space-between align-center">
        <h1>Мои Заявки</h1>
        <create-request-btn />
      </v-card-text>
    </v-card>
    <requests-table :requests="localRequests" v-if="localRequests.length > 0" />
    <div class="text-center">
      <v-btn
        v-if="requestsAmount > 0"
        fixed
        depressed
        class="font-weight-bold text-capitalize"
        color="primary"
        @click="loadRequests()"
      >Еще {{ requestsAmount }}</v-btn>
    </div>
    <div class="d-flex justify-center align-center height" v-if="localRequests.length == 0">
      <h3 class="grey--text text--darken-2">У Вас нет заявок</h3>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      localRequests: [],
      totalRequests: null,
      page: 1,
      total: null
    };
  },
  methods: {
    loadRequests() {
      this.loader = true;
      axios
        .get("/api/getRequests", {
          params: {
            isUser: true,
            page: this.page
          }
        })
        .then(res => {
          this.loader = false;
          this.localRequests.push(...res.data.data);
          this.page++;
          this.total = res.data.total;
        });
    },
    removeRequest(requestID) {
      this.localRequests.forEach((item, i) => {
        if (item.id === requestID) this.localRequests.splice(i, 1);
      });
    }
  },
  computed: {
    requestsAmount() {
      return this.total - this.localRequests.length;
    }
  },
  created() {
    this.loadRequests();
    Event.listen("requestCreated", data => {
      this.localRequests.push(data);
      Event.fire("notify", ["Ваша заявка успешно создана"]);
    });
    Event.listen("requestUpdated", request => {
      this.localRequests.forEach(item => {
        if (item.id === request.id) {
          Object.assign(item, request);
        }
      });
      Event.fire("notify", ["Заявка успешно обновлена"]);
    });
    Event.listen("requestRemoved", requestID => {
      this.removeRequest(requestID);
      Event.fire("notify", ["Заявка удалена"]);
    });
  }
};
</script>

<style>
.height {
  height: calc(100vh - 130px);
}
</style>