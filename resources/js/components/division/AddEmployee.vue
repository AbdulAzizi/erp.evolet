<template>
  <div>
    <v-card>
      <v-toolbar dense flat dark color="primary">
        <v-toolbar-title>Добавить сотрудника</v-toolbar-title>
      </v-toolbar>
      <v-card-text>
        <v-form class="mt-5">
          <form-field
            :field="{
          type: 'string',
          name: 'name',
          label: 'Имя',
          rules: ['required']
          }"
          v-model="name"></form-field>
          <form-field
            :field="{
          type: 'string',
          name: 'surname',
          label: 'Фамилия',
          rules: ['required']
          }"
          v-model="surname"></form-field>
          <form-field
            :field="{
         type: 'string',
          name: 'email',
          label: 'Email',
          rules: ['required']
          }"
          v-model="email"></form-field>
          <form-field
            :field="{
          type: 'select',
          name: 'positionId',
          label: 'Должность',
          items: positionItems,
          rules: ['required']
          }"
          v-model="position"></form-field>
          <form-field
            :field="{
          type: 'autocomplete',
          name: 'responsibilityId',
          label: 'Полномочия',
          items: responsibilityItems,
          multiple: true,
          rules: ['required']
          }"
          v-model="responsibilities"></form-field>
        </v-form>
      </v-card-text>
      <v-card-actions>
        <v-spacer />
        <v-btn @click="submitForm()">создать</v-btn>
      </v-card-actions>
    </v-card>
  </div>
</template>

<script>
export default {
  props: ["division"],
  data() {
    return {
        name: null,
        surname: null,
        email: null,
        position: null,
        responsibilities: [],
        responsibilityItems: this.loadDivisionResponsibilities(this.division.id),
        positionItems: this.loadPositions()
    };
  },
  methods: {
    submitForm() {
      axios
        .post("/api/users", {
          name: this.name,
          surname: this.surname,
          email: this.email,
          positionId: this.position,
          responsibilities: this.responsibilities,
          divisionId: this.division.id
        })
        .then(res => {
          Event.fire('userAdded', res.data);
        })
        .catch(err => err.messages);
    },
  },
  created(){
      Event.listen('responsibilityAdded', data => {
          this.responsibilityItems = this.loadDivisionResponsibilities(this.division.id)
      })
  }
};
</script>