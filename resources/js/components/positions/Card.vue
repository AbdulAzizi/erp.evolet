<template>
  <div>
    <v-card>
      <v-toolbar color="primary" dark flat>
        <v-toolbar-title v-if="!edit">{{position.name}}</v-toolbar-title>
        <v-toolbar-title v-else>
          <v-text-field
            v-model="positionName"
            label="Название"
            required
            solo
            flat
            hide-details
            background-color="primary darken-1"
            append-icon="mdi-check"
            append-outer-icon="mdi-close"
            :placeholder="position.name"
            @click:append="editPosition()"
            @click:append-outer="resetPositionEditForm()"
          ></v-text-field>
        </v-toolbar-title>
        <v-spacer />
        <edit-add-actions :position="position" />
      </v-toolbar>
      <v-card-text class="pa-0" v-if="position.responsibilities.length > 0">
        <v-list class="ml-2 mr-4" flat>
          <v-list-group
            v-for="(responsibility, index) in position.responsibilities"
            :key="index"
            :ripple="false"
          >
            <template v-slot:activator>
              <v-hover v-slot:default="{ hover }">
                <v-list-item>
                  <v-list-item-title>{{ responsibility.text }}</v-list-item-title>
                  <v-list-item-action class="ma-0" v-if="hover">
                    <v-btn icon small @click.stop="deleteResponsibility(responsibility.id)">
                      <v-icon small>mdi-delete</v-icon>
                    </v-btn>
                  </v-list-item-action>
                  <v-list-item-action class="ma-0" v-if="hover">
                    <v-btn icon small @click.stop="editResponsibility(responsibility)">
                      <v-icon small>mdi-pencil</v-icon>
                    </v-btn>
                  </v-list-item-action>
                </v-list-item>
              </v-hover>
            </template>
            <v-hover v-slot:default="{ hover }">
              <v-row class="mx-4">
                <v-col cols="10">
                  <div>Lorem ipsum dolor sit amet consectetur adipisicing elit. Culpa odio nobis ad quas cum numquam? Delectus iure laborum error rem ad minus deleniti eos inventore exercitationem. Reiciendis perferendis quo rerum.</div>
                </v-col>
                <v-col cols="2" v-if="hover">
                  <v-btn icon small>
                    <v-icon small>mdi-delete</v-icon>
                  </v-btn>
                  <v-btn icon small>
                    <v-icon small>mdi-pencil</v-icon>
                  </v-btn>
                </v-col>
              </v-row>
            </v-hover>
            <v-divider />
            <v-btn text small color="primary" class="mx-4">
              <v-icon small>mdi-plus-circle</v-icon>Добавить объязанность
            </v-btn>
          </v-list-group>
        </v-list>
      </v-card-text>
      <v-divider></v-divider>
      <v-btn class="py-2" text block color="primary" @click="addResponsibility = true">
        <v-icon small>mdi-plus-circle</v-icon>Добавить объязанность
      </v-btn>
    </v-card>
    <v-dialog v-model="addResponsibility" width="600">
      <add-responsibility :position="localPosition" />
    </v-dialog>
    <v-dialog eager v-model="editResponsibilityDialog" width="600">
      <edit-responsibility />
    </v-dialog>
    <v-dialog eager v-model="deleteResponsibilityDialog" width="400">
      <delete-responsibility />
    </v-dialog>
  </div>
</template>
<script>
export default {
  props: {
    position: {}
  },
  data() {
    return {
      edit: false,
      positionName: null,
      localPosition: this.position,
      descriptionDialog: false,
      addResponsibility: false,
      editResponsibilityDialog: false,
      deleteResponsibilityDialog: false,
      responsibilityForEdit: null
    };
  },
  methods: {
    resetPositionEditForm() {
      this.edit = false;
      this.positionName = null;
    },
    editPosition() {
      if (this.positionName !== null) {
        axios
          .post(`/api/edit/position/${this.position.id}`, {
            name: this.positionName
          })
          .then(res => {
            this.localPosition.name = res.data.name;
            this.resetPositionEditForm();
          })
          .catch(err => err.messages);
      }
    },
    editResponsibility(responsibility) {
      this.editResponsibilityDialog = true;
      Event.fire('responsibility', responsibility);
    },
    deleteResponsibility(responsibilityId) {
      this.deleteResponsibilityDialog = true;
      Event.fire('deleteResponsibility', responsibilityId)
    }
  },
  created() {
    Event.listen("editPosition", positionId => {
      if (this.position.id == positionId) {
        this.edit = true;
      }
    });
    Event.listen("responsibilityAdded", data => {
      this.addResponsibility = false;
      this.localPosition.responsibilities.push(data);
    });
    Event.listen("closeResponsibilityDialog", data => {
      this.addResponsibility = false;
    });
    Event.listen("editResponsibility", editedResponsibility => {
      this.localPosition.responsibilities.forEach(responsibility => {
        if(responsibility.id == editedResponsibility.id){
          responsibility.text = editedResponsibility.text;
        }
      })
      this.editResponsibilityDialog = false;
    })
    Event.listen("cancelResponsibilityEdit", data => {
      this.editResponsibilityDialog = false;
    });

    Event.listen("dontDeleteResponsibility", responsibility => this.deleteResponsibilityDialog = false);

    Event.listen("responsibilityDeleted", responsibilityId => {
      this.deleteResponsibilityDialog = false;

      this.localPosition.responsibilities.forEach((responsibility, index) => {
        if(responsibility.id == responsibilityId){
          this.localPosition.responsibilities.splice(index, 1);
        }
      });
    })
  }
};
</script>