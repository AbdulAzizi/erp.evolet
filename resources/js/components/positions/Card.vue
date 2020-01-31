<template>
  <div>
    <v-card>
      <v-toolbar color="primary" dark flat>
        <v-toolbar-title v-if="!edit">{{position.label}}</v-toolbar-title>
        <v-toolbar-title v-else>
          <v-text-field
            v-model="positionLabel"
            label="Название"
            required
            solo
            flat
            hide-details
            background-color="primary darken-1"
            append-icon="mdi-check"
            append-outer-icon="mdi-close"
            @click:append="editPosition()"
            @click:append-outer="resetPositionEditForm()"
          ></v-text-field>
        </v-toolbar-title>
        <v-spacer />
        <edit-add-actions :position="position" v-if="headUser || hrUser" />
      </v-toolbar>
      <v-card-text class="pa-0" v-if="position.responsibilities.length > 0">
        <v-list class="ml-2 mr-4" flat expand>
          <v-list-group
            v-for="(responsibility, index) in position.responsibilities"
            :key="index"
            active-class
            :ripple="false"
          >
            <template v-slot:activator>
              <v-hover v-slot:default="{ hover }">
                <v-row style="width: 95%">
                  
                  <v-col cols="9" class="ml-3">
                      <div class="float-left mr-2 font-weight-bold grey--text text--darken-3">{{ index + 1 }}.</div>
                      <div class="ml-5 font-weight-bold grey--text text--darken-3">{{ responsibility.text }}</div>
                  </v-col>
                  <v-col cols="2" v-if="hover" class="pb-0">
                     <v-btn
                        icon
                        small
                        @click.stop="deleteResponsibility(responsibility.id)"
                        v-if="headUser || hrUser"
                      >
                        <v-icon small>mdi-delete</v-icon>
                      </v-btn>
                      <v-btn
                        icon
                        small
                        @click.stop="editResponsibility(responsibility)"
                        v-if="headUser || hrUser"
                      >
                        <v-icon small>mdi-pencil</v-icon>
                      </v-btn>
                  </v-col>
                </v-row>
              </v-hover>
            </template>
            <v-row
              class="mx-5"
              v-for="(description, subIndex) in responsibility.descriptions"
              :key="subIndex"
            >
              <v-hover v-slot:default="{ hover }">
                <v-row style="border-bottom: 1px solid #e0e0e0; width: 100%" class="mx-2">
                  <v-col cols="10" class="pl-1">
                    <div class="float-left">{{index + 1}}.{{subIndex + 1}}.</div>
                    <div class=" grey--text text--darken-4 ml-7"
                    >{{ description.title }}</div>
                    <div class="grey--text text--darken-2 pl-7">{{ description.description }}</div>
                  </v-col>
                  <v-col cols="2" v-if="hover" class="pb-0">
                    <v-btn icon small v-if="headUser || hrUser">
                      <v-icon
                        small
                        @click="deleteResponsibilityDescription(description.id)"
                      >mdi-delete</v-icon>
                    </v-btn>
                    <v-btn icon small v-if="headUser || hrUser">
                      <v-icon small @click="editResponsibilityDescription(description)">mdi-pencil</v-icon>
                    </v-btn>
                  </v-col>
                </v-row>
              </v-hover>
            </v-row>
            <v-btn
              text
              small
              color="primary"
              class="mx-4 mt-2"
              @click="addResponsibilityDescription(responsibility.id)"
              v-if="headUser || hrUser"
            >
              <v-icon class="px-2" small>mdi-plus-circle</v-icon>Добавить должностную задачу
            </v-btn>
          </v-list-group>
        </v-list>
      </v-card-text>
      <v-divider></v-divider>
      <v-btn
        class="py-2"
        text
        block
        color="primary"
        @click="addResponsibilityDialog = true"
        v-if="headUser || hrUser"
      >
        <v-icon small class="px-2">mdi-plus-circle</v-icon>Добавить объязанность
      </v-btn>
      <p
        class="pa-4"
        v-if="!headUser && position.responsibilities.length == 0 && !hrUser"
      >Объязанностей нет</p>
    </v-card>
    <v-dialog persistent v-model="addResponsibilityDialog" width="600">
      <add-responsibility :position="localPosition" />
    </v-dialog>
    <v-dialog persistent eager v-model="editResponsibilityDialog" width="600">
      <edit-responsibility />
    </v-dialog>
    <v-dialog persistent eager v-model="deleteResponsibilityDialog" width="400">
      <delete-responsibility />
    </v-dialog>
    <v-dialog persistent eager v-model="addResponsibilityDescriptionDialog" width="600">
      <add-responsibility-description />
    </v-dialog>
    <v-dialog persistent eager v-model="editResponsibilityDescriptionDialog" width="600">
      <edit-responsibility-description />
    </v-dialog>
    <v-dialog persistent eager v-model="deleteResponsibilityDescriptionDialog" width="400">
      <delete-responsibility-description />
    </v-dialog>
  </div>
