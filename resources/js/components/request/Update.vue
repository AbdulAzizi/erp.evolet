<template>
  <v-dialog v-model="dialog" width="500" persistent>
    <template v-slot:activator="{ on }">
      <v-btn icon small v-on="on">
        <v-icon>mdi-pencil-outline</v-icon>
      </v-btn>
    </template>
    <v-card>
      <v-toolbar dark dense flat color="primary">
        <v-toolbar-title class="font-weight-bold">Изменить заявку</v-toolbar-title>
      </v-toolbar>
      <v-card-text>
        <v-form ref="form" class="mt-4">
          <v-row>
            <v-col cols="12">
              <v-select
                v-model="type"
                :items="types"
                label="Выберите тип заявки"
                rounded
                filled
                hide-details="auto"
              ></v-select>
            </v-col>
            <v-col cols="12" v-if="type === 'Оборудования'">
              <v-combobox
                v-model="equipmentValues"
                :items="equipments"
                label="Выберите оборудование"
                rounded
                filled
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
            <v-col cols="12" v-if="type === 'Отпуск' || type === 'Больничный'">
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
        <v-btn :disabled="!btnIsDisabled" @click="update()" depressed color="primary">Отправить</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>
<script>
export default {
  props: {
    request: {
      required: true
    }
  },
  data() {
    return {
      dialog: false,
      menu: false,
      type: null,
      dates: [],
      equipmentValues: [],
      description: null,
      salaryAdvanceValue: null,
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
      ]
    };
  },
  created() {
    this.setValues();
  },
  methods: {
    cancel() {
      this.dialog = false;
      this.setValues();
    },
    update() {
      axios.put(`/api/requests/${this.request.id}`, {
        type: this.type,
        description: this.description,
        values: this.values
      }).then(res => {
        this.dialog = false;
        Event.fire('requestUpdated', res.data);
      }).catch(err => {
        this.dialog = false;
        this.setValues();
      });
    },
    setValues() {
      this.type = this.request.type;
      this.description = this.request.description;
      switch (this.type) {
        case "Больничный":
        case "Отпуск":
          this.dates = this.request.parameters[0].value.split(" ");
          break;
        case "Оборудования":
          this.equipmentValues = this.request.parameters.map(el => el.value);
          break;
        default:
          this.salaryAdvanceValue = this.request.parameters[0].value;
          break;
      }
    }
  },
  computed: {
    values() {
      return this.equipmentValues.length > 0
             ? this.equipmentValues
             : (this.dates.length > 0 
             ? this.dates.join(' ') 
             : this.salaryAdvanceValue);
    },
    btnIsDisabled() {
      return (
        this.dates.length > 0 ||
        this.equipmentValues.length > 0 ||
        this.salaryAdvanceValue
      );
    },
    dateRange() {
      return this.dates.join(" ~ ");
    }
  },
  watch: {
    type(newValue, oldValue) {
      if(newValue && !oldValue) {
        this.setValues();
      } else {
        this.dates = [];
        this.equipmentValues = [];
        this.description = null;
        this.salaryAdvanceValue = null;
        }
    }
  }
};
</script>