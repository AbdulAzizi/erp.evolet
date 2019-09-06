<template>
  <div>
    <v-row>
      <v-col cols="12" sm="6" md="4">
        <v-card>
          <v-toolbar dark flat dense color="primary">
            <v-toolbar-title>
              <h4>Основное</h4>
            </v-toolbar-title>
          </v-toolbar>
          <v-list dense>
            <v-list-item>
              <v-list-item-icon>
                <v-icon>mdi-account</v-icon>
              </v-list-item-icon>
              <v-list-item-content>{{resume.name}} {{resume.surname}}</v-list-item-content>
            </v-list-item>
            <v-list-item>
              <v-list-item-icon>
                <v-icon>mdi-calendar-range</v-icon>
              </v-list-item-icon>
              <v-list-item-content>{{ moment(resume.birthday).format("L") }}</v-list-item-content>
            </v-list-item>
            <v-list-item>
              <v-list-item-icon>
                <v-icon>mdi-phone</v-icon>
              </v-list-item-icon>
              <v-list-item-content>{{resume.phone}}</v-list-item-content>
            </v-list-item>
            <v-list-item>
              <v-list-item-icon>
                <v-icon>mdi-human-male-female</v-icon>
              </v-list-item-icon>
              <v-list-item-content>{{resume.male_female}}</v-list-item-content>
            </v-list-item>
            <v-list-item>
              <v-list-item-icon>
                <v-icon>mdi-shield-half-full</v-icon>
              </v-list-item-icon>
              <v-list-item-content>{{resume.military_status}}</v-list-item-content>
            </v-list-item>
          </v-list>
        </v-card>
      </v-col>
      <v-col cols="12" sm="6" md="4">
        <resume-card
          title="Образование"
          :check="user.id == resume.creator || user.division.abbreviaton == 'ДЧ'"
          :resume="resume.educations"
          type="education"
          main_icon="mdi-school"
          deleteUrl="/api/deleteEducation/"
          firstMainLine="name"
          firstSecondaryLine="degree"
          :secondLineItems="['specialty', 'start_at', 'end_at']"
        >
          <resume-add-item
            v-if="user.division.abbreviation == 'ДЧ' || resume.creator == user.id"
            :resume="resume"
            title="Добавить образование"
            url="/api/education"
            :form="education"
            returnDataEvent="educationAdded"
          />
        </resume-card>
      </v-col>
      <v-col cols="12" sm="6" md="4">
        <resume-card
          title="Опыт работы"
          :check="user.id == resume.creator || user.division.abbreviaton == 'ДЧ'"
          :resume="resume.jobs"
          main_icon="mdi-office-building"
          deleteUrl="/api/deleteJob/"
          firstMainLine="company_name"
          firstSecondaryLine="location"
          :secondLineItems="['position', 'start_at', 'end_at']"
        >
          <resume-add-item
            v-if="user.division.abbreviation == 'ДЧ' || resume.creator == user.id"
            :resume="resume"
            title="Добавить место работы"
            url="/api/job"
            :form="job"
            returnDataEvent="jobAdded"
          />
        </resume-card>
      </v-col>
      <v-col cols="12" sm="6" md="4">
        <resume-card
          title="Семейное положение"
          :check="user.id == resume.creator || user.division.abbreviaton == 'ДЧ'"
          :resume="resume.families"
          main_icon="mdi-account-group"
          deleteUrl="/api/deleteFamily/"
          firstMainLine="name"
          firstSecondaryLine="relation"
          :secondLineItems="['birthday']"
        >
          <resume-add-item
            v-if="user.division.abbreviation == 'ДЧ' || resume.creator == user.id"
            :resume="resume"
            title="Добавить члена семьи"
            url="/api/family"
            :form="family"
            returnDataEvent="familyAdded"
          />
        </resume-card>
      </v-col>
      <v-col cols="12" sm="6" md="4">
        <resume-card
          title="Languages"
          :check="user.id == resume.creator || user.division.abbreviaton == 'ДЧ'"
          :resume="resume.languages"
          main_icon="mdi-chat"
          deleteUrl="/api/deleteLanguage/"
          firstMainLine="name"
          :secondLineItems="['level']"
        >
          <resume-add-item
            v-if="user.division.abbreviation == 'ДЧ' || resume.creator == user.id"
            :resume="resume"
            title="Добавить язык"
            url="/api/language"
            :form="language"
            returnDataEvent="languageAdded"
          />
        </resume-card>
      </v-col>
      <v-col cols="12" sm="6" md="4">
        <resume-card
          title="Достижения"
          :check="user.id == resume.creator || user.division.abbreviaton == 'ДЧ'"
          :resume="resume.achievments"
          main_icon="mdi-certificate"
          deleteUrl="/api/deleteAchievment/"
          firstMainLine="type"
          :secondLineItems="['description']"
        >
          <resume-add-item
            v-if="user.division.abbreviation == 'ДЧ' || resume.creator == user.id"
            :resume="resume"
            title="Добавить достижение"
            url="/api/achievment"
            :form="achievment"
            returnDataEvent="achievmentAdded"
          />
        </resume-card>
      </v-col>
    </v-row>
    <v-btn color="primary" @click="window.back()">Назад</v-btn>
    <v-btn dark color="primary darken-1" :href="`/resume-pdf/${resume.id}`">Экспортировать в PDF</v-btn>
  </div>