</template>
<script>
export default {
  props: {
    position: {},
    user: {}
  },
  data() {
    return {
      headUser: this.user.position_level.name == "Руководитель",
      hrUser: this.user.division.abbreviation == "ОУПС",
      edit: false,
      positionLabel: this.position.label,
      localPosition: this.position,
      descriptionDialog: false,
      addResponsibilityDialog: false,
      editResponsibilityDialog: false,
      deleteResponsibilityDialog: false,
      responsibilityForEdit: null,
      addResponsibilityDescriptionDialog: false,
      editResponsibilityDescriptionDialog: false,
      deleteResponsibilityDescriptionDialog: false
    };
  },
  methods: {
    resetPositionEditForm() {
      this.edit = false;
      this.positionLabel = this.position.label;
    },
    editPosition() {
      if (this.positionLabel !== null) {
        axios
          .post(`/api/edit/position/${this.position.id}`, {
            label: this.positionLabel
          })
          .then(res => {
            this.localPosition.label = res.data.label;
            this.resetPositionEditForm();
          })
          .catch(err => err.messages);
      }
    },
    editResponsibility(responsibility) {
      this.editResponsibilityDialog = true;
      Event.fire("responsibility", responsibility);
    },
    deleteResponsibility(responsibilityId) {
      this.deleteResponsibilityDialog = true;
      Event.fire("deleteResponsibility", responsibilityId);
    },
    addResponsibilityDescription(responsibilityId) {
      this.addResponsibilityDescriptionDialog = true;
      Event.fire("addResponsibilityDescription", responsibilityId);
    },
    editResponsibilityDescription(description) {
      this.editResponsibilityDescriptionDialog = true;
      Event.fire("editResponsibilityDescription", description);
    },
    deleteResponsibilityDescription(descriptionId) {
      this.deleteResponsibilityDescriptionDialog = true;
      Event.fire("deleteResponsibilityDescription", descriptionId);
    }
  },
  created() {
    Event.listen("editPosition", positionId => {
      if (this.position.id == positionId) {
        this.edit = true;
      }
    });
    Event.listen("responsibilityAdded", data => {
      this.addResponsibilityDialog = false;
      if (this.localPosition.id == data.position_id) {
        this.localPosition.responsibilities.push(data);
      }
    });
    Event.listen("closeResponsibilityDialog", data => {
      this.addResponsibilityDialog = false;
    });
    Event.listen("editResponsibility", editedResponsibility => {
      this.localPosition.responsibilities.forEach(responsibility => {
        if (responsibility.id == editedResponsibility.id) {
          responsibility.text = editedResponsibility.text;
        }
      });
      this.editResponsibilityDialog = false;
    });
    Event.listen("cancelResponsibilityEdit", data => {
      this.editResponsibilityDialog = false;
    });

    Event.listen(
      "dontDeleteResponsibility",
      responsibility => (this.deleteResponsibilityDialog = false)
    );

    Event.listen("responsibilityDeleted", responsibilityId => {
      this.deleteResponsibilityDialog = false;

      this.localPosition.responsibilities.forEach((responsibility, index) => {
        if (responsibility.id == responsibilityId) {
          this.localPosition.responsibilities.splice(index, 1);
        }
      });
    });

    Event.listen("responsibilityDescriptionAdded", data => {
      this.addResponsibilityDescriptionDialog = false;
      this.localPosition.responsibilities.forEach(responsibility => {
        if (responsibility.id == data.responsibilityId) {
          responsibility.descriptions.push(data.description);
        }
      });
    });

    Event.listen(
      "cancelDescriptionAdding",
      description => (this.addResponsibilityDescriptionDialog = false)
    );

    Event.listen("responsibilityDescriptionEdited", data => {
      this.editResponsibilityDescriptionDialog = false;
      this.localPosition.responsibilities.forEach(responsibility => {
        responsibility.descriptions.forEach(description => {
          if (description.id == data.id) {
            description.title = data.title;
            description.description = data.description;
          }
        });
      });
    });

    Event.listen(
      "cancelResponsibilityDescriptionEditing",
      description => (this.editResponsibilityDescriptionDialog = false)
    );

    Event.listen("responsibilityDescriptionDeleted", descriptionId => {
      this.localPosition.responsibilities.forEach(responsibility => {
        responsibility.descriptions.forEach((description, index) => {
          if (description.id == descriptionId) {
            responsibility.descriptions.splice(index, 1);
          }
        });
      });
      this.deleteResponsibilityDescriptionDialog = false;
    });

    Event.listen(
      "cancelResponsibilityDescriptionDeleteing",
      description => (this.deleteResponsibilityDescriptionDialog = false)
    );
  }
};
</script>
<style>
.v-list-group__items{
  color: black !important;
}

.v-list-item--active{
  color: black !important;
}
</style>