<template>
  <v-row justify="end">
    <v-btn class="mt-4 mr-4" color="primary" @click="showForm" fab dark>
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
              name: 'responsibilityId',
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
  props: ["responsibilities"],
  data() {
    console.log(this.responsibilities);
    return {};
  },
  methods: {
    showForm() {
      Event.fire("showForm");
    },
    addJobDescription() {
      Event.fire("addJobDescription", [
        {
          type: "input",
          name: "responsibilityId'",
          value: this.user.responsibilities
        }
      ]);
    }
  }
};
</script>