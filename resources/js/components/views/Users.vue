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
      <v-col v-for="(user, i) in filteredUsers" :key="i" cols="12" sm="6" md="4" lg="3" xl="2">
        <user-card-vertical :user="user" />
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
export default {
  props: ["users"],
  data() {
    return {
      search: "",
      filteredUsers: this.users
    };
  },
  watch: {
    search(value) {
      this.filteredUsers = this.users.filter(user => {
        if (new RegExp(this.search, "gi").test(user.name)) return true;
        if (new RegExp(this.search, "gi").test(user.surname)) return true;
      });
    }
  }
};
</script>