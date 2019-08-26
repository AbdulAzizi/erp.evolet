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
          <h4>Добавить образование</h4>
        </v-toolbar-title>
      </v-toolbar>
      <v-card-text>
        <v-form action="/api/education" method="POST" @submit.prevent="onSubmit" ref="educationForm">
          <v-row>
            <input type="hidden" name="_token" :value="csrf_token" />

            <v-col cols="4">
              <form-field
                :field="{
                    label: 'Степень',
                    type: 'select',
                    name: 'degree',
                    items: ['Среднее', 'Бакалавр',  'Специалитет', 'Магистр'],
                    rules: ['required']
                }"
                v-model="degree"
              ></form-field>
            </v-col>
            <v-col cols="4">
              <form-field
                :field="{
                    label: 'Дата начало',
                    type: 'date',
                    name: 'start_at',
                    rules: ['required']
                }"
                v-model="start_at"
              ></form-field>
            </v-col>
            <v-col cols="4">
              <form-field
                :field="{
                    label: 'Дата окончания',
                    type: 'date',
                    name: 'end_at',
                    rules: ['required']
                }"
                v-model="end_at"
              ></form-field>
            </v-col>
            <v-col cols="12">
              <form-field
                :field="{
                    label: 'Название',
                    type: 'string',
                    name: 'name',
                    rules: ['required']
                }"
                v-model="name"
              ></form-field>
            </v-col>
            <v-col cols="12">
              <form-field
                :field="{
                    label: 'Название',
                    type: 'string',
                    name: 'specialty',
                    rules: ['required']
                }"
                v-model="specialty"
              ></form-field>
            </v-col>
            <v-btn color="red lighten-2" type="submit" :loading="loading" :disabled="loading">Add</v-btn>
          </v-row>
        </v-form>
      </v-card-text>
    </v-card>
  </v-dialog>
</template>

<script>
export default {
  props: ["user"],

  data() {
    return {
      dialog: false,
      degree: null,
      name: null,
      start_at: null,
      end_at: null,
      specialty: null,
      resume_id: this.user.resume.id,
      csrf_token: window.Laravel.csrf_token,
      loading: false
    };
  },
  methods: {
    onSubmit() {
      let educationForm = this.$refs.educationForm;
      if (educationForm.validate()) {
        this.loading = true;
        axios
          .post("/api/education", {
            degree: this.degree,
            resume_id: this.resume_id,
            start_at: this.start_at,
            end_at: this.end_at,
            specialty: this.specialty,
            name: this.name
          })
          .then(res => {
            this.dialog = false;
            Event.fire("educationAdded", res.data);
            this.loading = false;
            educationForm.reset();
          })
          .catch(err => console.log(err.message));
      }
    }
  }
};
</script>
