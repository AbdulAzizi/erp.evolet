<template>
  <div>
    <v-form>
      <v-card>
        <v-card-text>
          <v-row>
            <v-col cols="12" sm="6" md="4">
              <h2>Основное</h2>
              <v-card outlined class="mt-3 mb-3" width="400">
                <v-card-text>

                  <form-field
                    :field="{
                                name: 'birthday',
                                label: 'Дата рождения',
                                type: 'date',
                                rules: ['required'],
                                value: birthday
                            }"
                    v-model="birthday"
                  ></form-field>

                  <form-field
                    :field="{
                                name: 'phone',
                                label: 'Телефон',
                                type: 'string',
                                rules: ['required'],
                                value: phone
                            }"
                    v-model="phone"
                  ></form-field>

                  <form-field
                    :field="{
                                name: 'gender',
                                label: 'Пол',
                                type: 'select',
                                rules: ['required'],
                                value: gender,
                                items: ['Мужской', 'Женский']
                            }"
                    v-model="gender"
                  ></form-field>

                  <form-field
                    :field="{
                                name: 'military_status',
                                label: 'Военная объязанность',
                                type: 'select',
                                rules: ['required'],
                                value: 'Объязан',
                                items: ['Объязан', 'Не объязан'],
                            }"
                    v-model="military_status"
                  ></form-field>

                </v-card-text>
              </v-card>
            </v-col>
            <v-dialog v-model="degreeDialog" width="800">
                    <v-card>
                      <v-toolbar color="primary" dark flat>
                        <v-toolbar-title>
                          <h4>Добавить образование</h4>
                        </v-toolbar-title>
                      </v-toolbar>
                      <v-card-text>
                        <v-form ref="educationAddForm">
                          <v-row>
                            <v-col cols="4">
                              <form-field
                                :field="{
                                type: 'select',
                                items: ['Среднее', 'Бакалавр', 'Специалитет', 'Магистр'],
                                name: 'degree',
                                label: 'Степень',
                                rules: ['required']
                            }"
                                v-model="dialogDegree.type"
                              />
                            </v-col>
                            <v-col cols="4">
                              <form-field
                                :field="{
                                type: 'date',
                                name: 'start_at',
                                label: 'Начало обучения',
                                rules: ['required'],
                            }"
                                v-model="dialogDegree.start_at"
                              />
                            </v-col>
                            <v-col cols="4">
                              <form-field
                                :field="{
                                type: 'date',
                                name: 'end_at',
                                label: 'Конец обучения',
                                rules: ['required'],
                            }"
                                v-model="dialogDegree.end_at"
                              />
                            </v-col>
                            <v-col cols="12">
                              <form-field
                                :field="{
                                    type: 'string',
                                    name: 'institute',
                                    label: 'Название учебного заведения',
                                    rules: ['required'],
                                }"
                                v-model="dialogDegree.institute"
                              />
                            </v-col>
                            <v-col cols="12">
                              <form-field
                                :field="{
                                    type: 'string',
                                    name: 'specialty',
                                    label: 'Специальность',
                                    rules: ['required'],
                                }"
                                v-model="dialogDegree.specialty"
                              />
                            </v-col>
                          </v-row>
                          <v-btn color="primary" @click="addDegree">Сохранить</v-btn>
                          <v-btn color="red lighten-2" dark @click="degreeDialog = false">Отмена</v-btn>
                        </v-form>
                      </v-card-text>
                    </v-card>
                  </v-dialog>
            <v-col cols="12" sm="6" md="4">
              <h2>Образование</h2>
              <v-card
                v-for="(education, index) in user.resume.educations"
                v-bind:key="index"
                outlined
                class="mt-3 mb-3"
                width="400"
              >
                <v-card-text>
                  <form-field
                    :field="{
                        name: 'type',
                        label: 'Степень',
                        type: 'select',
                        rules: ['required'],
                        items: ['Среднее', 'Бакалавр', 'Специалист'],
                        value: education.degree,
                    }"
                    v-model="degree.type"
                  ></form-field>

                  <v-row>
                    <v-col cols="6">
                      <form-field
                        :field="{
                            name: 'start_at',
                            label: 'Дата начало',
                            type: 'date',
                            rules: ['required'],
                            value: education.start_at,
                            }"
                        v-model="degree.start_at"
                      ></form-field>
                    </v-col>

                    <v-col cols="6">
                      <form-field
                        :field="{
                            name: 'end_at',
                            label: 'Дата окончания',
                            type: 'date',
                            rules: ['required'],
                            value: education.end_at,
                            }"
                        v-model="degree.start_at"
                      ></form-field>
                    </v-col>
                  </v-row>

                  <form-field
                    :field="{
                        name: 'institute',
                        label: 'Учереждение',
                        type: 'string',
                        rules: ['required'],
                        value: education.name,
                    }"
                    v-model="degree.institute"
                  ></form-field>

                  <form-field
                    :field="{
                        name: 'specialty',
                        label: 'Специальность',
                        type: 'string',
                        rules: ['required'],
                        value: education.specialty,
                    }"
                    v-model="degree.specialty"
                  ></form-field>

                </v-card-text>
              </v-card>
              <v-btn @click="degreeDialog = true" color="primary" fab>
                <v-icon>+</v-icon>
              </v-btn>
            </v-col>
            <v-col cols="12" sm="6" md="4">
              <h2>Опыт работы</h2>
              <v-card
                v-for="(job, index) in user.resume.jobs"
                v-bind:key="index"
                outlined
                class="mt-3 mb-3"
                width="400"
              >
                <v-card-text>
                  <form-field
                    :field="{
                        name: 'type',
                        label: 'Имя компании',
                        type: 'string',
                        rules: ['required'],
                        value: job.company_name,
                    }"
                    v-model="job.name"
                  ></form-field>
                  <form-field
                    :field="{
                        name: 'position',
                        label: 'Позиция',
                        type: 'string',
                        rules: ['required'],
                        value: job.position,
                }"
                    v-model="job.position"
                  ></form-field>
                  <form-field
                    :field="{
                        name: 'location',
                        label: 'Местоположение',
                        type: 'string',
                        rules: ['required'],
                        value: job.location,
                }"
                    v-model="job.location"
                  ></form-field>
                  <v-row>
                    <v-col cols="6">
                      <form-field
                        :field="{
                                name: 'start_at',
                                label: 'Дата начало',
                                type: 'date',
                                rules: ['required'],
                                value: job.start_at,
                            }"
                        v-model="job.start_at"
                      ></form-field>
                    </v-col>
                    <v-col cols="6">
                      <form-field
                        :field="{
                                name: 'end_at',
                                label: 'Дата окончания',
                                type: 'date',
                                rules: ['required'],
                                value: job.end_at,
                            }"
                        v-model="job.end_at"
                      ></form-field>
                    </v-col>
                  </v-row>
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
                width="400"
              >
                <v-card-text>
                  <v-row>
                    <v-col cols="6">
                      <form-field
                        :field="{
                        name: 'familyType',
                        label: 'Степень родства',
                        type: 'select',
                        rules: ['required'],
                        items: ['Мать', 'Отец', 'Муж', 'Жена', 'Сын', 'Дочь', 'Брат', 'Сестра'],
                        value: family.relation,
                    }"
                        v-model="family.type"
                      ></form-field>
                    </v-col>
                    <v-col cols="6">
                      <form-field
                        :field="{
                        name: 'familyBirthday',
                        label: 'Дата рождения',
                        type: 'date',
                        rules: ['required'],
                        value: family.birthday,
                    }"
                        v-model="family.birthday"
                      ></form-field>
                    </v-col>
                  </v-row>
                  <form-field
                    :field="{
                        name: 'famiyName',
                        label: 'ФИО',
                        type: 'string',
                        rules: ['required'],
                        value: family.name,
                }"
                    v-model="family.name"
                  ></form-field>
                </v-card-text>
              </v-card>
            </v-col>
            <v-col cols="12" sm="6" md="4">
              <h2>Знание Языков</h2>
              <v-card
                v-for="(language, index) in user.resume.languages"
                v-bind:key="index"
                outlined
                class="mt-3 mb-3"
                width="400"
              >
                <v-card-text>
                  <form-field
                    :field="{
                        name: 'name',
                        label: 'Язык',
                        type: 'select',
                        rules: ['required'],
                        items: ['Английский', 'Немецкий', 'Русский', 'Французский'],
                        value: language.name,
                    }"
                    v-model="language.name"
                  ></form-field>
                  <form-field
                    :field="{
                        name: 'level',
                        label: 'Уровень',
                        type: 'string',
                        rules: ['required'],
                        items: [
                                'Beginner', 'Pre-Intermidiate',
                                'Intermidiate',
                                'Upper-Intermidiate',
                                'Advanced'
                                ],
                        value: language.level,
                    }"
                    v-model="language.level"
                  ></form-field>
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
                width="400"
              >
                <v-card-text>
                  <form-field
                    :field="{
                        name: 'achievmentType',
                        label: 'Тип',
                        type: 'select',
                        rules: ['required'],
                        items: ['Спорт', 'Наука'],
                        value: achievment.type,
                    }"
                    v-model="language.name"
                  ></form-field>
                  <form-field
                    :field="{
                        name: 'achievmentDescription',
                        label: 'Описание',
                        type: 'text',
                        value: achievment.description,
                    }"
                    v-model="achievment.name"
                  ></form-field>
                </v-card-text>
              </v-card>
            </v-col>
          </v-row>
        </v-card-text>
      </v-card>
    </v-form>
  </div>
