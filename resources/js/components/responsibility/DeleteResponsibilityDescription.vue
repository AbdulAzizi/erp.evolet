<template>
  <v-card>
    <v-card-title>Действительно хотите удалить?</v-card-title>
    <v-card-actions>
      <v-spacer />
      <v-btn text color="primary" @click="cancel()">нет</v-btn>
      <v-btn text color="red lighten-2" @click="deleteResponsibilityDescription()">да</v-btn>
    </v-card-actions>
  </v-card>
</template>

<script>
export default {
  data() {
    return {
      responsibilityDescriptionId: null
    };
  },
  created() {
    Event.listen(
      "deleteResponsibilityDescription",
      responsibilityDescriptionId =>
        (this.responsibilityDescriptionId = responsibilityDescriptionId)
    );
  },
  methods: {
    deleteResponsibilityDescription() {
      axios
        .delete(
          `/api/delete/responsibility/description/${this.responsibilityDescriptionId}`
        )
        .then(res => {
          Event.fire(
            "responsibilityDescriptionDeleted",
            this.responsibilityDescriptionId
          );
        })
        .catch(err => err.messages);
    },
    cancel(){
        Event.fire("cancelResponsibilityDescriptionDeleting");
    }
  }
};
</script>