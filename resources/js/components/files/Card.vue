<template>
  <div>
    <v-card @click="dialog = true">
      <v-toolbar color="white" flat>
        <v-toolbar-title>{{localItem.name}}</v-toolbar-title>
        <v-spacer></v-spacer>
        <h4>{{localItem.fields.length}}</h4>
      </v-toolbar>
      <v-dialog
      v-model="deleteDialog"
      max-width="400"
    >
      <v-card>
        <v-card-title class="headline" v-if="fieldId">Действительно хотите удалить поле?</v-card-title>
        <v-card-title class="headline" v-else>Действительно хотите удалить файл {{localItem.name}}?</v-card-title>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
            color="red lighten-2"
            text
            @click="deleteDialog = false">отмена</v-btn>
          <v-btn
            color="primary"
            text
            @click="fieldId ? deleteFieldFromFile(fieldId, fileId) : deleteFile(fileId)">удалить</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    </v-card>
    <v-dialog v-model="dialog" width="600">
      <v-card>
        <v-toolbar dark flat color="primary">
           <v-toolbar-title v-if="!fileNameUpdateForm">{{localItem.name}}</v-toolbar-title>
            <v-toolbar-title v-else class="ma-2">
              <v-form ref="fileForm">
                <v-text-field
                  v-model="fileName"
                  label="Название"
                  required
                  solo
                  flat
                  hide-details
                  background-color="primary darken-1"
                  class="mb-1"
                  append-icon="mdi-check"
                  append-outer-icon="mdi-close"
                  @click:append="updateFileName(localItem.id)"
                  @click:append-outer="cancelFileUpdate()"
                  :placeholder="localItem.name"
                ></v-text-field>
              </v-form>
            </v-toolbar-title>
          <v-spacer></v-spacer>
          <v-btn text small fab @click="fileNameUpdateForm = true">
            <v-icon small>mdi-pencil</v-icon>
          </v-btn>
          <v-btn text small fab @click="alertBeforeFileDelete(localItem.id)">
            <v-icon small>mdi-delete</v-icon>
          </v-btn>
        </v-toolbar>
        <v-list dense class="py-0">
          <template v-for="(field, index) in localItem.fields">
            <v-hover v-slot:default="{hover}" :key="'hover' + index">
              <v-list-item :key="'item' + index">
                {{field.label}}
                <v-spacer></v-spacer>
                <v-list-item-action v-if="hover" class="ma-0">
                  <v-btn icon @click="alertBeforeFieldDelete(field.id, item.id)">
                    <v-icon small>mdi-delete</v-icon>
                  </v-btn>
                </v-list-item-action>
              </v-list-item>
            </v-hover>
            <v-divider :key="'divider' + index"></v-divider>
          </template>
          <add-field :file="localItem.id"/>
        </v-list>
      </v-card>
    </v-dialog>
  </div>
</template>
<script>
export default {
  props: ["item"],
  data() {
    return {
      dialog: false,
      fileName: null,
      localItem: this.item,
      fieldId: null,
      fileId: null,
      deleteDialog: false,
      fileNameUpdateForm:false
    };
  },
  methods: {
    deleteFile(id) {
      axios
        .delete(`/api/files/delete/${id}`)
        .then(res => {
          Event.fire("fileDeleted", res.data);
          this.dialog = false;
          Event.fire('notify', [`Файл ${this.localItem.name} удален`]);
        })
        .catch(err => err.messages);
    },
    alertBeforeFieldDelete(fieldId, fileId){
      this.fieldId = fieldId;
      this.fileId = fileId;
      this.deleteDialog = true;
    },
    alertBeforeFileDelete(id){
      this.fileId = id;
      this.fieldId = false;
      this.deleteDialog = true;
    },
    deleteFieldFromFile(fieldId, fileId){
        axios.post(`/api/files/field/delete/${fieldId}`, {fileId: fileId}).then(res => 
        {
          this.localItem.fields.forEach((element, index) => {
            if(element.id == fieldId){
              this.localItem.fields.splice(index, 1);
            }
          });
          this.deleteDialog = false;
          Event.fire('notify', [`Поле успешно удалено`]);
        }).catch(err => err.messages);
    },
    updateFileName(id){
      this.fileNameUpdateForm = true;
      if(this.fileName){
        axios.post(`/api/files/update/${id}`, {
          name: this.fileName
        }).then(res => {
          this.fileNameUpdateForm = false;
          Event.fire('notify', [`Файл ${this.localItem.name} переименован на ${this.fileName}`]);
          this.localItem.name = this.fileName;
          this.fileName = null;
        }).catch(err => err.messages);
      }
    },
    cancelFileUpdate(){
      this.fileNameUpdateForm = false;
    }
  },
  created(){
    Event.listen('fieldsCreated', data => {
      if(data.fileId == this.localItem.id){
        this.localItem.fields.push(...data.fields)
      }
    })
  }
};
</script>