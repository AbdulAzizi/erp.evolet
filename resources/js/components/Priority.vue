<template>
  <div>
    <v-tooltip bottom v-if="icon">
      <template v-slot:activator="{ on:tooltip }">
        <v-icon
          v-on="{ ...tooltip }"
          :color="priorities[id].color"
          :class="classes"
        >mdi-flag-variant</v-icon>
      </template>
      <span>{{priorities[id].label}} Приоритет</span>
    </v-tooltip>
    <v-list-item v-else>
      <v-list-item-avatar>
        <v-icon :color="priorities[id].color" :class="classes">mdi-flag-variant</v-icon>
      </v-list-item-avatar>
      <v-list-item-content>
        <v-list-item-title>{{priorities[id].label}}</v-list-item-title>
        <v-list-item-subtitle>Приоритет</v-list-item-subtitle>
      </v-list-item-content>
    </v-list-item>
  </div>
</template>

<script>
export default {
  props: {
    id: {
      required: true
    },
    classes: {
      required: false
    },
    icon: {
      required: false,
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      priorities: [
        { id: 0, label: "Низкий", color: "green lighten-3" },
        { id: 1, label: "Средний", color: "blue lighten-3" },
        { id: 2, label: "Высокий", color: "red lighten-3" }
      ]
    };
  }
};
</script>

<style>
</style>
