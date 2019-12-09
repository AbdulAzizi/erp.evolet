<template>
<div>
    <v-dialog width="600" v-model="dialog">
        <v-card>
        <v-toolbar dense flat dark color="primary">
          <v-toolbar-title>Добавить поля</v-toolbar-title>
        </v-toolbar>
        <v-card-text>
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
          <v-btn dark color="primary" @click="submitField()">Добавить</v-btn>
        </v-toolbar>
      </v-card>
    </v-dialog>
  <v-btn small outlined color="primary" @click="getFields()" class="ma-2">
    Добавить поля
  </v-btn>
</div>
</template>
<script>
export default {
    props: ['file'],
    data(){
        return {
            dialog: false,
            fields: null,
            oddFields: null,
            evenFields: null,
            selectedFieldIds: []
        }
    },
    methods: {
    getFields() {
      axios
        .get("/api/files/fields")
        .then(res => {
          this.dialog = true;
          this.fields = res.data;
          this.oddFields = this.fields.filter(elem => elem.id % 2 == 0);
          this.evenFields = this.fields.filter(elem => elem.id % 2 !== 0);
        })
        .catch(err => err.messages);
    },
    cancelFileCreate() {
      this.selectedFieldIds = [];
      this.dialog = false;
    },
    submitField() {
      axios
        .post("/api/files/fields/create", {
          id: this.file,
          fields: this.selectedFieldIds
        })
        .then(res => {
            this.dialog = false;
            Event.fire('fieldsCreated', {fileId: this.file, fields: res.data})
            Event.fire('notify', ['Поля созданы']);
            this.selectedFieldIds = [];
        })
        .catch(err => err.messages);
    }
  }
}
</script>