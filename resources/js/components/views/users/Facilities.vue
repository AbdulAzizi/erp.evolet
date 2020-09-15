<template>
  <v-row>
    <v-col cols="12" v-if="!localFacilities.length">
      <v-alert prominent color="primary" type="warning">
        <v-row align="center">
          <v-col class="grow">Вы не добавляли оборудования</v-col>
          <v-col class="shrink">
            <v-btn color="secondary" @click="addFacility()">Добавить</v-btn>
          </v-col>
        </v-row>
      </v-alert>
    </v-col>
    <v-col cols="12" v-for="(facility, index) in localFacilities" :key="index">
      <v-card>
        <v-toolbar flat dense dark color="primary">
          <v-toolbar-title>{{ facility.name }}</v-toolbar-title>
        </v-toolbar>
        <v-card-text>
          <v-simple-table dense>
            <template v-slot:default>
              <thead>
                <tr>
                  <th>Название</th>
                  <th>Параметр</th>
                </tr>
              </thead>
              <tbody>
                <tr class="pr-4" v-for="(attribute, index) in facility.attributes" :key="`attribute-${index}`">
                  <td style="width:50%">{{ attribute.name }}</td>
                  <td style="width:50%">{{ attribute.value }}</td>
                </tr>
              </tbody>
            </template>
          </v-simple-table>
        </v-card-text>
      </v-card>
    </v-col>
    <v-dialog v-model="dialog" width="500">
      <v-card>
        <v-toolbar flat dense dark color="primary">
          <v-toolbar-title>Добавить оборудование</v-toolbar-title>
        </v-toolbar>
        <v-card-text>
          <v-form ref="form">
            <v-row>
              <v-col cols="12">
                <v-select
                  v-model="type"
                  :items="equipmentType"
                  hide-details="auto"
                  rounded
                  filled
                  label="Оборудование"
                  :rules="required"
                />
              </v-col>
            </v-row>
            <v-row v-for="(attribute, index) in facilityAttributes" :key="index">
              <v-col cols="6">
                <v-select
                  v-model="facilityAttributes[index]['name']"
                  :items="attributeType"
                  hide-details="auto"
                  rounded
                  filled
                  label="Тип"
                  :rules="required"
                />
              </v-col>
              <v-col cols="6">
                <v-text-field
                  v-model="facilityAttributes[index]['value']"
                  hide-details="auto"
                  rounded
                  filled
                  label="Параметр"
                  :rules="required"
                />
              </v-col>
            </v-row>
            <v-btn icon color="primary">
              <v-icon small @click="addAttribute()">mdi-plus</v-icon>
            </v-btn>
          </v-form>
        </v-card-text>
        <v-card-actions>
          <v-spacer />
          <v-btn text color="primary" @click="dialog = false">Отмена</v-btn>
          <v-btn depressed color="primary" @click="createFacility()">Добавить</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-btn v-if="localFacilities.length > 0" @click="addFacility()" color="primary" fab fixed right bottom>
      <v-icon>mdi-plus</v-icon>
    </v-btn>
  </v-row>
</template>
<script>
export default {
  props: {
    facilities: {
      required: true
    }
  },
  data() {
    return {
      dialog: false,
      equipmentType: ["ПК", "Ноутбук", "Монитор", "Клавиатура", "Мышь"],
      type: null,
      attributeType: [
        "Видеокарта",
        "Оперативная память",
        "Жесткий диск",
        "Диагональ",
        "Модель"
      ],
      facilityAttributes: [{
        name: null,
        value: null
      }],
      localFacilities: this.facilities,
      required: [v => !!v || "Обязательное поле"],
    };
  },
  created() {
    // console.log(this.facilities);
  },
  methods: {
    addFacility() {
      this.dialog = true;
    },
    addAttribute() {
      this.facilityAttributes.push({
        name: null,
        value: null
      });
    },
    createFacility() {
      const FORM = this.$refs.form;
      if(FORM.validate()) {
        axios
          .post("/api/facilities", {
            type: this.type,
            facilityAttributes: this.facilityAttributes
          })
          .then(res => {
            this.localFacilities.push(res.data);
            this.dialog = false;
            Event.fire('notify', ['Оборудование успешно добавлено']);
          });
      }
    }
  }
};
</script>