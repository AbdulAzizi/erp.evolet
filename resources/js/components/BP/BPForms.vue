<template>
  <div>
    <v-btn fab fixed bottom right color="primary" @click="addFormDialog = !addFormDialog">
      <v-icon>mdi-plus</v-icon>
    </v-btn>
    <v-row>
      <v-col v-for="(form, index) in localForms" :key="index" cols="4">
        <v-card>
          <v-card-title>
            <a :href="`forms/${form.id}`">{{form.name}}</a>
            <v-spacer />
            <v-btn icon @click="beforeDeleteForm(form.id)">
                <v-icon small>
                    mdi-delete
                </v-icon>
            </v-btn>
          </v-card-title>
        </v-card>
      </v-col>
    </v-row>
    <v-dialog v-model="addFormDialog" width="600">
      <v-card>
        <v-toolbar dense flat dark color="primary">
          <v-toolbar-title>Создать новую форму</v-toolbar-title>
        </v-toolbar>
        <v-form ref="form">
          <v-card-text>
            <form-field
              :field="{
            name: 'formName',
            type: 'string',
            label: 'Название формы',
            }"
              v-model="formName"
            ></form-field>
            <form-field
              :field="{
            name: 'formLabel',
            type: 'string',
            label: 'Аббревиатура'
            }"
              v-model="formLabel"
            ></form-field>
          </v-card-text>
        </v-form>
        <v-card-actions>
          <v-spacer />
          <v-btn text color="primary" @click="addFormDialog = !addFormDialog">отмена</v-btn>
          <v-btn color="primary" @click="createForm()">Создать</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-dialog v-model="beforeDeleteDialog" width="400">
        <v-card>
        <v-card-title class="headline">Вы хотите удалить форму?</v-card-title>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="red lighten-2" text @click="beforeDeleteDialog = false">Отмена</v-btn>
          <v-btn color="primary" text @click="deleteForm(formId)">Удалить</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>
<script>
export default {
  props: ["forms"],
  data() {
    return {
      localForms: this.forms,
      addFormDialog: false,
      formName: null,
      formLabel: null,
      formId: null,
      beforeDeleteDialog: false
    };
  },
  methods: {
    createForm() {
      axios
        .post("/api/forms/create", {
          name: this.formName,
          label: this.formLabel
        })
        .then(res => {
          this.localForms.push(res.data);
          this.addFormDialog = !this.addFormDialog;
          Event.fire('notify', ['Создана форма']);
        })
        .catch(err => err.messages);
    },
    deleteForm(id){
        axios.delete(`/api/forms/delete/${id}`).then(res => 
        {
            this.localForms.forEach((elem, index) => {
                if(elem.id == id){
                    this.localForms.splice(index, 1);
                }
            })
            this.beforeDeleteDialog = false;
            Event.fire('notify', ['Форма удалена']);
            

        }).catch(err => err.messages);
    },
    beforeDeleteForm(id){
        this.formId = id;
        this.beforeDeleteDialog = true;
    }
  },
  created() {
    console.log(this.forms);
  }
};
</script>