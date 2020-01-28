<template>
  <div>
    <v-card>
      <v-toolbar flat dense dark color="primary">
        <v-toolbar-title>Добавить объязанность</v-toolbar-title>
      </v-toolbar>
      <v-card-text>
        <v-form ref="form">
          <form-field
            :field="{
          label: 'Должностная объязанность',
          name: 'responsibility',
          type: 'string',
          rules:['required']
        }"
        v-model="text" />
        </v-form>
      </v-card-text>
      <v-card-actions>
        <v-spacer />
        <v-btn text color="primary" @click="cancel()">отмена</v-btn>
        <v-btn color="primary" @click="submitForm()">создать</v-btn>
      </v-card-actions>
    </v-card>
  </div>
</template>


<script>
export default {
  props: ["position"],
  data() {
    return {
      text: null
    };
  },
  methods: {
    submitForm(){
      const form = this.$refs.form;
      if(form.validate()){
        axios.post('/api/add/responsibility', {
          positionId: this.position.id,
          text: this.text
        }).then(res => {
          Event.fire('responsibilityAdded', res.data);
          form.reset();
        })
      }
    },
    cancel() {
      const form = this.$refs.form;
      form.reset();
      Event.fire("closeResponsibilityDialog");
    }
  }
};
</script>