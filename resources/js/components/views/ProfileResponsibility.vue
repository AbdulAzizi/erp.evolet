<template>
  <div>
    <profile-banner :user="user" />
    <profile-responsibility-show :user="user" />
    <add-job-description :responsibilities="responsibilities" />
  </div>
</template>

<script>
export default {
  props: ["user", "responsibilities"],
  data() {
    return {};
  }
};
</script>