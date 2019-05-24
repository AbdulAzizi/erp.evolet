<template>
  <v-card class="text-xs-center pa-3">
    <v-avatar size="140">
      <v-img :src="`../img/${employee.img}.jpg`"/>
    </v-avatar>
	
    <h5 class="subheading text-xs-center">{{employee.position.name}}</h5>
    <h5 class="subheading text-xs-center pa-3 ">{{`${employee.name} ${employee.surname}`}}</h5>

  </v-card>
</template>

<script>
export default {
  props: ["employee"],
};
</script>

