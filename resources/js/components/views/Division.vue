<template>
  <v-flex>
    <v-tabs v-model="currentView" background-color="white" class="d-inline-flex justify-end my-4" grow>
      <v-tab :value="activeBtn.STRUCTURE">Структура</v-tab>
      <v-tab :value="activeBtn.RESPONSIBILITY">Объязанности</v-tab>
    </v-tabs>

    <v-tabs-items v-model="currentView" class="transparent">
      <!-- <v-tabs-items v-model="currentView" class="transparent"> -->
      <v-tab-item>
        <division-structure :division="division" :is-user-head="isUserHead" :is-root="true" />
      </v-tab-item>
      <v-tab-item>
        <division-responsibilities :division="division" :user="user" />
      </v-tab-item>
    </v-tabs-items>
  </v-flex>
</template>

<script>
export default {
  props: ["division", "isUserHead", "isRoot", "oldInputs", "errors", "user"],
  data() {
    return {
      // isDivision: true,
      tab: null,
      // items: [],
      currentView: null
    };
  },

  mounted() {
    this.currentView = this.localCurrentView;
  },

  computed: {
    activeBtn() {
      return Object.freeze({
        STRUCTURE: 1,
        RESPONSIBILITY: 2
      });
    },
    localCurrentView() {
      if (!localStorage.hasOwnProperty("currentView")) {
        return this.activeBtn.STRUCTURE;
      }
      return parseInt(localStorage.currentView);
    },
    isStructure() {
      return this.currentView === this.activeBtn.STRUCTURE;
    },
    isResponsibility() {
      return this.currentView === this.activeBtn.RESPONSIBILITY;
    }
  },

  watch: {
    currentView(value) {
      localStorage.currentView = value;
    }
  },

  methods: {
    addUser: function() {
      Event.fire("addUser", [
        {
          type: "input",
          name: "divisionId",
          value: this.division.id
        }
      ]);
    }
  }
};
</script>

<style>
.v-expansion-panel:before {
  box-shadow: none;
}
</style>