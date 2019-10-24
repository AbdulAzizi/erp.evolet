<template>
  <v-flex>
    <v-tabs v-model="tab" background-color="white" class="d-inline-flex justify-end my-4" grow>
      <v-tab>Структура</v-tab>
      <v-tab>Обязанности</v-tab>
    </v-tabs>

    <v-tabs-items v-model="tab" class="transparent">
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
      isDivision: true,
      tab: 1,
      items: []
    };
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