<template>
  <v-card>
    <v-toolbar dense flat dark color="primary">
      <v-toolbar-title>Добавить сотрудника</v-toolbar-title>
    </v-toolbar>
    <v-card-text>
      <v-form ref="form" class="mt-5">
        <form-field
          :field="{
                    label: 'Имя',
                    name: 'userName',
                    type: 'string',
                    rules: ['required']
                }"
          v-model="user.name"
        ></form-field>

        <form-field
          :field="{
                    label: 'Фамилия',
                    name: 'userSurname',
                    type: 'string',
                    rules: ['required']
                }"
          v-model="user.surname"
        ></form-field>

        <form-field
          :field="{
                    label: 'Почта',
                    name: 'userEmail',
                    type: 'string',
                    rules: ['required']
                }"
          v-model="user.email"
        ></form-field>

        <form-field
          :field="{
                    label: 'Должностная позиция',
                    name: 'userPosition',
                    type: 'select',
                    rules: ['required'],
                    items: positionLevels
                }"
          v-model="user.positionLevel"
        ></form-field>

        <form-field
          :field="{
                    label: 'Отдел',
                    name: 'userDivision',
                    type: 'autocomplete',
                    rules: ['required'],
                    items: divisions
                }"
          v-model="user.division"
        ></form-field>
      </v-form>
    </v-card-text>
    <v-card-actions>
      <v-spacer />
      <v-btn text color="primary" @click="cancel()">отмена</v-btn>
      <v-btn color="primary" @click="submit()">создать</v-btn>
    </v-card-actions>
  </v-card>
</template>
<script>
export default {
  props: ["dialog"],
  data() {
    return {
      user: {
        name: null,
        surname: null,
        email: null,
        positionLevel: null,
        division: null
      },
      positionLevels: this.loadPositionLevels(),
      divisions: this.loadDivisions()
    };
  },
  methods: {
    submit() {
      const form = this.$refs.form;
      if (form.validate) {
        axios
          .post("/api/hr/users/create", {
            user: this.user
          })
          .then(res => {
            Event.fire("userAdded", res.data);
            form.reset();
          })
          .catch(err => err.messages);
      }
    },
    cancel(){
        const form = this.$refs.form;
        form.reset();
        Event.fire('cancelUserAdding');
    }
  }
};
</script>