<template>
    <v-row>
      <v-col
        v-for="(resume, index) in resumes"
        :key="index"
        cols="12" sm="6" md="4" lg="4" xl="2"
      >
       <resume-index-card :resume="resume" />
      </v-col>
    </v-row>
</template>

<script>
export default {
  props: ["resumes"],
};
</script>
