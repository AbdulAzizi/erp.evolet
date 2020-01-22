<template>
  <div>
    <profile-banner :user="user" />
    <resume-create :user="user" v-if="!user.resume[0]" :permit="permit"></resume-create>
    <v-row v-if="user.resume[0]">
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
                <v-icon>mdi-calendar-range</v-icon>
              </v-list-item-icon>
              <v-list-item-content>{{ moment(user.resume[0].birthday).format("L") }}</v-list-item-content>
            </v-list-item>
            <v-list-item>
              <v-list-item-icon>
                <v-icon>mdi-phone</v-icon>
              </v-list-item-icon>
              <v-list-item-content>{{user.resume[0].phone}}</v-list-item-content>
            </v-list-item>
            <v-list-item>
              <v-list-item-icon>
                <v-icon>mdi-email</v-icon>
              </v-list-item-icon>
              <v-list-item-content>{{user.resume[0].email}}</v-list-item-content>
            </v-list-item>
            <v-list-item>
              <v-list-item-icon>
                <v-icon>mdi-human-male-female</v-icon>
              </v-list-item-icon>
              <v-list-item-content>{{user.resume[0].male_female}}</v-list-item-content>
            </v-list-item>
          </v-list>
        </v-card>
      </v-col>
      <v-col cols="12" sm="6" md="4">
        <resume-card
          :user="user"
          :check="user.id == permit"
          title="Образование"
          :resume="user.resume[0].educations"
          type="education"
          main_icon="mdi-school"
          deleteUrl="/api/deleteEducation/"
          firstMainLine="name"
          firstSecondaryLine="degree"
          :secondLineItems="['specialty', 'start_at', 'end_at']"
        >
          <resume-add-item
            :user="user"
            title="Добавить образование"
            url="/api/education"
            :form="education"
            :returnDataEvent="education.event"
            v-if="user.id == permit"
          />
        </resume-card>
      </v-col>
      <v-col cols="12" sm="6" md="4">
        <resume-card
          :user="user"
          :check="user.id == permit"
          title="Опыт работы"
          :resume="user.resume[0].jobs"
          type="job"
          main_icon="mdi-office-building"
          deleteUrl="/api/deleteJob/"
          firstMainLine="company_name"
          firstSecondaryLine="location"
          :secondLineItems="['positionLevel', 'start_at', 'end_at']"
        >
          <resume-add-item
            :user="user"
            title="Добавить место работы"
            url="/api/job"
            :form="job"
            :returnDataEvent="job.event"
            v-if="user.id == permit"
          />
        </resume-card>
      </v-col>
      <v-col cols="12" sm="6" md="4">
        <resume-card
          :user="user"
          :check="user.id == permit"
          title="Состав семьи"
          :resume="user.resume[0].families"
          main_icon="mdi-account-group"
          deleteUrl="/api/deleteFamily/"
          firstMainLine="name"
          firstSecondaryLine="relation"
          :secondLineItems="['birthday']"
        >
          <resume-add-item
            :user="user"
            title="Добавить члена семьи"
            url="/api/family"
            :form="family"
            :returnDataEvent="family.event"
            v-if="user.id == permit"
          />
        </resume-card>
      </v-col>
      <v-col cols="12" sm="6" md="4">
        <resume-card
          :check="user.id == permit"
          :user="user"
          title="Знание языков"
          :resume="user.resume[0].languages"
          type="language"
          main_icon="mdi-chat"
          deleteUrl="/api/deleteLanguage/"
          firstMainLine="name"
          :secondLineItems="['level']"
        >
          <resume-add-item
            :user="user"
            title="Добавить язык"
            url="/api/language"
            :form="language"
            :returnDataEvent="language.event"
            v-if="user.id == permit"
          />
        </resume-card>
      </v-col>
      <v-col cols="12" sm="6" md="4">
        <resume-card
          :check="user.id == permit"
          :user="user"
          title="Достижения"
          :resume="user.resume[0].achievments"
          main_icon="mdi-certificate"
          deleteUrl="/api/deleteAchievment/"
          firstMainLine="type"
          :secondLineItems="['description']"
        >
          <resume-add-item
            :user="user"
            title="Добавить достижение"
            url="/api/achievment"
            :form="achievment"
            :returnDataEvent="achievment.event"
            v-if="user.id == permit"
          />
        </resume-card>
      </v-col>

      <v-col cols="12" sm="6" md="4">
        <resume-card
          :check="user.id == permit"
          :user="user"
          title="Сильные стороны"
          :resume="user.resume[0].skills"
          main_icon="mdi-human-greeting"
          deleteUrl="/api/deleteSkill/"
          firstMainLine="type"
          :secondLineItems="['description']"
        >
          <resume-add-item
            :user="user"
            title="Добавить достижение"
            url="/api/skill"
            :form="skills"
            :returnDataEvent="skills.event"
            v-if="user.id == permit"
          />
        </resume-card>
      </v-col>
    </v-row>
  </div>
</template>

<script>
export default {
  props: ["user", "permit"],

  data() {
    return {
      localUser: this.user,
      showEdit: false,
      listenEventName: null,
      education: {
        colsPerRow: [4, 4, 4, 12, 12],
        event: 'educations',
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
            label: "Учебное заведение",
            type: "string",
            name: "name",
            rules: ["required"]
          },
          {
            label: "Специальность",
            type: "string",
            name: "specialty",
            rules: ["required"]
          }
        ]
      },
      job: {
        colsPerRow: [4, 4, 4, 12, 12],
        event: 'jobs',
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
            name: "positionLevel",
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
        event: 'families',
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
        event: 'languages',
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
            items: ["Начальный", "Средний", "Продвинутый", "Профессиональный"],
            rules: ["required"]
          }
        ]
      },
      achievment: {
        colsPerRow: [12],
        event: 'achievments',
        fields: [
          {
            label: "Тип",
            type: "string",
            name: "type",
            rules: ["required"]
          },
          {
            label: "Описание",
            type: "text",
            name: "description",
            rules: ["required"]
          }
        ]
      },
      skills: {
        colsPerRow: [12],
        event:'skills',
        fields: [
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

  methods: {
    datesPeriod(end, start) {
      let a = this.moment(end);
      let b = this.moment(start);
      return a.diff(b, "years");
    },
     defineEvent() {
      Event.listen("passDataEvent", data => {
        this.listenEventName = data;
      });
    },
    listenEvents(event) {
      Event.listen(event, data => {
        this.localUser.resume[0][event].push(data);
      });
    }
  },

  created() {
    this.defineEvent();
  },
  watch: {
    listenEventName(element){
      this.listenEvents(element);
    }
  }
};
</script>
