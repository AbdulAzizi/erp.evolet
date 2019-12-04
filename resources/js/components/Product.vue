<template>
  <v-row>
    <v-dialog width="600" v-model="dialog">
      <v-card>
        <v-toolbar dense flat dark color="primary">
          <v-toolbar-title>Изменить поле</v-toolbar-title>
        </v-toolbar>
        <v-card-text>
          <form-field :field="selectedFields" v-model="fieldVal" class="mt-5"></form-field>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn text color="red lighten-2" @click="dialog = false">Отмена</v-btn>
          <v-btn color="primary" dark @click="submitEditedField()">Изменить</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-col cols="12" class="pt-0">
      <p class="title mb-0">
        {{ product.project.country.name }}
        ·
        {{ product.project.pc.name }}
      </p>
      <p class="grey--text text--darken-2 mb-1">
        <span class="grey--text">Текущий процесс:</span>
        {{ product.current_process.name }}
      </p>
      <v-btn small depressed color="primary" class="mb-0" @click="window.back()">Назад</v-btn>
      <v-btn
        small
        depressed
        color="primary"
        class="mb-0"
        :href="`/products/${product.id}/edit`"
      >Редактировать</v-btn>
    </v-col>

    <v-col lg="6" md="6" sm="12">
      <v-card flat class="pt-0">
        <v-simple-table fixed-header dense height="calc(100vh - 180px)">
          <thead>
            <tr>
              <th v-if="editAllowed"></th>
              <th class="text-left">Наименование</th>
              <th class="text-left">Значение</th>
            </tr>
          </thead>
          <tbody>
              <tr
                
                v-for="(field, index) in localProduct.fields_with_lists"
                :key="index"
              >
                <td v-if="editAllowed">
                  <v-btn text fab x-small color="grey" @click="displayEditForm(field)">
                    <v-icon x-small>mdi-pencil</v-icon>
                  </v-btn>
                </td>
                <td>{{ field.label }}</td>
                <td>{{ showFieldFromItems(field) }}</td>
            
              </tr>
          </tbody>
        </v-simple-table>
      </v-card>
    </v-col>

    <v-col lg="6" md="6" sm="12">
      <messages :messageable="product" type="App\Product" />
    </v-col>

    <v-col lg="6" md="6" sm="12" class="pt-0">
      <v-card class="mx-auto mb-2" outlined>
        <v-card-text>
          <div class="text-center">Участники продукта</div>
        </v-card-text>
        <v-list disabled class="py-0">
          <v-list-item-group color="primary">
            <template v-for="(participant, i) in participants.project_participant">
              <v-divider :key="'divider-'+i"></v-divider>
              <v-list-item :key="'participant-'+i">
                <v-list-item-avatar>
                  <v-img :src="photo(participant.participant.img)"></v-img>
                </v-list-item-avatar>
                <v-list-item-content>
                  <v-list-item-title>{{participant.role.name}}</v-list-item-title>
                  <v-list-item-subtitle>{{participant.participant.name}} {{participant.participant.surname}}</v-list-item-subtitle>
                </v-list-item-content>
              </v-list-item>
            </template>
          </v-list-item-group>
        </v-list>
      </v-card>
      <v-card outlined>
        <v-card-text class="px-0">
          <div class="text-center">История статуса</div>
          <p v-if="!product.processes.length">Нет событий</p>
          <v-simple-table dense class="mt-3">
            <template v-slot:default>
              <thead>
                <tr>
                  <th class="text-left">Статус</th>
                  <th class="text-left">Дата</th>
                  <th class="text-left">Потраченное время</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="status in localProduct.processes" :key="status.id">
                  <td>{{status.name}}</td>
                  <td>{{moment(status.pivot.created_at).local().format('DD-M-YY HH:mm')}}</td>
                  <td v-if="status.pivot.spent_time !== null">
                    <span
                      v-if="durObj(status.pivot.spent_time).days()"
                    >{{ durObj(status.pivot.spent_time).days() }} д</span>
                    <span
                      v-if="durObj(status.pivot.spent_time).hours()"
                    >{{ durObj(status.pivot.spent_time).hours() }} ч</span>
                    <span
                      v-if="durObj(status.pivot.spent_time).minutes()"
                    >{{ durObj(status.pivot.spent_time).minutes() }} мин</span>
                    <span
                      v-if="durObj(status.pivot.spent_time).seconds()"
                    >{{ durObj(status.pivot.spent_time).seconds() }} сек</span>
                  </td>
                </tr>
              </tbody>
            </template>
          </v-simple-table>
          <div class="mt-4 mx-3">
            <span v-if="localProduct.processes.length > 1">Общее количество времени:</span>
            <span v-if="durObj(overallSpentTime).days()">{{ durObj(overallSpentTime).days() }} д</span>
            <span v-if="durObj(overallSpentTime).hours()">{{ durObj(overallSpentTime).hours() }} ч</span>
            <span
              v-if="durObj(overallSpentTime).minutes()"
            >{{ durObj(overallSpentTime).minutes() }} мин</span>
            <span
              v-if="durObj(overallSpentTime).seconds()"
            >{{ durObj(overallSpentTime).seconds() }} сек</span>
          </div>
        </v-card-text>
      </v-card>
    </v-col>
    <v-col lg="6" md="6" sm="12" class="pt-0">
      <div class="text-center">Журнал действий продукта</div>
      <p v-if="!product.history.length">Нет событий</p>
      <history :history="product.history" v-if="product.history.length" />
    </v-col>

    <v-col lg="6" md="6" sm="12"></v-col>
  </v-row>