</template>

<script>
export default {
  props: ["user", "resume"],

  data() {
    return {
      birthday: this.user.resume.birthday,
      phone: this.user.resume.phone,
      gender: this.user.resume.male_female,
      military_status: this.user.resume.military_status,

      degree: {
        type: null,
        end_at: null,
        start_at: null,
        institute: null,
        specialty: null
      },

      job: {
        name: null,
        position: null,
        location: null,
        start_at: null,
        end_at: null
      },

      family: {
        name: null,
        type: null,
        birthday: null
      },

      language: {
        name: null,
        level: null
      },

      achievment: {
        type: null,
        name: null
      },

      degrees: [],

      dialogDegree: {
        type: null,
        end_at: null,
        start_at: null,
        institute: null,
        specialty: null
      },

      degreeDialog: false
    };
  },

  methods: {
    addDegree() {
        const valid = this.$refs.educationAddForm.validate();

        if (valid) {
          this.degrees.push({
            degree: this.dialogdegree.type,
            end_at: this.dialogDegree.end_at,
            start_at: this.dialogDegree.start_at,
            institute: this.dialogDegree.institute,
            specialty: this.dialogDegree.specialty
          });

            this.$refs.educationAddForm.reset();

            this.degreeDialog = true;
        }
    },
    addJob() {
      const valid = this.$refs.jobs.validate();

      if (valid) {
        this.jobs.push({
          name: this.job.name,
          position: this.job.position,
          location: this.job.location,
          start_at: this.job.start_at,
          end_at: this.job.end_at
        });

        this.$refs.jobs.reset();

        this.jobDialog = false;
      }
    },
    addFamily() {
      const valid = this.$refs.family.validate();

      if (valid) {
        this.families.push({
          name: this.family.name,
          type: this.family.type,
          birthday: this.family.birthday
        });

        this.$refs.family.reset();

        this.familyDialog = false;
      }
    },
    addLanguage() {
      const valid = this.$refs.language.validate();

      if (valid) {
        this.languages.push({
          name: this.language.name,
          level: this.language.level
        });

        this.$refs.language.reset();

        this.languageDialog = false;
      }
    },
    addAchievment() {
      const valid = this.$refs.achievment.validate();

      if (valid) {
        this.achievments.push({
          name: this.achievment.name,
          type: this.achievment.type
        });

        this.$refs.achievment.reset();

        this.achievmentDialog = false;
      }
    }
  },
  mounted() {
    console.log(this.$refs);
  }
};
</script>
