<template>

  <v-card class="text-xs-center pa-3">
    <v-avatar size="140">
      <v-img :src="`../img/${employee.user.img}.jpg`"  />
    </v-avatar>
	
    <h6 class="subheading mt-2 grey--text">{{employee.position.name}}</h6>
    <h6 class="title py-1 primary--text" >{{`${employee.user.name} ${employee.user.surname}`}}</h6>
    <h6 class="body-2 grey--text ">{{employee.responsibility.name}}</h6>

  </v-card>
</template>

<script>
export default {
  props: ["employee"],
};
</script>

