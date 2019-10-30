<template>
  <div>
    <v-row>
      <v-col class="pt-0">
        <v-btn-toggle v-model="currentView" active-class="primary" class="float-right" mandatory>
          <v-tooltip bottom>
            <template v-slot:activator="{ on }">
              <v-btn small text :value="activeBtn.TABLE" dark v-on="on">
                <v-icon :color="isTable ? 'white' : 'grey lighten-0'">mdi-table-of-contents</v-icon>
              </v-btn>
            </template>
            <span>таблица</span>
          </v-tooltip>

          <v-tooltip bottom>
            <template v-slot:activator="{ on }">
              <v-btn small text :value="activeBtn.CALENDAR" dark v-on="on">
                <v-icon :color="isCalendar ? 'white' : 'grey lighten-0'">mdi-calendar-month</v-icon>
              </v-btn>
            </template>
            <span>календарь</span>
          </v-tooltip>

          <v-tooltip bottom>
            <template v-slot:activator="{ on }">
              <v-btn small text :value="activeBtn.KANBAN" dark v-on="on">
                <v-icon :color="isKanban ? 'white' : 'grey lighten-0'">mdi-view-dashboard</v-icon>
              </v-btn>
            </template>
            <span>Канбан доска</span>
          </v-tooltip>
        </v-btn-toggle>
      </v-col>
    </v-row>

    <tasks-table :tasks="tasks" :users="users" v-show="isTable"></tasks-table>
    <tasks-calendar :tasks="tasks" v-show="isCalendar"></tasks-calendar>
    <kanban-view
      v-show="isKanban"
      :tasks="tasks"
      :users="users"
      :taskStatuses="statuses"
      :authuser="authuser"
    />
    <tasks-add :users="users" :errors="errors" :tags="tags" />
  </div>
</template>

<script>
export default {
  props: ["tasks", "users", "tags", "errors", "statuses", "authuser"],
  data() {
    return {
      currentView: null
    };
  },
  mounted() {
    this.currentView = this.localCurrentView;
  },
  computed: {
    activeBtn() {
      return Object.freeze({
        TABLE: 1,
        CALENDAR: 2,
        KANBAN: 3
      });
    },
    localCurrentView() {
      if (!localStorage.hasOwnProperty("currentView")) {
        return this.activeBtn.TABLE;
      }
      return parseInt(localStorage.currentView);
    },
    isTable() {
      return this.currentView === this.activeBtn.TABLE;
    },
    isCalendar() {
      return this.currentView === this.activeBtn.CALENDAR;
    },
    isKanban() {
      return this.currentView === this.activeBtn.KANBAN;
    }
  },
  watch: {
    currentView(value) {
      console.log(value);
      localStorage.currentView = value;
    }
  }
};
</script>


