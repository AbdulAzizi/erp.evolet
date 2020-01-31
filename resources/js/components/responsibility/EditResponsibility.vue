<template>
  <v-card>
    <v-toolbar flat dense dark color="primary">
      <v-toolbar-title>Изменить должностную объязанность</v-toolbar-title>
    </v-toolbar>
    <v-card-text>
      <v-form ref="form" class="mt-5">
        <form-field
          :field="{
                    label: 'Должностная объязанность',
                    name: 'responsibility',
                    type: 'text',
                    rules: ['required'],
                    value: responsibilityText
                }"
          v-model="responsibilityText"
        />
      </v-form>
    </v-card-text>
    <v-card-actions>
      <v-spacer />
      <v-btn text color="primary" @click="cancel()">отмена</v-btn>
      <v-btn color="primary" @click="submitForm()">изменить</v-btn>
    </v-card-actions>
  </v-card>
</template>
<script>
export default {
  props: ["responsibilities"],
  data() {
    return {
      responsibilityName: null,
      responsibilityText: null,
      responsibilityId: null
    };
  },
  methods: {
    submitForm() {
      const form = this.$refs.form;
      if (form.validate()) {
        axios
          .post(`/api/edit/responsibility/${this.responsibilityId}`, {
            text: this.responsibilityText
          })
          .then(res => {
            Event.fire("editResponsibility", res.data);
          })
          .catch(err => err.messages);
      }
    },
    cancel() {
      Event.fire("cancelResponsibilityEdit");
    }
  },
  created() {
    Event.listen("responsibility", responsibility => {
      this.responsibilityText = responsibility.text;
      this.responsibilityId = responsibility.id;
    });
  }
};
</script>