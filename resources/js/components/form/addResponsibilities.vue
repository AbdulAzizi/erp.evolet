<template>
  <div>
    <v-btn
      fab
      fixed
      bottom
      right
      color="primary"
      @click="addResponsibilityDialog = !addResponsibilityDialog"
    >
      <v-icon>mdi-plus</v-icon>
    </v-btn>
    <v-dialog persistent width="600" v-model="addResponsibilityDialog">
      <v-card>
        <v-toolbar flat dense dark color="primary">
          <v-toolbar-title>Добавить должностную объязанность</v-toolbar-title>
        </v-toolbar>
        <v-card-text>
          <v-form class="my-5" ref="addResponsibilityForm">
            <v-text-field
              name="responsibility"
              rounded
              filled
              label="Должностная объязанность"
              v-model="responsibilityName"
            ></v-text-field>
            <v-divider v-if="responsibilityDescriptions.length > 0" class="ma-2"/>
            <v-row>
              <template v-for="(description, index) in responsibilityDescriptions">
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
                  ref="estimateDays">
                  </v-text-field>
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
                  ref="estimateHours">
                  </v-text-field>
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
                  ref="estimateMinutes">
                  </v-text-field>
                </v-col>
               <v-divider :key="'divider' + index" v-if="index < responsibilityDescriptions.length" class="ma-2"/>
              </template>
            </v-row>
            <v-btn
              rounded
              outlined
              block
              color="primary"
              @click="addResponsibilityDescription()"
            >Добавить инструкцию</v-btn>
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
  props: ["division", "user", "responsibilities"],
  data() {
    return {
      addResponsibilityDialog: false,
      responsibilityName: null,
      responsibilityDescriptions: [],
      rules: [v => !!this.isValid || "Обязательное поле"],
      isValid: false,
      daysValid: null,
      hoursValid: null,
      minutesValid: null
    };
  },
  methods: {
    addResponsibilityDescription() {
      this.responsibilityDescriptions.push({
        text: null,
        level: null,
        days: null,
        hours: null,
        minutes: null
      });
    },
    submit() {
      const form = this.$refs.addResponsibilityForm;
      console.log(this.$refs);
      // this.daysValid = this.$refs.estimateDays.validate(true);
      // this.hoursValid = this.$refs['estimateHours'].validate(true);
      // this.minutesValid = this.$refs['estimateMinutes'].validate(true);
      if (form.validate()) {
        axios
          .post(`/api/add/responsibility/${this.division.id}`, {
            descriptions: this.responsibilityDescriptions,
            name: this.responsibilityName
          })
          .then(res => {
            Event.fire("responsibilityAdded", res.data);
            this.resetForm();
          })
          .catch(err => err.messages);
      }
    },
    resetForm() {
      const form = this.$refs.addResponsibilityForm;
      this.addResponsibilityDialog = !this.addResponsibilityDialog;
      form.reset();
      this.responsibilityDescriptions.length = 0;
    }
  },
  watch: {
    responsibilityDescriptions: {
      handler: function(elem){
       elem.forEach(item => {
         this.isValid = item.days || item.hours || item.minutes
       });
      },
      deep: true
    },
    isValid(val){
      this.daysValid;
      this.hoursValid;
      this.minutesValid;
    }
  }
};
</script>