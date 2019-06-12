<template>
  <v-card hover :href="appPath + 'employees/' + employee.id">
  <v-list three-line>
    <v-list-tile avatar>
      <v-list-tile-avatar>
        <img :src="photo(employee.user.img)">
      </v-list-tile-avatar>

      <v-list-tile-content>
        <v-list-tile-title>{{`${employee.user.name} ${employee.user.surname}`}}</v-list-tile-title>
        <v-list-tile-sub-title>{{employee.position.name}} <br> {{employee.responsibility.name}}</v-list-tile-sub-title>
      </v-list-tile-content>
    </v-list-tile>
  </v-list>
  </v-card>
</template>

<script>
export default {
  props: ["employee"],
};
</script>