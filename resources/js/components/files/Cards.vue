<template>
  <v-container fluid>
    <v-row>
      <v-col md="3" v-for="(file,index) in localItems" :key="'file-'+index">
        <file-card :item="file" />
      </v-col>
    </v-row>
    <file-add />
  </v-container>
</template>
<script>
export default {
  props: ["items"],
  data() {
    return {
      localItems: this.items
    };
  },
  created() {
    this.synch();
  },
  methods: {
    synch() {
      Event.listen("fileDeleted", data => {
        this.localItems = data;
      });
      Event.listen("fileCreated", data => {
        this.localItems = data;
      });
    }
  }
};
</script>