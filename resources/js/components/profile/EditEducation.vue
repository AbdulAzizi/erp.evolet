<template>
  <div>
    <v-dialog v-model="show" width="800">
      <v-card>
        <v-toolbar dense dark flat color="primary">
          <v-toolbar-title>
            <h4>Изменить</h4>
          </v-toolbar-title>
        </v-toolbar>
        <v-card-text>
          <v-form
            :action="`/api/edit/education/${user.id}`"
            method="post"
            @submit.prevent="onSubmit"
            ref="editForm"
          >
            <input type="hidden" name="_token" :value="csrf_token" />
            <v-row>
              <v-col cols="4">
                <form-field
                  :field="{
                        label: 'Степень',
                        type: 'select',
                        name: 'degree',
                        value: degree,
                        items: ['Среднее', 'Бакалавр',  'Специалитет', 'Магистр'],
                        rules: ['required']
                    }"
                  v-model="degree"
                ></form-field>
              </v-col>
              <v-col cols="4">
                <form-field
                  :field="{
                        label: 'Дата начала',
                        type: 'date',
                        name: 'start_at',
                        value: start_at,
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
                        value: end_at,
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
                        name: 'institute',
                        value: institute,
                        rules: ['required']
                    }"
                  v-model="institute"
                ></form-field>
              </v-col>
              <v-col cols="12">
                <form-field
                  :field="{
                        label: 'Специальность',
                        type: 'string',
                        name: 'specialty',
                        value: specialty
                    }"
                  v-model="specialty"
                ></form-field>
                <v-btn type="submit" color="primary" :loading="loading" :disabled="loading">Изменить</v-btn>
              </v-col>
            </v-row>
          </v-form>
        </v-card-text>
      </v-card>
    </v-dialog>
    <v-btn icon small class="grey lighten-3" @click="show = true">
      <v-icon small dark>mdi-pencil</v-icon>
    </v-btn>
  </div>
</template>

<script>
export default {
  props: ["user"],

  data() {
    return {
      show: false,
      degree: this.user.degree,
      start_at: this.user.start_at,
      end_at: this.user.end_at,
      institute: this.user.name,
      specialty: this.user.specialty,
      csrf_token: window.Laravel.csrf_token,
      loading: false
    };
  },

  methods: {
    onSubmit() {

      let valid = this.$refs.editForm;

      if(valid.validate()){

          this.loading = true;
          axios
            .put(`/api/edit/education/${this.user.id}`, {
                institute: this.institute,
                degree: this.degree,
                specialty: this.specialty,
                start_at: this.start_at,
                end_at: this.end_at
            })
            .then(res => {
                Event.fire('educationEdited', res.data);
                this.loading = false;
                this.show = false;
            })
            .catch(err => console.log(err.message));

      }
    }
  }
};
</script>