</template>

<script>

export default {
  props: ["resume", "user"],

  data() {
    return {
      localUser: this.resume,
      window: window.history,
      education: {
        colsPerRow: [4, 4, 4, 12, 12],
        fields: [
          {
            label: "Степень",
            type: "select",
            name: "degree",
            items: ["Среднее", "Бакалавр", "Специалитет", "Магистр"],
            rules: ["required"]
          },
          {
            label: "Дата начало",
            type: "date",
            name: "start_at",
            rules: ["required"]
          },
          {
            label: "Дата окончания",
            type: "date",
            name: "end_at",
            rules: ["required"]
          },
          {
            label: "Название",
            type: "string",
            name: "name",
            rules: ["required"]
          },
          {
            label: "Название",
            type: "string",
            name: "specialty",
            rules: ["required"]
          }
        ]
      },
      job: {
        colsPerRow: [4, 4, 4, 12, 12],
        fields: [
          {
            label: "Название",
            type: "string",
            name: "company_name",
            rules: ["required"]
          },
          {
            label: "Дата начало",
            type: "date",
            name: "start_at",
            rules: ["required"]
          },
          {
            label: "Дата окончания",
            type: "date",
            name: "end_at",
            rules: ["required"]
          },
          {
            label: "Позиция",
            type: "string",
            name: "position",
            rules: ["required"]
          },
          {
            label: "Местоположение",
            type: "string",
            name: "location",
            rules: ["required"]
          }
        ]
      },
      family: {
        colsPerRow: [4, 4, 4],
        fields: [
          {
            label: "Степень родства",
            type: "select",
            name: "relation",
            items: ["Муж", "Жена", "Отец", "Мать", "Сын", "Дочь"],
            rules: ["required"]
          },
          {
            label: "Дата рождения",
            type: "date",
            name: "birthday",
            rules: ["required"]
          },
          {
            label: "Имя",
            type: "string",
            name: "name",
            rules: ["required"]
          }
        ]
      },
      language: {
        colsPerRow: [6, 6],
        fields: [
          {
            label: "Язык",
            type: "string",
            name: "name",
            rules: ["required"]
          },
          {
            label: "Уровень",
            type: "select",
            name: "level",
            items: [
              "Beginner",
              "Pre-Intermidiate",
              "Intermidiate",
              "Upper-Intermidiate",
              "Advanced"
            ],
            rules: ["required"]
          }
        ]
      },
      achievment: {
        colsPerRow: [6],
        fields: [
          {
            label: "Тип",
            type: "select",
            name: "type",
            items: ["Спорт", "Наука"],
            rules: ["required"]
          },
          {
            label: "Описание",
            type: "text",
            name: "description",
            rules: ["required"]
          }
        ]
      }
    };
  },
  created() {
    Event.listen("educationAdded", data => {
      console.log("Event listened");

      this.localUser.educations.push(data);
    });
    Event.listen("jobAdded", data => {
      console.log("Event listened");

      this.localUser.jobs.push(data);
    });
    Event.listen("familyAdded", data => {
      console.log("Event listened");

      this.localUser.families.push(data);
    });
    Event.listen("languageAdded", data => {
      console.log("Event listened");

      this.localUser.languages.push(data);
    });
    Event.listen("achievmentAdded", data => {
      console.log("Event listened");

      this.localUser.achievments.push(data);
    });
  }
};
</script>
