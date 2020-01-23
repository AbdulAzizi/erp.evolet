<template>
  <v-row justify="end">
    <v-btn color="primary" @click="showForm" bottom right fab dark fixed>
      <v-icon dark>mdi-plus</v-icon>
    </v-btn>

    <dynamic-form
      title="Добавить должностные обязоности"
      v-show="true"
      dialog
      method="post"
      action-url="/users/{id}/position"
      activatorEventName="showForm"
      :fields="[
            {
              type: 'select',
              name: 'position_id',
              label: 'Полномочия',
              items:  this.positions,
              rules: ['required']
            },
            {
              type: 'text',
              name: 'text',
              label: 'Описание',
              rules: ['required']
            }
          ]"
    />
  </v-row>
</template>


<script>
export default {
  props: ["user", "positions"],
  data() {
    return {};
  },
  methods: {
    showForm() {
      Event.fire("showForm");
    }
  }
};
</script>