<template>
  <v-container>
    <profile-banner :user="user" />
    <v-row>
      <v-col cols="12" sm="6" md="4">
        <v-card class="mt-3 mb-3">
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
              <v-list-item-content>{{ moment(user.resume.birthday).format("L") }}</v-list-item-content>
            </v-list-item>
            <v-list-item>
              <v-list-item-icon>
                <v-icon>mdi-phone</v-icon>
              </v-list-item-icon>
              <v-list-item-content>{{user.resume.phone}}</v-list-item-content>
            </v-list-item>
            <v-list-item>
              <v-list-item-icon>
                <v-icon>mdi-human-male-female</v-icon>
              </v-list-item-icon>
              <v-list-item-content>{{user.resume.male_female}}</v-list-item-content>
            </v-list-item>
            <v-list-item>
              <v-list-item-icon>
                <v-icon>mdi-shield-half-full</v-icon>
              </v-list-item-icon>
              <v-list-item-content>{{user.resume.military_status}}</v-list-item-content>
            </v-list-item>
          </v-list>
        </v-card>
      </v-col>
      <v-col cols="12" sm="6" md="4">
        <v-card class="mt-3 mb-3">
          <v-toolbar dark flat dense color="primary">
            <v-toolbar-title>
              <h4>Образование</h4>
            </v-toolbar-title>
            <v-spacer></v-spacer>
            <add-education-dialog :user="user"></add-education-dialog>
          </v-toolbar>
          <v-list two-line>
            <template v-for="(degree, index) in localUser.resume.educations">
              <v-hover v-slot:default="{ hover }" :key="'hover-' + index">
                <v-list-item :key="'item-' + index">
                  <v-list-item-avatar>
                    <v-icon>mdi-school</v-icon>
                  </v-list-item-avatar>
                  <v-list-item-content>
                    <v-list-item-title>{{degree.name}}</v-list-item-title>
                    <v-list-item-subtitle>
                      {{degree.degree}} &#183; {{degree.specialty}} &#183;
                      {{moment(degree.start_at).format('MMMM YYYY')}} -
                      {{moment(degree.end_at).format('MMMM YYYY')}}
                    </v-list-item-subtitle>
                  </v-list-item-content>
                  <v-list-item-action v-show="hover">
                    <edit-education-dialog :user="degree"></edit-education-dialog>
                  </v-list-item-action>
                  <v-list-item-action v-if="hover">
                    <v-btn icon small class="grey lighten-3">
                      <v-icon small dark @click="deleteEducation(degree.id, index)">mdi-delete</v-icon>
                    </v-btn>
                  </v-list-item-action>
                </v-list-item>
              </v-hover>
              <v-divider
                :key="'divider-' + index"
                v-if="localUser.resume.educations.length > index + 1 "
              ></v-divider>
            </template>
          </v-list>
          <v-progress-linear
            :active="loading"
            :indeterminate="loading"
            absolute
            bottom
            color="deep-purple accent-4"
          ></v-progress-linear>
        </v-card>
      </v-col>
      <v-col cols="12" sm="6" md="4">
        <v-card class="mt-3 mb-3">
          <v-toolbar dark flat dense color="primary">
            <v-toolbar-title>
              <h4>Опыт работы</h4>
            </v-toolbar-title>
          </v-toolbar>
          <v-list two-line>
            <template v-for="(job, index) in user.resume.jobs">
              <v-list-item :key="'list-' + index">
                <v-list-item-avatar>
                  <v-icon>mdi-office-building</v-icon>
                </v-list-item-avatar>
                <v-list-item-content>
                  <v-list-item-title>{{job.company_name}} &#183; {{job.location}}</v-list-item-title>
                  <v-list-item-subtitle>{{job.position}} &#183; {{ datesPeriod(job.end_at, job.start_at) }} год назад</v-list-item-subtitle>
                </v-list-item-content>
              </v-list-item>
              <v-divider :key="'divider-' + index" v-if="index < user.resume.jobs.length - 1"></v-divider>
            </template>
          </v-list>
        </v-card>
      </v-col>
      <v-col cols="12" sm="6" md="4">
        <v-card class="mt-3 mb-3">
          <v-toolbar dark flat dense color="primary">
            <v-toolbar-title>
              <h4>Семейное положение</h4>
            </v-toolbar-title>
          </v-toolbar>
          <v-list two-line>
            <template v-for="(family, index) in user.resume.families">
              <v-list-item :key="'item-' + index">
                <v-list-item-avatar>
                  <v-icon>mdi-account-group</v-icon>
                </v-list-item-avatar>
                <v-list-item-content>
                  <v-list-item-title>{{family.relation}}</v-list-item-title>
                  <v-list-item-subtitle>{{family.name}} &#183; {{moment(family.birthday).format('L')}}</v-list-item-subtitle>
                </v-list-item-content>
              </v-list-item>
              <v-divider :key="'divider-' + index" v-if="index < user.resume.families.length - 1"></v-divider>
            </template>
          </v-list>
        </v-card>
      </v-col>
      <v-col cols="12" sm="6" md="4">
        <v-card class="mt-3 mb-3">
          <v-toolbar dark flat dense color="primary">
            <v-toolbar-title>
              <h4>Знание языков</h4>
            </v-toolbar-title>
          </v-toolbar>
          <v-list two-line>
            <template v-for="(language, index) in user.resume.languages">
              <v-list-item :key="'list-' + index">
                <v-list-item-avatar>
                  <v-icon>mdi-chat</v-icon>
                </v-list-item-avatar>
                <v-list-item-content>
                  <v-list-item-title>{{language.name}}</v-list-item-title>
                  <v-list-item-subtitle>{{language.level}}</v-list-item-subtitle>
                </v-list-item-content>
              </v-list-item>
              <v-divider :key="'divider-' + index" v-if="index < user.resume.languages.length - 1 "></v-divider>
            </template>
          </v-list>
        </v-card>
      </v-col>
      <v-col cols="12" sm="6" md="4">
        <v-card class="mt-3 mb-3">
          <v-toolbar dark flat dense color="primary">
            <v-toolbar-title>
              <h4>Достижения</h4>
            </v-toolbar-title>
          </v-toolbar>
          <v-list two-line>
            <template v-for="(achievment, index) in user.resume.achievments">
              <v-list-item :key="'item-' + index">
                <v-list-item-avatar>
                  <v-icon>mdi-certificate</v-icon>
                </v-list-item-avatar>
                <v-list-item-content>
                  <v-list-item-title>{{achievment.type}}</v-list-item-title>
                  <p class="grey--text caption">{{achievment.description}}</p>
                </v-list-item-content>
              </v-list-item>
              <v-divider
                :key="'divider-' + index"
                v-if="index < user.resume.achievments.length - 1"
              ></v-divider>
            </template>
          </v-list>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
export default {
  props: ["user"],

  data() {
    return {
      showed: false,
      showDialog: false,
      localUser: this.user,
      loading: false,
      showEdit: false
    };
  },

  methods: {
    datesPeriod(end, start) {
      let a = this.moment(end);
      let b = this.moment(start);
      return a.diff(b, "years");
    },

    deleteEducation(id, index) {
      this.loading = true;
      axios
        .delete(`/api/deleteEducation/${id}`, {})
        .then(res => {
          this.localUser.resume.educations.forEach((education, key) => {
            if (education.id === id)
              this.localUser.resume.educations.splice(key, 1);
          });
          this.loading = false;
        })
        .catch(err => console.log(err.message));
    }
  },
  created() {
    Event.listen("educationAdded", data => {
      this.localUser.resume.educations.push(data);
    });
    Event.listen("educationEdited", data => {
        this.localUser.resume.educations.push(data);
    });
  }
};
</script>
