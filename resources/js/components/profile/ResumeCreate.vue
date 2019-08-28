<template>
  <div>
    <v-alert prominent type="warning" color="primary" v-if="!user.resume">
      <v-row align="center">
        <v-col class="grow" v-if="user.id !== permit">У пользователя {{user.name}} {{user.surname}} нету резюме</v-col>
        <v-col class="grow" v-if="user.id == permit">У вас пока нету резюме. Создайте</v-col>
        <v-col class="shrink">
          <v-btn dark outlined @click="dialog = true" v-if="permit == user.id">Создать резюме</v-btn>
        </v-col>
      </v-row>
    </v-alert>
    <v-dialog width="800" v-model="dialog">
      <v-card>
        <v-toolbar dense dark flat color="primary">
          <v-toolbar-title>Создать резюме</v-toolbar-title>
        </v-toolbar>
        <v-card-text>
          <v-form method="post" action="/api/resume" ref="resumeForm">
            <v-row>
              <v-col cols="4">
                <form-field
                  :field="{
                            label: 'Дата рождения',
                            name: 'birthday',
                            type: 'date',
                            rules: ['required']
                        }"
                ></form-field>
              </v-col>
              <v-col cols="4">
                <form-field
                  :field="{
                            label: 'Пол',
                            name: 'gender',
                            type: 'select',
                            items: ['Мужской', 'Женский'],
                            rules: ['required']
                        }"
                ></form-field>
              </v-col>
              <v-col cols="4">
                <form-field
                  :field="{
                            label: 'Военная обязанность',
                            name: 'military_status',
                            type: 'select',
                            items: ['Обязан', 'Не обязан'],
                            rules: ['required']
                        }"
                ></form-field>
              </v-col>
              <v-col cols="12">
                <form-field
                  :field="{
                            label: 'Номер телефона',
                            name: 'phone',
                            type: 'string',
                            rules: ['required']
                        }"
                ></form-field>
              </v-col>
            </v-row>
            <v-btn color="primary" :loading="loading" :disabled="loading" type="submit">Создать</v-btn>
          </v-form>
        </v-card-text>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
export default {
  props: ["user", "permit"],

  data() {
    return {
      dialog: false,
      loading: false,
    };
  }
};
</script>
