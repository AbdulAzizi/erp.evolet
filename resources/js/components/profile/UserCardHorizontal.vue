<template>
  <v-card active-class="true" height="100%">
<v-container class="py-0">
      <v-row>
        <v-col cols="3" class="pa-0">
          <v-img :src="photo(user.img)"
          height="100%"
          width="6rem"></v-img>
        </v-col>
        <v-col cols="9">
          <h4 class="primary--text pb-1">{{ user.name }} {{ user.surname }}</h4>
          <span style="font-size:12px">{{ user.position_level.name }}</span> <br>
          <span style="font-size:12px">{{user.division.name}}</span> <br>
          <span style="font-size: 12px;" class="grey--text" v-for="(position, key) in user.positions" :key="'position-'+key">
          {{position.name}}
          <span v-if="key != user.positions.length-1"> &#183;  </span>
          </span>
        </v-col>
      </v-row>
</v-container>
  </v-card>
</template>


<script>
export default {
  props: ["user"],
  data() {
    return {};
  },
  created(){
    console.log(this.user)
  }
};
</script>
