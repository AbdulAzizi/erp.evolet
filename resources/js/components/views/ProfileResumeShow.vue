<template>
  <div>
    <profile-banner :user="user" />
    <resume-create :user="user" v-if="!user.resume[0]" :permit="permit"></resume-create>
    <v-row v-if="user.resume[0]">
      <v-col cols="12">
        <v-card outlined>
          <v-toolbar dark flat dense color="primary">
            <v-toolbar-title>
              <h4>Основное</h4>
            </v-toolbar-title>
            <v-spacer />
            <v-btn icon v-if="user.id == permit" @click="edit = true">
              <v-icon>mdi-pencil-circle</v-icon>
            </v-btn>
          </v-toolbar>
          <v-list dense>
            <v-form ref="editForm">
              <v-list-item v-if="!edit">
                <v-list-item-icon>
                  <v-icon>mdi-calendar-range</v-icon>
                </v-list-item-icon>
                <v-list-item-content>{{ moment(birthday).format("L") }}</v-list-item-content>
              </v-list-item>
              <v-list-item v-if="edit">
                <v-list-item-content>
                  <v-dialog
                    ref="dialog"
                    v-model="modal"
                    :return-value.sync="birthday"
                    persistent
                    width="290px"
                  >
                    <template v-slot:activator="{ on }">
                      <v-text-field
                        v-model="birthday"
                        label="День рождение"
                        outlined
                        v-on="on"
                        dense
                        :rules="required"
                        hide-details
                      ></v-text-field>
                    </template>
                    <v-date-picker v-model="birthday" scrollable>
                      <v-spacer></v-spacer>
                      <v-btn text color="primary" @click="modal = false">Отмена</v-btn>
                      <v-btn text color="primary" @click="$refs.dialog.save(birthday)">Сохранить</v-btn>
                    </v-date-picker>
                  </v-dialog>
                </v-list-item-content>
              </v-list-item>
              <v-list-item v-if="!edit">
                <v-list-item-icon>
                  <v-icon>mdi-phone</v-icon>
                </v-list-item-icon>
                <v-list-item-content>{{phone}}</v-list-item-content>
              </v-list-item>
              <v-list-item v-show="edit">
                <v-list-item-content>
                  <v-text-field
                  v-model="phone"
                  v-mask="'###-##-##-##'"
                  label="Номер телефона"
                  outlined
                  dense
                  :rules="required"
                  hide-details>
                  </v-text-field>
                </v-list-item-content>
              </v-list-item>
              <v-list-item v-if="!edit && phone_2">
                <v-list-item-icon>
                  <v-icon>mdi-phone-plus</v-icon>
                </v-list-item-icon>
                <v-list-item-content>{{phone_2}}</v-list-item-content>
              </v-list-item>
              <v-list-item v-show="edit">
                <v-list-item-content>
                  <v-text-field
                  v-model="phone_2"
                  v-mask="'###-##-##-##'"
                  label="Доп. номер телефона"
                  outlined
                  dense
                  hide-details>
                  </v-text-field>
                </v-list-item-content>
              </v-list-item>
              <v-list-item v-if="!edit">
                <v-list-item-icon>
                  <v-icon>mdi-email</v-icon>
                </v-list-item-icon>
                <v-list-item-content>{{email}}</v-list-item-content>
              </v-list-item>
              <v-list-item v-if="edit">
                <v-list-item-content>
                  <v-text-field
                  v-model="email"
                  label="Эл. почта"
                  outlined
                  dense
                  :rules="required"
                  hide-details
                  >
                  </v-text-field>
                </v-list-item-content>
              </v-list-item>
               <v-list-item v-if="!edit && address">
                <v-list-item-icon>
                  <v-icon>mdi-map-marker</v-icon>
                </v-list-item-icon>
                <v-list-item-content>{{address}}</v-list-item-content>
              </v-list-item>
              <v-list-item v-if="edit">
                <v-list-item-content>
                  <v-text-field
                  v-model="address"
                  label="Домашний адрес"
                  outlined
                  dense
                  hide-details
                  >
                  </v-text-field>
                </v-list-item-content>
              </v-list-item>
              <v-list-item v-if="!edit && citizenship">
                <v-list-item-icon>
                  <v-icon>mdi-passport</v-icon>
                </v-list-item-icon>
                <v-list-item-content>
                  <div>
                      <span v-for="(citizen, index) in citizenship" :key="index">{{citizen}}<span v-if="index !== citizenship.length - 1">, </span></span>
                  </div>
                </v-list-item-content>
              </v-list-item>
              <v-list-item v-if="edit">
                <v-list-item-content>
                  <v-select
                  v-model="citizenship"
                  label="Гражданство"
                  outlined
                  dense
                  hide-details
                  multiple
                  :items="countries"
                  >
                  </v-select>
                </v-list-item-content>
              </v-list-item>
              <v-list-item v-if="!edit">
                <v-list-item-icon>
                  <v-icon>mdi-human-male-female</v-icon>
                </v-list-item-icon>
                <v-list-item-content>{{gender}}</v-list-item-content>
              </v-list-item>
              <v-list-item v-if="edit">
                <v-list-item-content>
                  <v-select
                  v-model="gender"
                  label="Выберите пол"
                  :items="['Мужской', 'Женский']"
                  outlined
                  dense
                  :rules="required"
                  hide-details>
                  </v-select>
                </v-list-item-content>
              </v-list-item>
            </v-form>
          </v-list>
          <v-card-actions v-if="edit">
            <v-spacer />
            <v-btn text color="primary" @click="cancelEdit()">
              Отмена
            </v-btn>
            <v-btn color="primary" @click="editMainInfo()">
              Изменить
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-col>
      <v-col cols="12">
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
      <v-col cols="12">
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
      <v-col cols="12">
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
      <v-col cols="12">
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
      <v-col cols="12">
        <resume-card
          :check="user.id == permit"
          :user="user"
          title="Достижения"
          :resume="user.resume[0].achievments"
          main_icon="mdi-certificate"
          deleteUrl="/api/deleteAchievment/"
          firstMainLine="type"
          firstSecondaryLine="date"
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

      <v-col cols="12">
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
            title="Добавить сильные стороны"
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
import { VueMaskDirective } from 'v-mask';
Vue.directive('mask', VueMaskDirective);
export default {
  props: ["user", "permit"],

  data() {
    return {
      edit: false,
      modal: false,
      required: [v => !!v || "Форма должна быть заполнена"],
      localUser: this.user,
      birthday: this.user.resume[0].birthday,
      phone: this.user.resume[0].phone,
      phone_2: this.user.resume[0].phone_2,
      email: this.user.resume[0].email,
      gender: this.user.resume[0].male_female,
      address: this.user.resume[0].address,
      citizenship: JSON.parse(this.user.resume[0].citizenship),
      showEdit: false,
      listenEventName: null,
      education: {
        colsPerRow: [4, 4, 4, 12, 12],
        event: "educations",
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
        event: "jobs",
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
        event: "families",
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
        event: "languages",
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
        event: "achievments",
        fields: [
          {
            label: "Тип",
            type: "string",
            name: "type",
            rules: ["required"]
          },
          {
            label: "Дата получения награды",
            type: "date",
            name: "date"
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
        event: "skills",
        fields: [
          {
            label: "Описание",
            type: "text",
            name: "description",
            rules: ["required"]
          }
        ]
      },
      countries: [
        'Таджикистан',
        'Узбекистан',
        'Россия',
        'Индия',
        'США'
      ]
    };
  },

  methods: {
    datesPeriod(end, start) {
      let a = this.moment(end);
      let b = this.moment(start);
      return a.diff(b, "years");
    },
    cancelEdit(){
      this.edit = false;
      this.birthday = this.user.resume[0].birthday;
      this.phone = this.user.resume[0].phone;
      this.email = this.user.resume[0].email;
      this.gender = this.user.resume[0].male_female;
    },
    editMainInfo(){
      const formValid = this.$refs.editForm.validate();
      if(formValid){
        axios.post(this.appPath(`api/resume/${this.user.resume[0].id}/edit`), {
          phone: this.phone.split('-').join(),
          phone_2: this.phone_2.split('-').join(),
          email: this.email,
          gender: this.gender,
          birthday: this.birthday,
          address: this.address,
          citizenship: JSON.stringify(this.citizenship)
        }).then(res => {
          this.edit = false;
          this.birthday = res.data.birthday;
          this.phone = res.data.phone.split(',').join('-');
          this.phone_2 = res.data.phone_2.split(',').join('-');
          this.email = res.data.email;
          this.gender = res.data.male_female;
          this.address = res.data.address;
          this.citizenship = JSON.parse(res.data.citizenship);
        }).catch(err => console.error(err));
      }
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
    listenEventName(element) {
      this.listenEvents(element);
    }
  }
};
</script>
