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
            @click:append="editPositionName()"
            @click:append-outer="resetEditForm()"
          ></v-text-field>
        </v-toolbar-title>
        <v-spacer />
        <edit-add-actions :position="position" />
      </v-toolbar>
      <v-card-text v-if="position.descriptions.length" class="pa-0 ma-0">
        <template v-for="(description, subIndex) in position.descriptions">
          <v-hover v-slot:default="{ hover }" :key="'hover' + subIndex">
            <div :key="'description' + subIndex" class="ma-4">
              <span>{{subIndex + 1}}. {{ description.text }}</span>
              <span class="float-right mr-3 grey--text" v-if="!hover">
                <v-icon small>mdi-timer-sand-full</v-icon>
                {{ durObj(description.planned_time) }}
              </span>
              <span class="float-right mr-3 grey--text" v-if="!hover">
                <v-icon small>mdi-seal</v-icon>
                {{ description.level }}
              </span>
              <span class="float-right mr-3" v-if="hover">
                <v-btn x-small icon @click="deleteDescription(description)">
                  <v-icon small>mdi-delete</v-icon>
                </v-btn>
              </span>
              <span class="float-right" v-if="hover">
                <v-btn x-small icon @click="getDataBeforeEdit(description)">
                  <v-icon small>mdi-pencil</v-icon>
                </v-btn>
              </span>
            </div>
          </v-hover>
          <v-divider :key="'divider-'+subIndex"></v-divider>
        </template>
      </v-card-text>
      <v-card-text v-else class="text-center">
        <span class="mt-2">Инструкций нет</span>
      </v-card-text>
    </v-card>
    <v-dialog v-model="descriptionDialog" width="600">
      <v-card>
        <v-toolbar dark dense flat color="primary">
          <v-toolbar-title>Изменить инструкцию</v-toolbar-title>
        </v-toolbar>
        <v-card-text>
          <v-form class="mt-5" ref="form">
            <v-row>
              <v-col cols="12">
                <v-textarea
                  v-model="description.text"
                  name="text"
                  label="Должностные инструкции"
                  rounded
                  filled
                ></v-textarea>
              </v-col>
              <v-col cols="12">
                <v-slider
                  v-model="description.level"
                  rounded
                  filled
                  step="1"
                  :max="10"
                  ticks="always"
                  thumb-label="always"
                  :thumb-size="24"
                  name="level"
                  label="Уровень"
                ></v-slider>
              </v-col>
              <v-col cols="4">
                <v-text-field
                  v-model="days"
                  name="days"
                  label="Дни"
                  rounded
                  type="number"
                  filled
                ></v-text-field>
              </v-col>
              <v-col cols="4">
                <v-text-field
                  v-model="hours"
                  label="Часы"
                  name="hours"
                  type="number"
                  rounded
                  filled
                ></v-text-field>
              </v-col>
              <v-col cols="4">
                <v-text-field
                  v-model="minutes"
                  label="Минуты"
                  name="minutes"
                  type="number"
                  rounded
                  filled
                ></v-text-field>
              </v-col>
            </v-row>
          </v-form>
          <v-card-actions>
            <v-spacer />
            <v-btn text color="primary">отмена</v-btn>
            <v-btn dark color="primary" @click="editDescription()">изменить</v-btn>
          </v-card-actions>
        </v-card-text>
      </v-card>
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
      description: {
        text: null,
        level: null,
      },
      days: null,
      hours: null,
      minutes: null,
      rules: [v => !!v || "Обязательное поле"]
    };
  },
  methods: {
    resetEditForm() {
      this.edit = false;
      this.positionName = null;
    },
    editPositionName() {
      if (this.positionName !== null) {
        axios
          .post(`/api/edit/position/${this.position.id}`, {
            name: this.positionName
          })
          .then(res => {
            this.localPosition.name = res.data.name;
            this.resetEditForm();
          })
          .catch(err => err.messages);
      }
    },
    deleteDescription(item) {
      axios
        .delete(`/api/delete/description/${item.id}`)
        .then(res => {
          this.localPosition.descriptions.forEach(
            (description, index) => {
              if (description.id == item.id) {
                this.localPosition.descriptions.splice(index, 1);
              }
            }
          );
        })
        .catch(err => err.messages);
    },
    editDescription() {
      const form = this.$refs.form;
      if (form.validate()) {
        axios.post(`/api/edit/description/${this.description.id}`, {
         text: this.description.text,
         level: this.description.level,
         days: this.days,
         hours: this.hours,
         minutes: this.minutes
        }).then(res => {
          this.descriptionDialog = false;
          this.localPosition.descriptions.forEach(description => {
            if(description.id == this.description.id){
              description.planned_time = res.data;
            }
          })
        }).catch(err => err.messages);
      }
    },
    getDataBeforeEdit(item){
      this.descriptionDialog = true;
      this.description = item;
    }
  },
  created() {
    Event.listen("editPosition", positionId => {
      if (this.position.id == positionId) {
        this.edit = true;
      }
    });
  }
};
</script>