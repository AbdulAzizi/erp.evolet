<template>
  <v-container fluid>
    <v-row justify="center">
      <v-col md="5">
        <v-text-field
          hide-details
          label="Search"
          prepend-inner-icon="mdi-magnify"
          solo
          v-model="search"
        ></v-text-field>
      </v-col>
    </v-row>
    <v-row justify="center">
      <v-col md="2">
        <v-switch color="primary" v-model="withOwner" label="Cотрудники" />
      </v-col>
      <v-col md="2">
        <v-switch color="primary" v-model="withoutOwner" label="В приоритете"/>
      </v-col>
    </v-row>
    <v-row justify="center">
      <v-col
        v-for="(resume, index) in filteredResumes"
        :key="index"
        cols="12" sm="6" md="4" lg="4" xl="2"
      >
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
                <span
                  class="grey--text text--darken-2"
                >{{resume.educations[0] ? resume.educations[0].name + ' &#183; ' + resume.educations[0].specialty : 'Нет данных'}}</span>
              </v-list-item-content>
            </v-list-item>
            <v-list-item>
              <v-list-item-icon>
                <v-icon color="primary">mdi-chat</v-icon>
              </v-list-item-icon>
              <v-list-item-content>
                <span
                  class="grey--text text--darken-2"
                >{{resume.languages[0] ? resume.languages[0].name + ' &#183; ' + resume.languages[0].level : 'Нет данных'}}</span>
              </v-list-item-content>
            </v-list-item>
          </v-list>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
export default {
  props: ["resumes"],
  data() {
    return {
      search: "",
      withOwner: false,
      withoutOwner: false,
      filteredResumes: this.resumes
    };
  },
  watch: {
    search() {
      this.filteredResumes = this.resumes.filter(resume => {
        if (new RegExp(this.search, "gi").test(resume.name)) return true;
        if (new RegExp(this.search, "gi").test(resume.surname)) return true;
      });
    },
    withOwner() {
      if (this.withOwner) {
        this.filteredResumes = this.resumes.filter(
          resume => resume.owner.length > 0
        );
      } else {
        this.filteredResumes = this.resumes;
      }
    },
    withoutOwner() {
      if (this.withoutOwner) {
        this.filteredResumes = this.resumes.filter(
          resume => resume.owner.length == 0
        );
      } else {
        this.filteredResumes = this.resumes;
      }
    }
  }
};
</script>
