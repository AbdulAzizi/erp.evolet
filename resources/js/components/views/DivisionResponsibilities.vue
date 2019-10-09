<template>
  <v-flex>
    <profile-responsibility-show :user="division" />
    <add-responsibilities :division="division" v-if="user.position.name == 'Руководитель'" />
  </v-flex>
</template>
<script>
export default {
  props: ["division", "user", "responsibilities"],
  data() {
    return {
      thisUserPosition: this.division.head.position.name
    };
  }
};
</script>