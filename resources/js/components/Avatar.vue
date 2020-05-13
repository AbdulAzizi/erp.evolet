<template>
<div>
  <a :href="appPath(user.surname ? 'users/'+user.id : '#' )" v-if="link">
    <v-avatar :size="size">
      <v-tooltip bottom>
        <template v-slot:activator="{ on:tooltip }">
          <img v-on="{ ...tooltip }" :src="thumb(user.img) " alt="avatar" />
        </template>
        <span v-if="user.surname">{{ user.name }} {{ user.surname }}</span>
        <span v-else>Процесс: {{ user.name }}</span>
      </v-tooltip>
    </v-avatar>
  </a>
  <v-avatar :size="size" v-else>
      <v-tooltip bottom>
        <template v-slot:activator="{ on:tooltip }">
          <img v-on="{ ...tooltip }" :src="thumb(user.img) " alt="avatar" />
          
        </template>
        <span v-if="user.surname">{{ user.name }} {{ user.surname }}</span>
        <span v-else>Процесс: {{ user.name }}</span>
      </v-tooltip>
    </v-avatar>
      <span v-if="fullname">
        {{user.fullname}}
      </span>
</div>

</template>

<script>
export default {
  props: {
    user: {
      required: true,
      type: Object
    },
    size:{
      required:false,
      default:40
    },
    link: {
      required: false
    },
    fullname: {
      required: false
    }
  }
};
</script>

<style>
</style>
