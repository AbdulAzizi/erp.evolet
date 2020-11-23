<template>
  <v-dialog width="600" v-model="dialog" persistent>
    <template v-slot:activator="{ on }">
      <v-btn v-on="on" class="font-weight-bold" depressed color="primary">Создать заявку</v-btn>
    </template>
    <v-card>
      <v-toolbar dark flat dense color="primary">
        <v-toolbar-title class="font-weight-bold">Создать заявку</v-toolbar-title>
      </v-toolbar>
      <v-card-text>
        <v-form ref="form" class="mt-4">
          <v-row>
            <v-col cols="12">
              <v-select
                label="Выберите тип заявки"
                rounded
                filled
                v-model="type"
                :items="types"
                hide-details="auto"
                @change="reset()"
              ></v-select>
            </v-col>
            <v-col cols="12" v-if="type === 'Оборудования'">
              <v-combobox
                label="Выберите оборудование"
                rounded
                filled
                v-model="equipmentValues"
                :items="equipments"
                hide-details="auto"
                chips
                clearable
                multiple
                single-line
              >
                <template v-slot:selection="data">
                  <v-chip
                    class="my-2"
                    color="primary"
                    close
                    :input-value="data.selected"
                    @click:close="data.parent.selectItem(data.item)"
                  >{{ data.item }}</v-chip>
                </template>
              </v-combobox>
            </v-col>
            <v-col cols="12" v-if="type === 'Больничный' || type === 'Отпуск'">
              <v-menu
                ref="menu"
                v-model="menu"
                :close-on-content-click="false"
                :return-value.sync="dates"
                :nudge-right="40"
                transition="scale-transition"
                offset-y
                min-width="290px"
              >
                <template v-slot:activator="{ on, attrs }">
                  <v-text-field
                    v-model="dateRange"
                    label="Выберите желаемый период отпуска"
                    v-bind="attrs"
                    v-on="on"
                    filled
                    rounded
                    hide-details="auto"
                  ></v-text-field>
                </template>
                <v-date-picker range v-model="dates" multiple>
                  <v-spacer></v-spacer>
                  <v-btn text color="primary" @click="menu = false">Cancel</v-btn>
                  <v-btn text color="primary" @click="$refs.menu.save(dates)">OK</v-btn>
                </v-date-picker>
              </v-menu>
            </v-col>
            <v-col cols="12" v-if="type === 'Аванс' || type === 'Повышение зарплаты'">
              <v-text-field
                v-model="salaryAdvanceValue"
                filled
                rounded
                hide-details="auto"
                :placeholder="type === 'Аванс' ? 'Укажите сумму аванса' : 'Укажите сумму повышения зарплаты'"
              ></v-text-field>
            </v-col>
            <v-col cols="12" v-if="equipmentValues.length || dates.length || salaryAdvanceValue">
              <v-textarea
                hide-details="auto"
                placeholder="Я, Азизов Азиз Азизович хочу..."
                rounded
                filled
                v-model="description"
              />
            </v-col>
          </v-row>
        </v-form>
      </v-card-text>
      <v-card-actions class="px-4 pb-4">
        <v-spacer />
        <v-btn text color="primary" @click="cancel()">Отмена</v-btn>
        <v-btn :disabled="!btnIsDisabled" @click="create()" depressed color="primary">Отправить</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>
<script>
export default {
  data() {
    return {
      dialog: false,
      type: null,
      description: null,
      dates: [],
      menu: false,
      salaryAdvanceValue: null,
      disabled: null,
      types: [
        "Оборудования",
        "Отпуск",
        "Повышение зарплаты",
        "Больничный",
        "Аванс"
      ],
      equipments: [
        "Клавиатура",
        "Ноутбук",
        "Системный блок",
        "Мышь",
        "Наушники"
      ],
      equipmentValues: []
    };
  },
  methods: {
    reset() {
      this.description = this.salaryAdvanceValue = null;
      this.dates = this.equipmentValues = [];
    },
    cancel() {
      const FORM = this.$refs.form;
      this.dialog = false;
      FORM.reset();
      this.dates = this.equipmentValues = [];
    },
    create() {
      axios.post('/api/requests', {
        type: this.type,
        description: this.description,
        values: this.values
      }).then(res => {
          Event.fire('requestCreated', res.data);
          this.dialog = false;
          this.cancel();
      }).catch(err => console.error(err.message))
    }
  },
  computed: {
    dateRange() {
      return this.dates.join(" ∼ ");
    },
    btnIsDisabled() {
      return this.dates.length > 0 || this.equipmentValues.length > 0 || this.salaryAdvanceValue;
    },
    values() {
      return this.equipmentValues.length > 0
             ? this.equipmentValues
             : (this.dates.length > 0 
             ? this.dates.join(' ') 
             : this.salaryAdvanceValue);
    }
  },
  created() {
  }
};
</script>