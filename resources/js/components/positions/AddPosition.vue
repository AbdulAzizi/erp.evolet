<template>
  <div>
    <v-card>
      <v-toolbar dark flat color="primary">
        <v-toolbar-title>Добавить должность</v-toolbar-title>
      </v-toolbar>
      <v-card-text>
        <v-form ref="form">
          <form-field :field="{
            type: 'string',
            label: 'Должность',
            name: 'position',
            requried: ['required']
        }" v-model="position" />
        </v-form>
      </v-card-text>
      <v-card-actions>
        <v-spacer />
        <v-btn text color="primary">отмена</v-btn>
        <v-btn dark color="primary" @click="submitForm()">создать</v-btn>
      </v-card-actions>
    </v-card>
  </div>
</template>
<script>
export default {
  props: ['divisionId'],
  data() {
    return {
      position: null,
    };
  },
  methods: {
    submitForm(){
      const form = this.$refs.form;
      if(form.validate()){
        axios.post(`/api/create/position/${this.divisionId}`, {
          position: this.position
        }).then(res => {
          Event.fire('newPosition', res.data);
          form.reset();
        }).catch(err => err.messages);
      }
    }
  }
};
</script>