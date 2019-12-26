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
                <th class="text-left">Поля</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(field, index) in localForm.fields" :key="index">
                <td>{{field.label}}</td>
              </tr>
            </tbody>
          </v-simple-table>
        </v-card>
      </v-col>
    </v-row>
    <v-dialog v-model="createFieldDialog" width="600">
      <v-card>
        <v-toolbar dense flat dark color="primary">
          <v-toolbar-title>Добавить поле</v-toolbar-title>
        </v-toolbar>
        <v-form>
          <v-card-text v-for="(field, index) in formFields" :key="index">
            <v-row>
              <v-col cols="12" class="ma-0">
                <v-text-field v-model="field.name" :name="index" label="Поле" outlined rounded />
                <v-text-field
                  v-model="field.label"
                  :name="index"
                  label="Аббревиатура"
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
                  @change="(field.type == 4 ? addList(field.id) : field.type == 5 ? addManyToManyList(field.id) : null)"
                />
                <v-select
                  v-if="field.type == 5"
                  v-model="field.foreignListId"
                  :key="'list' + index"
                  :name="index"
                  :items="manyToManyListItems"
                  item-value="id"
                  item-text="label"
                  label="Связанная форма"
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
                <v-col
                  v-for="(item, index) in field.manyToManyItems"
                  :key="'manyToMany' + index"
                  cols="11"
                  class="float-right pa-0 ma-0"
                >
                  <v-text-field
                    v-model="item.listName"
                    :key="'list' + index"
                    :name="index"
                    @keydown.once="addManyToManyList(field.id)"
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
          listItems: [],
          manyToManyItems: [],
          id: 1,
          label: null
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
        manyToManyItems: [],
        foreignListId: null
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
          Event.fire('notify', ['Добавлены поля к форме']);
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
    addManyToManyList(id) {
      this.formFields.forEach(elem => {
        if (elem.id == id) {
          elem.manyToManyItems.push({
            listName: null
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
    }
  }
};
</script>