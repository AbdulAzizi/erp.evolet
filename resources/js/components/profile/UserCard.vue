<template>
  <v-card
    class="pa-3 text-center"
    active-class="true"
    height="100%"
    hover
    :href="appPath('users/' + user.id)"
  >
    <v-img
      :src="photo(user.img)"
      style="border-radius: 100%; border: solid 6px #b8cf41;"
      class="elevation-14 mx-auto"
      width="12rem"
      height="12rem"
    ></v-img>

    <v-card-text>
      <v-card-title class="justify-center pa-0">
        <div class="subtitle-1 black--text">
          <span class="font-weight-regular text-sm-left">{{ user.position.name }}</span>
        </div>
      </v-card-title>
      <h3
        class="display-1 font-weight-regular primary--text my-2"
      >{{ user.name }} {{ user.surname }}</h3>

      <div class="subtitle-1 black--text">
        <span v-for="(responsibility, key) in user.responsibilities" :key="'responsibility-'+key">
          {{responsibility.name}}
          <span v-if="key != user.responsibilities.length-1">|</span>
        </span>
      </div>
    </v-card-text>
  </v-card>
</template>


<script>
export default {
  props: ["user"],
  data() {
    return {};
  }
};
</script>