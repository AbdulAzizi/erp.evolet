<template>
  <div>
    <profile-banner :user="user" />
    <profile-responsibility-show :user="user" />
    <add-job-description :user="user" />
  </div>
</template>

<script>
export default {
  props: ["user"]
};
</script>