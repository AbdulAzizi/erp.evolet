<template>
  <div>
    <v-card>
      <v-toolbar color="primary" dark flat>
        <v-toolbar-title v-if="!edit">{{responsibility.name}}</v-toolbar-title>
        <v-toolbar-title v-else>
          <v-text-field
            v-model="responsibilityName"
            label="Название"
            required
            solo
            flat
            hide-details
            background-color="primary darken-1"
            append-icon="mdi-check"
            append-outer-icon="mdi-close"
            :placeholder="responsibility.name"
            @click:append="editResponsibilityName()"
            @click:append-outer="resetEditForm()"
          ></v-text-field>
        </v-toolbar-title>
        <v-spacer />
        <edit-add-actions :responsibility="responsibility" />
      </v-toolbar>
      <v-card-text v-if="responsibility.descriptions.length" class="pa-0 ma-0">
        <template v-for="(description, subIndex) in responsibility.descriptions">
          <v-hover v-slot:default="{ hover }" :key="'hover' + subIndex">
            <div :key="'description' + subIndex" class="ma-4">
              <span>{{subIndex + 1}}. {{ description.text }}</span>
              <span class="float-right mr-3 grey--text" v-if="!hover">
                <v-icon small>mdi-timer-sand-full</v-icon>
                {{ days(description.planned_time) }} {{ hours(description.planned_time) }} {{ minutes(description.planned_time) }}
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
                <v-btn x-small icon>
                  <v-icon small @click="getDataBeforeEdit(description)">mdi-pencil</v-icon>
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
                  :rules="simpleRules"
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
                  :rules="simpleRules"
                ></v-slider>
              </v-col>
              <v-col cols="4">
                <v-form ref="estimateDays">
                  <v-text-field
                    v-model="estimateDays"
                    name="days"
                    label="Дни"
                    rounded
                    type="number"
                    filled
                    :rules="rules"
                  ></v-text-field>
                </v-form>
              </v-col>
              <v-col cols="4">
                <v-text-field
                  v-model="estimateHours"
                  label="Часы"
                  name="hours"
                  type="number"
                  rounded
                  filled
                  ref="estimateHours"
                  :rules="rules"
                ></v-text-field>
              </v-col>
              <v-col cols="4">
                <v-text-field
                  v-model="estimateMinutes"
                  label="Минуты"
                  name="minutes"
                  type="number"
                  rounded
                  filled
                  ref="estimateMinutes"
                  :rules="rules"
                ></v-text-field>
              </v-col>
            </v-row>
          </v-form>
          <v-card-actions>
            <v-spacer />
            <v-btn text color="primary" @click="descriptionDialog = false">отмена</v-btn>
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
    responsibility: {}
  },
  data() {
    return {
      edit: false,
      responsibilityName: null,
      localResponsibility: this.responsibility,
      descriptionDialog: false,
      description: {
        text: null,
        level: null
      },
      estimateDays: null,
      estimateHours: null,
      estimateMinutes: null,
      estimateTime: null,
      estimateDaysValid: null,
      estimateHoursValid: null,
      estimateMinutesValid: null,
      rules: [v => !!this.estimateTime || "Обязательное поле"],
      simpleRules: [v => !!v || "Обязательное поле"]
    };
  },
  methods: {
    resetEditForm() {
      this.edit = false;
      this.responsibilityName = null;
    },
    editResponsibilityName() {
      if (this.responsibilityName !== null) {
        axios
          .post(`/api/edit/responsibility/${this.responsibility.id}`, {
            name: this.responsibilityName
          })
          .then(res => {
            this.localResponsibility.name = res.data.name;
            this.resetEditForm();
          })
          .catch(err => err.messages);
      }
    },
    deleteDescription(item) {
      axios
        .delete(`/api/delete/description/${item.id}`)
        .then(res => {
          this.localResponsibility.descriptions.forEach(
            (description, index) => {
              if (description.id == item.id) {
                this.localResponsibility.descriptions.splice(index, 1);
              }
            }
          );
        })
        .catch(err => err.messages);
    },
    editDescription() {
      const form = this.$refs.form;
      this.estimateTime = this.estimateDays || this.estimateHours || this.estimateMinutes;
      this.estimateDaysValid = this.$refs.estimateDays.validate(true);
      this.estimateHoursValid = this.$refs.estimateHours.validate(true);
      this.estimateMinutesValid = this.$refs.estimateMinutes.validate(true);

      if (form.validate()) {
        axios
          .post(`/api/edit/description/${this.description.id}`, {
            description: this.description,
            days: this.estimateDays,
            hours: this.estimateHours,
            minutes: this.estimateMinutes
          })
          .then(res => {
            this.descriptionDialog = false;
            this.localResponsibility.descriptions.forEach(description => {
              if (description.id == this.description.id) {
                description.text = res.data.text;
                description.level = res.data.level;
                description.planned_time = res.data.planned_time;
              }
            });
          })
          .catch(err => err.messages);
      }
    },
    getDataBeforeEdit(item) {
      this.descriptionDialog = true;
      this.description = {
        id: item.id,
        text: item.text,
        level: item.level
      };

      (this.estimateDays = parseInt(this.days(item.planned_time))),
        (this.estimateHours = parseInt(this.hours(item.planned_time))),
        (this.estimateMinutes = parseInt(this.minutes(item.planned_time)));
    }
  },
  watch: {
    estimateTime(val) {
      this.estimateDaysValid;
      this.estimateHoursValid;
      this.estimateMinutesValid;
    },
    estimateDays(val) {
      this.estimateTime = val;
    },
    estimateHours(val) {
      this.estimateTime = val;
    },
    estimateMinutes(val) {
      this.estimateTime = val;
    }
  },
  created() {
    Event.listen("editResponsibility", responsibilityId => {
      if (this.responsibility.id == responsibilityId) {
        this.edit = true;
      }
    });
  }
};
</script>