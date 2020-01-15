<template>
  <div>
    <profile-banner :user="user" />
    <responsibilities :user="user" />
  </div>
</template>

<script>
export default {
  props: ["user", "responsibilities"],
  data() {
    return {
      thisUserPosition: this.user.position.name
    };
  }
};
</script>