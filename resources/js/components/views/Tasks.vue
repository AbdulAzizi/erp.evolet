<template>
  <div>
    <v-row>
      <v-col class="pt-0">
        <v-btn-toggle v-model="text" active-class="primary" class="float-right">
          <v-tooltip bottom>
            <template v-slot:activator="{ on }">
              <v-btn small text value="table" @click="isTable = true" dark v-on="on">
                <v-icon :color="isTable ? 'white': 'grey lighten-0'">mdi-table-of-contents</v-icon>
              </v-btn>
            </template>
            <span>таблица</span>
          </v-tooltip>

          <v-tooltip bottom>
            <template v-slot:activator="{ on }">
              <v-btn small text value="calendar" @click="selectTab" dark v-on="on">
                <v-icon
                  :color="!isTable && isCalendar ? 'white': 'grey lighten-0'"
                >mdi-calendar-month</v-icon>
              </v-btn>
            </template>
            <span>календарь</span>
          </v-tooltip>
          <v-tooltip bottom>
            <template v-slot:activator="{ on }">
              <v-btn
                small
                text
                value="kanban"
                @click="isTable = false, isCalendar = false"
                dark
                v-on="on"
              >
                <v-icon
                  :color="!isTable && !isCalendar ? 'white': 'grey lighten-0'"
                >mdi-reorder-vertical</v-icon>
              </v-btn>
            </template>
            <span>Канбан доска</span>
          </v-tooltip>
        </v-btn-toggle>
      </v-col>
    </v-row>

    <tasks-table :tasks="tasks" :users="users" v-if="isTable"></tasks-table>
    <tasks-calendar :tasks="tasks" v-if="!isTable && isCalendar"></tasks-calendar>
    <kanban-view v-if="!isTable && !isCalendar" :tasks="tasks" :users="users" :taskStatuses="statuses" :authuser="authuser" />
    <tasks-add :users="users" :errors="errors" :tags="tags"/>
  </div>
</template>

<script>
export default {
  props: ['tasks',
    'users',
    'tags',
    'errors',
    'statuses',
    'authuser'],
  data() {
    return {
      text: "table",
      icon: "justify",
      isTable: true,
      isCalendar: false,
      justify: "end"
    };
  },
  methods: {
    selectTab() {
      this.isTable = false;
      this.isCalendar = true;
    }
  }
};
</script>


