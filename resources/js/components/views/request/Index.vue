<template>
  <div>
    <v-card outlined>
      <v-card-text class="d-flex justify-space-between align-center">
        <h1>Мои Заявки</h1>
        <create-request-btn />
      </v-card-text>
    </v-card>
    <requests-table :requests="localRequests" v-if="localRequests.length > 0" />
    <div class="d-flex justify-center align-center height" v-else>
      <h3 class="grey--text text--darken-2">У Вас нет заявок</h3>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      localRequests: []
    };
  },
  methods: {
    loadRequests() {
      const authID = this.auth.id;
      const headOfDivisionID = this.auth.division.head_id;

      axios
        .post("/api/getRequests", {
           isHeadOfDivision: authID === headOfDivisionID
        })
        .then(res => this.localRequests.push(...res.data))
        .then(err => err.messages);
    },
    removeRequest(requestID) {
      this.localRequests.forEach((item, i) => {
        if(item.id === requestID) 
          this.localRequests.splice(i, 1);
      })
    }
  },
  created() {
    this.loadRequests();
    Event.listen("requestCreated", data => {
      this.localRequests.push(data);
      Event.fire("notify", ["Ваша заявка успешно создана"]);
    });
    Event.listen("requestUpdated", data => {
      this.localRequests.forEach(item => {
        if(item.id === data.id) {
          Object.assign(item, data);
        }
      })
      Event.fire("notify", ["Заявка успешно обновлена"]);
    });
    Event.listen("requestRemoved", requestID => {
     this.removeRequest(requestID)
     Event.fire("notify", ["Заявка удалена"]);
    });
    // Event.listen('requestVerified', requestId => {

    // })
  }
};
</script>

<style>
.height {
  height: calc(100vh - 130px);
}
</style>
