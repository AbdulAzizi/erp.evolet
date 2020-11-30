<template>
  <div>
    <v-card flat>
      <v-autocomplete
        v-model="filters.id"
        solo
        :items="users"
        placeholder="Поиск"
        hide-details="auto"
        item-text="name"
        item-value="id"
        clearable
        prepend-inner-icon="mdi-magnify"
        @click="loadUsers()"
      >
        <template v-slot:selection="data">
          <v-chip v-bind="data.attrs" :input-value="data.selected" @click="data.select">
            <v-avatar left>
              <v-img :src="thumb(data.item.img)"></v-img>
            </v-avatar>
            {{ data.item.name }} {{ data.item.surname }}
          </v-chip>
        </template>
        <template v-slot:item="data">
          <template>
            <v-list-item-avatar>
              <img :src="thumb(data.item.img)" />
            </v-list-item-avatar>
            <v-list-item-content>
              <v-list-item-title>{{data.item.name}} {{data.item.surname}}</v-list-item-title>
            </v-list-item-content>
          </template>
        </template>
      </v-autocomplete>
      <v-progress-linear v-if="loader" color="primary" indeterminate height="5"></v-progress-linear>
    </v-card>
      <requests-table v-if="localRequests.length > 0" :requests="localRequests" />
    <div class="text-center">
      <v-btn v-if="requestsAmount > 0" fixed depressed class="font-weight-bold text-capitalize" color="primary" @click="filterRequests()">Еще {{ requestsAmount }}</v-btn>
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
      search: null,
      users: [],
      laoder: false,
      total: null,
      page: 1,
      filters: {
        isHead: true,
        id: null
      }
    };
  },
  methods: {
    filterRequests() {
      this.loader = true;
      axios
        .get("/api/getRequests", {
          params: {
            ...this.filters,
            page: this.page
          }
        })
        .then(res => {
          this.loader = false;
          this.localRequests.push(...res.data.data);
          this.total = res.data.total;
          this.page++;
        });
    },
    loadUsers() {
      if (!this.users.length)
        axios
          .get("/api/requests/head/users")
          .then(res => this.users.push(...res.data));
    },
    removeRequest(requestID) {
      this.localRequests.forEach((item, i) => {
        if (item.id === requestID) this.localRequests.splice(i, 1);
      });
    }
  },
  computed: {
    id() {
      return this.filters.id;
    },
    requestsAmount() {
      return this.total - this.localRequests.length;
    }
  },
  created() {
    this.filterRequests();
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
  },
  watch: {
    id(val) {
      this.page = 1;
      this.localRequests = [];
      this.filterRequests();
    }
  }
};
</script>

<style>
.height {
  height: calc(100vh - 130px);
}
</style>
