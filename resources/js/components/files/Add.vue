<template>
  <div>
    <v-btn fab fixed bottom right color="primary" @click="getFields()">
      <v-icon>mdi-plus</v-icon>
    </v-btn>
    <v-dialog v-model="createFormDialog" width="600">
      <v-card>
        <v-toolbar dense flat dark color="primary">
          <v-toolbar-title>Создать файл</v-toolbar-title>
        </v-toolbar>
        <v-card-text>
          <v-form ref="form">
          <form-field
            :field="{
               label: 'Название файла',
               name: 'fileName',
               type: 'string',
               rules: ['required']                   
           }"
            v-model="fileName"
            class="mt-5"
          ></form-field>
          </v-form>
          <v-row>
            <v-col cols="6">
              <v-checkbox
                v-model="selectedFieldIds"
                v-for="(field, index) in oddFields"
                :key="index"
                :value="String(field.id)"
                :label="field.label"
                class="ma-0"
                hide-details
                color="primary"
              ></v-checkbox>
            </v-col>
            <v-col cols="6">
              <v-checkbox
                v-model="selectedFieldIds"
                v-for="(field, index) in evenFields"
                :key="index"
                :value="String(field.id)"
                :label="field.label"
                class="ma-0"
                hide-details
                color="primary"
              ></v-checkbox>
            </v-col>
          </v-row>
        </v-card-text>
        <v-toolbar dense style="position: sticky; bottom:0;">
          <v-spacer></v-spacer>
          <v-btn text color="red lighten-2" @click="cancelFileCreate()">Отмена</v-btn>
          <v-btn dark color="primary" @click="submitFile()">Добавить</v-btn>
        </v-toolbar>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
export default {
  data() {
    return {
      fields: null,
      oddFields: null,
      evenFields: null,
      createFormDialog: false,
      selectedFieldIds: [],
      fileName: null
    };
  },
  methods: {
    getFields() {
      axios
        .get("/api/fields")
        .then(res => {
          this.createFormDialog = true;
          this.fields = res.data;
          this.oddFields = this.fields.filter(elem => elem.id % 2 == 0);
          this.evenFields = this.fields.filter(elem => elem.id % 2 !== 0);
        })
        .catch(err => err.messages);
    },
    cancelFileCreate() {
      this.selectedFieldIds = [];
      this.fileName;
      this.createFormDialog = false;
    },
    submitFile() {
      let form = this.$refs.form;
      if(form.validate()){
        axios
          .post("/api/file/create", {
            name: this.fileName,
            fields: this.selectedFieldIds
          })
          .then(res => {
              this.createFormDialog = false;
              Event.fire('fileCreated', res.data)
              Event.fire('notify', [`Файл ${res.data[res.data.length - 1].name} создан`]);
              this.selectedFieldIds = [];
              this.fileName = null;
          })
         .catch(err => err.messages);
      }
    }
  }
};
</script>