<template>
  <v-card>
    <v-toolbar flat dark dense color="primary">
      <v-toolbar-title>Изменить должностную задачу</v-toolbar-title>
    </v-toolbar>
    <v-card-text>
      <v-form class="mt-5" ref="form">
        <form-field
          :field="{
                label: 'Должностная задача',
                name: 'text',
                rules: ['required'],
                type: 'string',
                value: text
            }"
          v-model="text"
        />
        <form-field
          :field="{
                label: 'Описание',
                name: 'text',
                type: 'text',
                value: description
            }"
          v-model="description"
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
  data() {
    return {
      text: null,
      description: null,
      descriptionId: null
    };
  },
  created() {
    Event.listen("editResponsibilityDescription", description => {
      this.text = description.text;
      this.description = description.description;
      this.descriptionId = description.id
    });
  },
  methods: {
      submitForm(){
          const form = this.$refs.form;
          if(form.validate){
              axios.post(`/api/edit/responsibility/description/${this.descriptionId}`, {
                  text: this.text,
                  description: this.description
              }).then(res => {
                  Event.fire("responsibilityDescriptionEdited", res.data);
              }).catch(err => err.messages);
          }
      },
      cancel(){
          Event.fire("cancelResponsibilityDescriptionEditing");
      }
  }
};
</script>