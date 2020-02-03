<template>
  <v-container fluid>
    <v-row>
      <v-col :md="addUser ? 9 : 12" sm="12">
        <v-text-field
          hide-details
          label="Search"
          prepend-inner-icon="mdi-magnify"
          solo
          v-model="search"
          dense
        ></v-text-field>
      </v-col>
      <v-col md="3" sm="12" v-if="addUser">
        <v-btn height="38" outlined color="primary" block>Добавить сотрудника</v-btn>
      </v-col>
    </v-row>
    <v-row justify="center">
      <v-col v-for="(user, i) in filteredUsers" :key="i" cols="12" sm="6" md="4" lg="4" xl="2">
        <user-card-horizontal :user="user" />
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
export default {
  props: ["users", "addUser"],
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
