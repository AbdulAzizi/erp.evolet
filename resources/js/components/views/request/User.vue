<template>
  <div>
    <v-card outlined>
      <v-card-text class="d-flex justify-space-between align-center">
        <h1>Заявки</h1>
        <create-request-btn />
      </v-card-text>
    </v-card>
    <requests-table :requests="localRequests" v-if="localRequests.length > 0" />
    <div class="d-flex justify-center align-center height" v-if="localRequests.length == 0">
      <h3 class="grey--text text--darken-2">У Вас нет заявок</h3>
    </div>
    <div class="d-flex justify-center align-center mb-2">
      <v-btn
        color="primary"
        depressed
        class="font-weight-bold"
        @click="loadMoreRequests()"
        v-if="(totalRequests - localRequests.length) > 0"
      >Еще {{totalRequests - localRequests.length}}</v-btn>
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
      filter: {
        type: null,
        status: null
      }
    };
  },
  methods: {
    loadMoreRequests() {
      this.page++;
      this.loadRequests();
    },
    changeView() {
      if(this.tableView) {
        this.tableView = false;
        this.cardView = true;
      } else {
        this.tableView = true;
        this.cardView = false;
      }
    },
    loadRequests() {
      axios
        .get("/api/getRequests", {
          params: {
            isUser: true,
            type: this.filter.type,
            status: this.filter.status,
            page: this.page
          }
        })
        .then(res => {
          this.totalRequests = res.data.total;
          this.localRequests.push(...res.data.data);
        })
        .then(err => err.messages);
    },
    removeRequest(requestID) {
      this.localRequests.forEach((item, i) => {
        if (item.id === requestID) this.localRequests.splice(i, 1);
      });
    }
  },
  created() {
    this.loadRequests();
    Event.listen("loadRequests", page => {
      this.page = page;
      this.loadRequests();
    });
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