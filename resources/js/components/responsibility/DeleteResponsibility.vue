<template>
  <div>
    <v-card>
      <v-card-title>Действительно хотите удалить должностную объязанность?</v-card-title>
      <v-card-actions>
        <v-spacer />
        <v-btn text color="primary" @click="cancel()">нет</v-btn>
        <v-btn text color="red lighten-2" @click="deleteResponsibility()">да</v-btn>
      </v-card-actions>
    </v-card>
  </div>
</template>

<script>
export default {
  data() {
    return {
      responsibilityId: null
    };
  },
  created() {
    Event.listen("deleteResponsibility", responsibilityId => this.responsibilityId = responsibilityId);
  },
  methods: {
    deleteResponsibility() {
      axios.delete(`/api/delete/responsibility/${this.responsibilityId}`)
           .then(res => Event.fire("responsibilityDeleted", this.responsibilityId))
           .catch(err => err.messages);
    },
    cancel(){
        Event.fire("dontDeleteResponsibility");
    }
  }
};
</script>