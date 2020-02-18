<template>
  <div>
    <v-card>
      <v-toolbar dense flat dark color="primary">
        <v-toolbar-title v-if="addHeadEmployee">Добавить руководителя</v-toolbar-title>
        <v-toolbar-title v-else>Добавить сотрудника</v-toolbar-title>
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
          type: 'email',
          name: 'email',
          label: 'Email',
          rules: ['required'],
          error: emailError,
          errorMsg: errorMessage
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
            v-if="!addHeadEmployee"
          ></form-field>
          <form-field
            :field="{
          type: 'autocomplete',
          name: 'positionId',
          label: 'Должность',
          items: positionItems,
          multiple: true
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
  props: {
    division: {
      required: true
    },
    addHeadEmployee: {
      required: false
    }
  },
  data() {
    return {
      name: null,
      surname: null,
      email: null,
      errorMessage: null,
      emailError: false,
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
            divisionId: this.division.id,
            headEmployee: this.addHeadEmployee
          })
          .then(res => {
            if (res.data.emailError) {
              this.emailError = true,
              this.errorMessage = res.data.emailError;
            } else {
              Event.fire("userAdded", {
                divisionId: this.division.id,
                user: res.data,
                headEmployee: this.addHeadEmployee
              });
              form.reset();
            }
          })
          .catch(err => err.message);
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
      this.positionItems = this.loadDivisionPositions(this.division.id);
    });

    Event.listen("deletePosition", data => {
      this.positionItems = this.loadDivisionPositions(this.division.id);
    });
  },
  watch: {
    email(newVal, oldVal){
      if(newVal !== oldVal){
        this.errorMessage = null;
        this.emailError = false;
      }
    }
  }
};
</script>