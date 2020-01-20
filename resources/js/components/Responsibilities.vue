<template>
  <div>
    <v-row v-if="responsibilities.length > 0">
      <v-col cols="6" v-for="(responsibility, index ) in responsibilities" :key="index">
        <responsibility-card :responsibility="responsibility" />
      </v-col>
    </v-row>
    <div class="d-flex justify-center" v-else>
      <p class="grey--text">Объязанностей пока нет, но вы можете добавить их прямо сейчас</p>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    responsibilities: {}
  },
  data() {
    return {
      localResponsibilities: this.responsibilities
    };
  },
  created() {
    Event.listen("deleteResponsibility", responsibilityId => {
      this.localResponsibilities.forEach((responsibility, index) => {
        if (responsibility.id == responsibilityId) {
          this.localResponsibilities.splice(index, 1);
        }
      });
    });
  }
};
</script>