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
        <v-switch color="primary" v-model="withoutOwner" label="Кандидаты"/>
      </v-col>
    </v-row>
    <v-row justify="center">
      <v-col
        v-for="(resume, index) in filteredResumes"
        :key="index"
        cols="12" sm="6" md="4" lg="4" xl="2"
      >
       <resume-index-card :resume="resume" />
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
