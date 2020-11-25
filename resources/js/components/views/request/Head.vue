<template>
  <div>
    <v-text-field
      v-model="search"
      solo
      placeholder="Поиск"
      hide-details="auto"
      clearable
      prepend-inner-icon="mdi-magnify"
    ></v-text-field>
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
      localRequests: [],
      search: null
    };
  },
  methods: {
    loadRequests() {
      axios
        .get("/api/getRequests", {
          params: {
            isHead: true,
          }
        })
        .then(res => this.localRequests.push(...res.data.data))
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
    Event.listen("requestApproved", data => {
      this.localRequests.forEach(item => {
        if (item.id === data.request.id) Object.assign(item, data.request);
      });
      Event.fire("notify", [data.message]);
    });
    Event.listen("requestStatusChanged", request => {
      this.localRequests.forEach(item => {
        if (item.id === request.id) Object.assign(item, request);
      });
      Event.fire("notify", ["Заявка отклонена"]);
    });
  }
};
</script>

<style>
.height {
  height: calc(100vh - 130px);
}
</style>
