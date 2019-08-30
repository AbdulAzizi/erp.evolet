<template>
  <div>
    <v-row>
      <v-col cols="4" v-for="(resume, index) in resumes" :key="index">
        <v-card>
          <v-card-title>
            <a :href="`/resume/${resume.id}`">{{resume.name}} {{resume.surname}}</a>
          </v-card-title>
          <v-card-text>{{resume.birthday}}</v-card-text>
        </v-card>
      </v-col>
    </v-row>
    <v-btn @click="dialog = true" color="primary">Создать резюме</v-btn>
    <v-dialog width="800" v-model="dialog">
      <v-card>
        <v-toolbar dense dark flat color="primary">
          <v-toolbar-title>Создать резюме</v-toolbar-title>
        </v-toolbar>
        <v-card-text>
          <v-form method="post" action="/resume" ref="resumeForm">
            <input type="hidden" name="_token" :value="csrf_token" />
            <input type="hidden" name="own" :value="own" />
            <v-row>
              <v-col cols="4">
                <form-field :field="fields.name"></form-field>
              </v-col>
              <v-col cols="4">
                <form-field :field="fields.surname"></form-field>
              </v-col>
              <v-col cols="4">
                <form-field :field="fields.birthday"></form-field>
              </v-col>
              <v-col cols="4">
                <form-field :field="fields.gender"></form-field>
              </v-col>
              <v-col cols="4">
                <form-field :field="fields.military_status"></form-field>
              </v-col>
              <v-col cols="4">
                <form-field :field="fields.phone"></form-field>
              </v-col>
            </v-row>
            <v-btn color="primary" @click="onSubmit">Создать</v-btn>
          </v-form>
        </v-card-text>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
export default {
  props: ["resumes"],
  data() {
    return {
      own: false,
      dialog: false,
      csrf_token: window.Laravel.csrf_token,
      fields: {
        name: {
          label: "Имя",
          type: "string",
          name: "name",
          rules: ["required"]
        },
        surname: {
          label: "Фамилия",
          type: "string",
          name: "surname",
          rules: ["required"]
        },
        birthday: {
          label: "Дата рождения",
          name: "birthday",
          type: "date",
          rules: ["required"]
        },
        gender: {
          label: "Пол",
          name: "gender",
          type: "select",
          items: ["Мужской", "Женский"],
          rules: ["required"]
        },
        military_status: {
          label: "Военная обязанность",
          name: "military_status",
          type: "select",
          items: ["Обязан", "Не обязан"],
          rules: ["required"]
        },
        phone: {
          label: "Номер телефона",
          name: "phone",
          type: "string",
          rules: ["required"]
        }
      }
    };
  },
  methods: {
    onSubmit() {
      let form = this.$refs.resumeForm;
      if (form.validate()) {
        this.$refs.resumeForm.$el.submit();
      }
    }
  }
};
</script>
