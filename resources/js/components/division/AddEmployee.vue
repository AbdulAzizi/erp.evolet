<template>
  <div>
    <v-card>
      <v-toolbar dense flat dark color="primary">
        <v-toolbar-title>Добавить сотрудника</v-toolbar-title>
      </v-toolbar>
      <v-card-text>
        <v-form class="mt-5" ref="addEmployeeForm">
          <form-field
            :field="{
          type: 'string',
          name: 'name',
          label: 'Имя',
          rules: ['required']
          }"
            v-model="name"
          ></form-field>
          <form-field
            :field="{
          type: 'string',
          name: 'surname',
          label: 'Фамилия',
          rules: ['required']
          }"
            v-model="surname"
          ></form-field>
          <form-field
            :field="{
         type: 'string',
          name: 'email',
          label: 'Email',
          rules: ['required']
          }"
            v-model="email"
          ></form-field>
          <form-field
            :field="{
          type: 'select',
          name: 'positionId',
          label: 'Позиционный уровень',
          items: positionLevelItems,
          rules: ['required']
          }"
            v-model="positionLevel"
          ></form-field>
          <form-field
            :field="{
          type: 'autocomplete',
          name: 'positionId',
          label: 'Должность',
          items: positionItems,
          multiple: true,
          rules: ['required']
          }"
            v-model="positions"
          ></form-field>
        </v-form>
      </v-card-text>
      <v-card-actions>
        <v-spacer />
        <v-btn text color="primary" @click="resetForm()">отмена</v-btn>
        <v-btn color="primary" @click="submitForm()">создать</v-btn>
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
      positionLevel: null,
      positions: [],
      positionItems: this.loadDivisionPositions(this.division.id),
      positionLevelItems: this.loadPositionLevels()
    };
  },
  methods: {
    submitForm() {
      const form = this.$refs.addEmployeeForm;
      if (form.validate()) {
        axios
          .post("/api/users", {
            name: this.name,
            surname: this.surname,
            email: this.email,
            positionId: this.positionLevel,
            positions: this.positions,
            divisionId: this.division.id
          })
          .then(res => {
            Event.fire("userAdded", res.data);
            form.reset();
          })
          .catch(err => err.messages);
      }
    },
    resetForm() {
      const form = this.$refs.addEmployeeForm;
      Event.fire("cancelEmployeeSubmition");
      form.reset();
    }
  },
  created() {
    Event.listen("newPosition", data => {
      this.positionItems = this.loadDivisionPositions(
        this.division.id
      );
    });

    Event.listen("deletePosition", data => {
      this.positionItems = this.loadDivisionPositions(
        this.division.id
      );
    });
  }
};
</script>