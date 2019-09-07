<template>
  <div>
    <v-row>
      <v-col cols="12">
        <v-alert
          v-model="alert"
          dismissible
          color="primary"
          border="left"
          elevation="2"
          colored-border
          dense
        >
          <span class="grey--text text--darken-2">Дорогие сотрудники! Тут вы можете заполнить резюме кандидатов, которые у вас на примере, и мы рассмотрим их кандидатуру в случаи появления новых вакансий.</span>
        </v-alert>
      </v-col>
      <v-col cols="3" v-for="(resume, index) in resumes" :key="index">
        <v-card hover :href="`/resume/${resume.id}`">
          <v-list dense>
              <v-list-item>
                  <v-list-item-icon>
                      <v-icon color="primary">mdi-account</v-icon>
                  </v-list-item-icon>
                  <v-list-item-content>
                      <span class="grey--text text--darken-2">{{resume.name}} {{resume.surname}}</span>
                  </v-list-item-content>
              </v-list-item>
              <v-list-item>
                  <v-list-item-icon>
                      <v-icon color="primary">mdi-calendar-range</v-icon>
                  </v-list-item-icon>
                  <v-list-item-content>
                      <span class="grey--text text--darken-2">{{resume.birthday}}</span>
                  </v-list-item-content>
              </v-list-item>
              <v-list-item>
                  <v-list-item-icon>
                      <v-icon color="primary">mdi-school</v-icon>
                  </v-list-item-icon>
                  <v-list-item-content>
                      <span class="grey--text text--darken-2">
                          {{resume.educations[0] ? resume.educations[0].name + ' &#183; ' + resume.educations[0].specialty : 'Нет данных'}}
                        </span>
                  </v-list-item-content>
              </v-list-item>
              <v-list-item>
                  <v-list-item-icon>
                      <v-icon color="primary">mdi-chat</v-icon>
                  </v-list-item-icon>
                  <v-list-item-content>
                      <span class="grey--text text--darken-2">
                          {{resume.languages[0] ? resume.languages[0].name + ' &#183; ' + resume.languages[0].level : 'Нет данных'}}
                        </span>
                  </v-list-item-content>
              </v-list-item>
          </v-list>
        </v-card>
      </v-col>
    </v-row>
    <v-btn @click="dialog = true" color="primary" fixed fab bottom right >
        <v-icon>mdi-plus</v-icon>
    </v-btn>
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
          label: "Электронная почта",
          name: "email",
          type: "string",
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
