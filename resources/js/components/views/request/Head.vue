<template>
  <div>
    <div id="filters" class="d-flex flex-row justify-start">
      <v-card flat class="mr-2">
        <v-autocomplete
          solo
          v-model="filters.user"
          :items="users"
          placeholder="Сотрудник"
          hide-details="auto"
          item-text="name"
          item-value="id"
          clearable
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
      <v-card flat v-if="ceo" class="mr-2">
        <v-autocomplete
          v-model="filters.division"
          solo
          placeholder="Отдел"
          hide-details="auto"
          :items="divisions"
          item-text="abbreviation"
          item-value="id"
          clearable
          @click="loadDivisions()"
        ></v-autocomplete>
      </v-card>
      <v-card flat class="mr-2">
        <v-autocomplete
          v-model="filters.type"
          solo
          placeholder="Тип заявки"
          hide-details="auto"
          :items="requestTypes"
          clearable
        ></v-autocomplete>
      </v-card>
      <v-card flat class="mr-2">
        <v-autocomplete
          v-model="filters.status"
          solo
          :items="statuses"
          placeholder="Статус"
          hide-details="auto"
          item-text="text"
          item-value="id"
          clearable
        ></v-autocomplete>
      </v-card>
    </div>
    <requests-table v-if="localRequests.length > 0" :requests="localRequests" />
    <div class="text-center">
      <v-btn
        v-if="requestsAmount > 0"
        fixed
        depressed
        class="font-weight-bold text-capitalize"
        color="primary"
        @click="filterRequests()"
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
      search: null,
      users: [],
      divisions: [],
      laoder: false,
      total: null,
      page: 1,
      filters: {
        isHead: true,
        division: null,
        user: null,
        status: null,
        type: null
      },
      requestTypes: [
        "Оборудования",
        "Отпуск",
        "Повышение зарплаты",
        "Больничный",
        "Аванс"
      ],
      statuses: [
        {
          text: "На рассмотрении",
          id: 0
        },
        {
          text: "Рассмотрено",
          id: 1
        },
        {
          text: "Одобрено",
          id: 2
        },
        {
          text: "Отклонено",
          id: 3
        }
      ]
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
    loadDivisions() {
      if (!this.divisions.length) {
        axios
          .get("/api/divisions")
          .then(res => this.divisions.push(...res.data));
      }
    },
    removeRequest(requestID) {
      this.localRequests.forEach((item, i) => {
        if (item.id === requestID) this.localRequests.splice(i, 1);
      });
    },
    applyFilters() {
      this.total = null;
      this.page = 1;
      this.localRequests = [];
      this.filterRequests();
    }
  },
  computed: {
    user() {
      return this.filters.user;
    },
    division() {
      return this.filters.division;
    },
    status() {
      return this.filters.status;
    },
    type() {
      return this.filters.type;
    },
    requestsAmount() {
      return this.total - this.localRequests.length;
    },
    ceo() {
      return this.auth.positions.some(el => el.name == 'HR' || el.name == 'РВЗ');
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
    user() {
      this.applyFilters();
    },
    division() {
      this.applyFilters();
    },
    status() {
      this.applyFilters();
    },
    type() {
      this.applyFilters();
    }
  }
};
</script>

<style>
.height {
  height: calc(100vh - 130px);
}

#filters
  .v-text-field.v-text-field--solo:not(.v-text-field--solo-flat)
  > .v-input__control
  > .v-input__slot {
  box-shadow: none !important;
}
</style>
