<template>
  <v-card>
    <v-toolbar dense dark flat color="primary">
      <v-toolbar-title>Добавить должностную задачу</v-toolbar-title>
    </v-toolbar>
    <v-card-text>
      <v-form ref="form" class="mt-5">
        <form-field
          :field="{
                label: 'Должностная задача',
                name: 'title',
                type: 'string',
                rules: ['required'],
            }"
          v-model="title"
        />
        <form-field
          :field="{
                label: 'Инструкция',
                name: 'description',
                type: 'text'
            }"
          v-model="description"
        />
      </v-form>
    </v-card-text>
    <v-card-actions>
      <v-spacer />
      <v-btn text color="primary" @click="cancel()">отмена</v-btn>
      <v-btn color="primary" @click="submitForm()">создать</v-btn>
    </v-card-actions>
  </v-card>
</template>

<script>
export default {
  data() {
    return {
      title: null,
      description: null,
      responsibilityId: null
    };
  },
  created() {
    Event.listen(
      "addResponsibilityDescription",
      responsibilityId => (this.responsibilityId = responsibilityId)
    );
  },
  methods: {
    submitForm() {
      const form = this.$refs.form;
      if (form.validate()) {
        axios
          .post("/api/add/responsibility/description", {
            description: this.description,
            title: this.title,
            responsibility_id: this.responsibilityId
          })
          .then(res => {
            Event.fire("responsibilityDescriptionAdded", {
              responsibilityId: this.responsibilityId,
              description: res.data
            });
            form.reset();
          })
          .catch(err => err.messages);
      }
    },
    cancel() {
      const form = this.$refs.form;
      form.reset();
      Event.fire("cancelDescriptionAdding");
    }
  }
};
</script>