<template>
  <div>
    <profile-banner :user="user" />
    <profile-responsibility-show :user="user" />
    <add-job-description :user="user" :responsibilities="responsibilities" v-if="responsibilities" />
  </div>
</template>

<script>
export default {
  props: ["user","responsibilities"],
  data() {
    return {};
  }
};
</script>