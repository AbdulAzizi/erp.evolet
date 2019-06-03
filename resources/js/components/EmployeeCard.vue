<template>
  <v-list three-line>
    <v-list-tile avatar>
      <v-list-tile-avatar>
        <img :src="`../img/${employee.user.img}.jpg`">
      </v-list-tile-avatar>

      <v-list-tile-content>
        <v-list-tile-title>{{`${employee.user.name} ${employee.user.surname}`}}</v-list-tile-title>
        <v-list-tile-sub-title>{{employee.position.name}} <br> {{employee.responsibility.name}}</v-list-tile-sub-title>
      </v-list-tile-content>
    </v-list-tile>
  </v-list>
</template>

<script>
export default {
  props: ["employee"]
};
</script>