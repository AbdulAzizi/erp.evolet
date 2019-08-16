<template>
    <v-card-text fluid  class="pa-0">
      <v-card-text class="pt-0 px-0">
        <v-layout justify-end>
          <v-btn-toggle v-model="text">
            <v-btn
              text
              value="table"
              color="primary"
              @click="selectTab(1)"
              ><v-icon>mdi-table-large</v-icon></v-btn>
            <v-btn
              text
              value="calendar"
              color="primary"
              @click="selectTab(2)"
            ><v-icon>mdi-calendar-month</v-icon></v-btn>
          </v-btn-toggle>
        </v-layout>
      </v-card-text>

      <tasks-table :tasks="tasks" v-if="currentTab == 1"></tasks-table>
      <tasks-calendar :tasks="tasks" v-if="currentTab == 2"></tasks-calendar>

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
      toggle_none: null,
      toggle_one: 0,
      toggle_exclusive: 2,
      toggle_multiple: [0, 1, 2],
      // firstSwitcher: true,
      // secondSwitcher: false,
      currentTab: 1,
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
.block_toggle_btn {
  padding: 0;
}
</style>
