<template>
  <v-dialog v-model="dialog" width="800">
    <template v-slot:activator="{ on }">
      <v-btn icon v-on="on">
        <v-icon>mdi-plus-circle</v-icon>
      </v-btn>
    </template>

    <v-card>
      <v-toolbar color="primary" dark flat dense>
        <v-toolbar-title>
          <h4>{{title}}1</h4>
        </v-toolbar-title>
      </v-toolbar>
      <v-card-text>
        <v-form :action="url" method="POST" @submit.prevent="onSubmit" ref="form">
          <v-row>
            <input type="hidden" name="_token" :value="csrf_token" />

            <v-col
              :cols=" form.colsPerRow.length <= index ? 12 : form.colsPerRow[index]"
              v-for="(field, index) in form.fields"
              :key="index"
            >
              <form-field :field="field"></form-field>
            </v-col>
          </v-row>
            <v-btn  color="primary" type="submit" :loading="loading" :disabled="loading">Добавить</v-btn>
        </v-form>
      </v-card-text>
    </v-card>
  </v-dialog>
</template>

<script>
export default {
  props: ["user", "title", "url", "form", "returnDataEvent", "resume"],

  data() {
    return {
      dialog: false,
      resume_id: this.user ? this.user.resume.id : this.resume.id,
      csrf_token: window.Laravel.csrf_token,
      loading: false
    };
  },
  methods: {
    onSubmit() {
      let form = this.$refs.form;

      if (form.validate()) {
        this.loading = true;

        let values = {
          resume_id: this.resume_id
        };

        this.$refs.form.inputs.map(input => {
          values[input.$attrs.name] = input.value;
        });

        axios
          .post(this.url, values)
          .then(res => {
            this.dialog = false;
            Event.fire(this.returnDataEvent, res.data);
            console.log("Event Fired");

            this.loading = false;
            form.reset();
          })
          .catch(err => console.log(err.message));
      }
    }
  }
};
</script>