</template>

<script>
export default {
  props: ["product", "participants", "authuser"],
  data() {
    return {
      window: window.history,
      localProduct: this.product,
      overallSpentTime: null,
      dialog: false,
      selectedFields: null,
      fieldVal: null,
      editAllowed: false

    };
  },
  computed: {
    totalSpentTime() {
      let sum = 0;

      this.localProduct.processes.forEach(elem => {
        if (elem.pivot.spent_time) {
          sum += elem.pivot.spent_time;
        } else {
          sum += this.lastProcessSpentTime;
        }
      });

      return sum;
    },
    lastProcessSpentTime() {
      let len = this.localProduct.processes.length;

      let elem = this.moment(
        this.localProduct.processes[len - 1].pivot.created_at
      ).valueOf();

      let lastElementSpentTime = this.moment().valueOf() - elem;

      return lastElementSpentTime;
    }
  },

  methods: {
    displayEditForm(selectedField) {
      (this.selectedFields = {
        ...selectedField,
        rules:
          selectedField.pivot && selectedField.pivot.required
            ? ["required"]
            : [true],
        type: this.defineFieldType(selectedField),
        value:
          selectedField.type.name == "list" ||
          selectedField.type.name == "many-to-many-list" ||
          selectedField.type.name == "year"
            ? +selectedField.pivot.value
            : selectedField.pivot.value,
        label: selectedField.label
      }),
        (this.dialog = true);
    },
    submitEditedField() {
      axios
        .put(`/api/fields/edit/${this.selectedFields.id}`, {
          productId: this.localProduct.id,
          value: this.fieldVal
        })
        .then(response => {
          this.localProduct.fields_with_lists.forEach(elem => {
            if (response.data.field_id == elem.id) {
              elem.pivot = response.data;
            }
          });
          this.dialog = false;
          Event.fire("notify", [
            `Значение поля "${this.selectedFields.label}" изменено`
          ]);
        });
    },
    defineFieldType(field) {
      if (field.type.name == "many-to-many-list") {
        return "autocomplete";
      } else if (field.type.name == "list") {
        return "select";
      } else {
        return field.type.name;
      }
    },
    showFieldFromItems(element) {
      let itemValue = null;
      if (
        element.type.name == "many-to-many-list" ||
        element.type.name == "list"
      ) {
        element.items.forEach(item => {
          if (element.pivot.value == item.id) {
            itemValue = item.name;
          }
        });
        return itemValue;
      } else {
        return element.pivot.value;
      }
    },
    defineAuthUserResponsibility(){
      this.authuser.responsibilities.filter(elem => {
        if(elem.name == 'Рук НАП'){
          this.editAllowed = true;
        }else{
          this.editAllowed = false;
        }
      });
    }
  },
  created() {
    // Calculate last process spent time
    this.localProduct.processes[
      this.localProduct.processes.length - 1
    ].pivot.spent_time = this.lastProcessSpentTime;
    // Calculate total spent time
    this.overallSpentTime = this.totalSpentTime;
    this.defineAuthUserResponsibility();    
  }
};
</script>