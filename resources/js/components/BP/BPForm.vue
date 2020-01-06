<template>
  <div>
    <v-btn color="primary" dark href="/forms" small>Назад</v-btn>
    <v-row>
      <v-col cols="6">
        <v-card>
          <v-card-title class="headline">
            {{form.name}}
            <v-spacer />
            <v-btn icon color="primary" @click="createFieldDialog = !createFieldDialog">
              <v-icon>mdi-plus</v-icon>
            </v-btn>
          </v-card-title>
          <v-simple-table dense v-if="form.fields.length">
            <thead>
              <tr>
                <th></th>
                <th class="text-left">Поля</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(field, index) in localForm.fields" :key="index">
                <td>
                  <v-btn icon small @click="deleteField(field)">
                    <v-icon small>mdi-delete</v-icon>
                  </v-btn>
                </td>
                <td>{{field.label}}</td>
              </tr>
            </tbody>
          </v-simple-table>
        </v-card>
      </v-col>
    </v-row>
    <v-dialog v-model="createFieldDialog" width="600" persistent>
      <v-card>
        <v-toolbar dense flat dark color="primary">
          <v-toolbar-title>Добавить поле</v-toolbar-title>
        </v-toolbar>
        <v-form ref="addField">
          <v-card-text v-for="(field, index) in formFields" :key="index">
            <v-row>
              <v-col cols="12" class="ma-0">
                <v-text-field v-model="field.name" :name="index" label="Поле" outlined rounded />
                <v-text-field
                  v-model="field.label"
                  :name="index"
                  label="Название для отображения"
                  outlined
                  rounded
                />
                <v-text-field
                  v-model="field.abbreviation"
                  :name="index"
                  label="Аббревиатура"
                  outlined
                  rounded
                />
                <v-select
                  v-model="field.required"
                  :name="index"
                  :items="[{id: 1, name: 'Да'}, {id: 0, name: 'Нет'}]"
                  item-value="id"
                  item-text="name"
                  label="Обязательное поле"
                  outlined
                  rounded
                />
                <v-select
                  v-model="field.type"
                  :name="index"
                  :items="items"
                  item-value="id"
                  item-text="name"
                  label="Тип"
                  outlined
                  rounded
                  @change="field.type == 4 ? addList(field.id) : null"
                />
                <v-select
                  v-if="field.type == 4"
                  v-model="field.isMultiple"
                  :key="'multiple' + index"
                  :name="index"
                  :items="[{id: 1, name: 'Да'}, {id: 0, name: 'Нет'}]"
                  item-value="id"
                  item-text="name"
                  label="Мультивыбор"
                  outlined
                  rounded
                />
                <v-col
                  v-for="(item, index) in field.listItems"
                  :key="'list' + index"
                  cols="11"
                  class="float-right pa-0 ma-0"
                >
                  <v-text-field
                    v-if="field.type == 4"
                    v-model="item.name"
                    @keydown.once="addList(field.id)"
                    :key="'list' + index"
                    :name="index"
                    label="Лист"
                    outlined
                    rounded
                  />
                </v-col>
              </v-col>
            </v-row>
          </v-card-text>
          <v-card-actions>
            <v-btn icon small color="primary" @click="addField()">
              <v-icon>mdi-plus</v-icon>
            </v-btn>
          </v-card-actions>
        </v-form>
        <v-card-actions>
          <v-spacer />
          <v-btn text color="primary" @click="cancelFieldCreate()">Отмена</v-btn>
          <v-btn dark color="primary" @click="submit()">Создать</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>
<script>
export default {
  props: ["form"],
  data() {
    return {
      createFieldDialog: false,
      localForm: this.form,
      formFields: [
        {
          name: null,
          type: null,
          required: null,
          listItems: [],
          id: 1,
          required: null,
          label: null,
          abbreviation: null
        }
      ],
      items: this.getFieldType(),
      manyToManyListItems: this.getFields(),
      formId: 1
    };
  },
  methods: {
    addField() {
      this.formId++;
      this.formFields.push({
        name: null,
        type: null,
        id: this.formId,
        listItems: [],
        isMultiple: null
      });
    },
    submit() {
      axios
        .post("/api/field/create", {
          data: this.formFields,
          formId: this.form.id
        })
        .then(res => {
          this.createFieldDialog = false;
          this.localForm.fields.push(...res.data);
          Event.fire("notify", ["Добавлены поля к форме"]);
        })
        .catch(err => err.messages);
    },
    addList(id) {
      this.formFields.forEach(elem => {
        if (elem.id == id) {
          elem.listItems.push({
            name: null
          });
        }
      });
    },
    getFieldType() {
      let items = [];
      axios.get("/api/field/type").then(res => {
        items.push(...res.data);
      });
      return items;
    },
    getFields() {
      let items = [];
      axios.get("/api/fields").then(res => {
        items.push(...res.data);
      });
      return items;
    },
    cancelFieldCreate() {
      const form = this.$refs.addField;
      form.reset();
      this.createFieldDialog = false;
    },
    deleteField(item) {
      axios
        .post(`/api/field/delete/${this.form.id}`, { field: item })
        .then(res => {
          this.form.fields.forEach((elem, index) => {
            if(elem.id == item.id){
              this.form.fields.splice(index, 1);
            }
          })
          Event.fire('notify', [`Поле "${item.label}" удален`]);
         })
        .catch(err => err.messages);
    }
  }
};
</script>