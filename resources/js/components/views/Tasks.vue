<template>
    <v-card-text fluid  class="pa-0">
      <v-card-text class="pt-0 px-0">
        <v-layout justify-end>
          <v-btn-toggle v-model="text" active-class="primary">
            <v-btn
              text
              value="table"
              @click="isTable = true"
              >
                <v-icon :color="isTable ? 'white': 'grey lighten-0'">mdi-table-large</v-icon>
              </v-btn>
            <v-btn
              text
              value="calendar"
              color="primary"
              @click="isTable = false"
            >
              <v-icon :color="!isTable ? 'white': 'grey lighten-0'">mdi-calendar-month</v-icon>
            </v-btn>
          </v-btn-toggle>
        </v-layout>
      </v-card-text>

      <tasks-table :tasks="tasks" v-if="isTable"></tasks-table>
      <tasks-calendar :tasks="tasks" v-if="!isTable"></tasks-calendar>

      <tasks-add :users="users" :errors="errors" :tags="tags" />
    </v-card-text>
  <!-- </v-container> -->
</template>

<script>
export default {
  props: ["tasks", "users", "errors", "tags"],
  data() {
    return {
      text: "table",
      icon: "justify",
      isTable: true,
      justify: "end"
    };
  },
  methods: {
    selectTab(selectedTab){
      this.currentTab = selectedTab
    }
  }
};
</script>

<style>

</style>
