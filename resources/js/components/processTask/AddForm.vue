<template>
  <div>
    <v-card>
      <v-toolbar dense flat dark color="primary">
        <v-toolbar-title>Добавить форму</v-toolbar-title>
      </v-toolbar>
      <v-form>
        <v-card-text>
          <v-row>
            <v-col :cols="field.col">
              <form-field :field="field" v-model="field.value"></form-field>
            </v-col>
          </v-row>
        </v-card-text>
        <v-card-actions>
          <v-spacer />
          <v-btn text small color="primary">Отмена</v-btn>
          <v-btn small color="primary" @click="submit()">Добавить</v-btn>
        </v-card-actions>
      </v-form>
    </v-card>
  </div>
</template>
<script>
export default {
  props: ["taskId"],
  data() {
  const items = this.getFields();
    return {
      field: {
        name: "formName",
        value: null,
        type: "select",
        rules: ["required"],
        label: "Выберите форму",
        items: items,
        col: 12
      },
      items: null,
      list: null
    };
  },
  methods: {
    submit(){
      axios.post('/api/process/task/add/form', {
        processTaskId: this.taskId,
        formId: this.field.value
      }).then(res => {
        Event.fire('formAdded', {
          taskId: this.taskId,
          data: res.data
        });
      }).catch(err => err.messages);
    },
    getFields() {
      let items = [];
      axios.get("/api/get/forms").then(res => {
        items.push(...res.data);
      });
      return items;
    }
  }
};
</script>