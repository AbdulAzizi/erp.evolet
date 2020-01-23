<template>
  <div>
    <v-btn fab fixed bottom right color="primary" @click="addPositionDialog = !addPositionDialog">
      <v-icon>mdi-plus</v-icon>
    </v-btn>
    <v-dialog persistent width="600" v-model="addPositionDialog">
      <v-card>
        <v-toolbar flat dense dark color="primary">
          <v-toolbar-title>Добавить должностную объязанность</v-toolbar-title>
        </v-toolbar>
        <v-card-text>
          <v-form class="my-5" ref="addPositionForm">
            <v-text-field
              name="position"
              rounded
              filled
              label="Должностная объязанность"
              v-model="positionName"
              :rules="rules"
            ></v-text-field>
            <v-divider v-if="positionDescriptions.length > 0" class="ma-2" />
            <v-row>
              <template v-for="(description, index) in positionDescriptions">
                <v-col cols="12" :key="'desc' + index">
                  <v-textarea
                    v-model="description.text"
                    :key="index"
                    :name="'description' + index"
                    label="Должностные инструкции"
                    rounded
                    filled
                  ></v-textarea>
                </v-col>
                <v-col cols="12" :key="'weight' + index">
                  <v-slider
                    v-model="description.level"
                    rounded
                    filled
                    step="1"
                    :max="10"
                    ticks="always"
                    thumb-label="always"
                    :thumb-size="24"
                    :name="'weight' + index"
                    label="Уровень"
                  ></v-slider>
                </v-col>
                <v-col cols="4" :key="'day' + index">
                  <v-text-field
                    v-model="description.days"
                    label="Дни"
                    :name="'day' + index"
                    rounded
                    type="number"
                    filled
                    :rules="rules"
                    ref="estimateDays"
                  ></v-text-field>
                </v-col>
                <v-col cols="4" :key="'hour' + index">
                  <v-text-field
                    v-model="description.hours"
                    label="Часы"
                    :name="'hour' + index"
                    type="number"
                    rounded
                    filled
                    :rules="rules"
                    ref="estimateHours"
                  ></v-text-field>
                </v-col>
                <v-col cols="4" :key="'minute' + index">
                  <v-text-field
                    v-model="description.minutes"
                    label="Минуты"
                    :name="'minute' + index"
                    type="number"
                    rounded
                    filled
                    :rules="rules"
                    ref="estimateMinutes"
                  ></v-text-field>
                </v-col>
                <v-divider
                  :key="'divider' + index"
                  v-if="index < positionDescriptions.length"
                  class="ma-2"
                />
              </template>
            </v-row>
            <v-btn
              rounded
              outlined
              block
              color="primary"
              @click="addPositionDescription()"
            >Добавить интсрукцию</v-btn>
          </v-form>
        </v-card-text>
        <v-card-actions>
          <v-spacer />
          <v-btn text color="primary" class="float-right" @click="resetForm()">отмена</v-btn>
          <v-btn dark color="primary" class="float-right" @click="submit()">создать</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>
<script>
export default {
  props: ["division", "user", "positions"],
  data() {
    return {
      addPositionDialog: false,
      positionName: null,
      positionDescriptions: [],
      rules: [v => !!v || "Обязательное поле"]
    };
  },
  methods: {
    addPositionDescription() {
      this.positionDescriptions.push({
        text: null,
        level: null,
        days: null,
        hours: null,
        minutes: null
      });
    },
    submit() {
      const form = this.$refs.addPositionForm;
      if (form.validate()) {
        axios
          .post(`/api/add/position/${this.division.id}`, {
            descriptions: this.positionDescriptions,
            name: this.positionName
          })
          .then(res => {
            Event.fire("positionAdded", res.data);
            this.resetForm();
          })
          .catch(err => err.messages);
      }
    },
    resetForm() {
      const form = this.$refs.addPositionForm;
      this.addPositionDialog = !this.addPositionDialog;
      form.reset();
      this.positionDescriptions.length = 0;
    }
  },
  watch: {
    responsibilityDescriptions: {
      handler: function(elem) {
        elem.forEach(item => {
          this.isValid = item.days || item.hours || item.minutes;
        });
      },
      deep: true
    },
    isValid(val) {
      this.daysValid;
      this.hoursValid;
      this.minutesValid;
    }
  }
};
</script>