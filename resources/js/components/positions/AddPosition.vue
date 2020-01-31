<template>
  <div>
    <v-card>
      <v-toolbar dark flat color="primary">
        <v-toolbar-title>Добавить должность</v-toolbar-title>
      </v-toolbar>
      <v-card-text>
        <v-form ref="form">
          <form-field
            :field="{
            type: 'string',
            label: 'Должность',
            name: 'position',
            rules: ['required']
        }"
            v-model="position"
          />
        </v-form>
      </v-card-text>
      <v-card-actions>
        <v-spacer />
        <v-btn text color="primary" @click="cancel()">отмена</v-btn>
        <v-btn dark color="primary" @click="submitForm()">создать</v-btn>
      </v-card-actions>
    </v-card>
  </div>
</template>
<script>
export default {
  props: ["divisionId"],
  data() {
    return {
      position: null,
      singleDivisionId: null
    };
  },
  created() {
    Event.listen("addPositionToDivision", divisionId => {
      this.singleDivisionId = divisionId;
    });
  },
  methods: {
    submitForm() {
      const form = this.$refs.form;
      if (form.validate()) {
        axios
          .post("/api/create/position", {
            divisionId: this.divisionId
              ? this.divisionId
              : this.singleDivisionId,
            position: this.position
          })
          .then(res => {
            if (this.singleDivisionId) {
              Event.fire("newPositionToDivision", {
                divisionId: this.singleDivisionId,
                position: res.data
              });
            } else {
              Event.fire("newPosition", res.data);
            }
            form.reset();
          })
          .catch(err => err.messages);
      }
    },
    cancel() {
      const form = this.$refs.form;
      form.reset();
      Event.fire("cancelPositionAdding");
    }
  }
};
</script>