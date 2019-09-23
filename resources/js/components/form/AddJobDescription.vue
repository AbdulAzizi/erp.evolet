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
      action-url="/users/{id}/responsibility"
      activatorEventName="showForm"
      :fields="[
            {
              type: 'select',
              name: 'responsibility_id',
              label: 'Полномочия',
              items:  this.responsibilities,
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
  props: ["user","responsibilities"],
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