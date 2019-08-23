<template>
  <div>
    <profile-banner :user="user" />
    <resume-edit :user="user" v-if="!showed"></resume-edit>
    <v-card v-if="showed">
      <!-- <v-card-title>
                <h2>Резюме</h2>
      </v-card-title>-->
      <v-card-text>
        <v-row>
          <v-col cols="12" sm="6" md="4">
            <h2>Основное</h2>
            <v-card outlined class="mt-3 mb-3">
              <v-card-text>
                <v-icon>mdi-calendar-range</v-icon>
                {{ moment(user.resume.birthday).format("L") }}
                <p>Телефон: {{user.resume.phone}}</p>
                <p>Пол: {{user.resume.male_female}}</p>
                <p>Военная обязанность: {{user.resume.military_status}}</p>
              </v-card-text>
            </v-card>
          </v-col>
          <v-col cols="12" sm="6" md="4">
            <h2>Образование</h2>
            <v-card
              v-for="(degree, index) in user.resume.educations"
              v-bind:key="index"
              outlined
              class="mt-3 mb-3"
            >
              <v-card-text>
                <p>Степень: {{degree.degree}}</p>
                <p>Период обучения: {{degree.start_at}} - {{degree.end_at}}</p>
                <p>Учереждение: {{degree.name}}</p>
                <p>Специальность: {{degree.specialty}}</p>
              </v-card-text>
            </v-card>
          </v-col>
          <v-col cols="12" sm="6" md="4">
            <h2>Опыт работы</h2>
            <v-card
              v-for="(job, index) in user.resume.jobs"
              v-bind:key="index"
              outlined
              class="mt-3 mb-3"
            >
              <v-card-text>
                <p>Место работы: {{job.company_name}}</p>
                <p>Позиция: {{job.position}}</p>
                <p>Местоположение: {{job.location}}</p>
                <p>Период: {{ datesPeriod(job.end_at, job.start_at) }}</p>
              </v-card-text>
            </v-card>
          </v-col>
          <v-col cols="12" sm="6" md="4">
            <h2>Семейное положение</h2>
            <v-card
              v-for="(family, index) in user.resume.families"
              v-bind:key="index"
              outlined
              class="mt-3 mb-3"
            >
              <v-card-text>
                <p>ФИО: {{family.name}}</p>
                <p>Дата рождения: {{family.birthday}}</p>
                <p>Степень родства: {{family.relation}}</p>
              </v-card-text>
            </v-card>
          </v-col>
          <v-col cols="12" sm="6" md="4">
            <h2>Знание языков</h2>
            <v-card
              v-for="(language, index) in user.resume.languages"
              v-bind:key="index"
              outlined
              class="mt-3 mb-3"
            >
              <v-card-text>
                <p>Язык: {{language.name}}</p>
                <p>Уровень: {{language.level}}</p>
              </v-card-text>
            </v-card>
          </v-col>
          <v-col cols="12" sm="6" md="4">
            <h2>Достижения</h2>
            <v-card
              v-for="(achievment, index) in user.resume.achievments"
              v-bind:key="index"
              outlined
              class="mt-3 mb-3"
            >
              <v-card-text>
                <p>Тип: {{achievment.type}}</p>
                <p>Описание: {{achievment.description}}</p>
              </v-card-text>
            </v-card>
          </v-col>
        </v-row>
        <v-btn @click="showed = false">Изменить</v-btn>
      </v-card-text>
    </v-card>
  </div>
</template>

<script>
export default {
  props: ["user"],

  data() {
    return {
      showed: true
    };
  },

  methods: {
    datesPeriod(end, start) {
      let a = this.moment(end);
      let b = this.moment(start);
      return a.diff(b, "days");
    }
  }
};
</script>
