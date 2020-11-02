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
  props: {
    requests: {
      required: true
    }
  },
  data() {
    return {
      localRequests: this.requests
    };
  },
  created() {
    Event.listen("requestCreated", data => {
      this.localRequests.push(data);
      Event.fire("notify", ["Ваша заявка успешно создана"]);
    });
    Event.listen("requestUpdated", data => {
      let i = 0;
      let len = this.localRequests.length;

      for (i; i < len; i++) {
        let item = this.localRequests[i];

        if (item.id === data.id) this.localRequests.splice(i, 1);
      }
      this.localRequests.push(data);
      Event.fire("notify", ["Заявка успешно обновлена"]);
    });
    Event.listen("requestRemoved", requestId => {
      let i = 0;
      let len = this.localRequests.length;

      for (i; i < len; i++) {
        let item = this.localRequests[i];

        if (requestId === item.id) this.localRequests.splice(i, 1);
      }
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
