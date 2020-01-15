<template>
  <div>
    <profile-responsibility-show :division="localDivision" />
    <add-responsibilities :division="localDivision" v-if="user.position.name == 'Руководитель'" />
  </div>
</template>
<script>
export default {
  props: ["division", "user", "responsibilities"],
  data() {
    return {
      thisUserPosition: this.division.head.position.name,
      localDivision: this.division
    };
  }
};
</script>