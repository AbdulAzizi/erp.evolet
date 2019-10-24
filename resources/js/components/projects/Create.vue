<template>
  <div>
    <v-dialog v-model="dialog" width="400" persistent>
      <v-card>
        <v-toolbar dense flat color="primary" dark>
          <v-toolbar-title>
            <span v-if="addCountry">Добавить страну</span>
            <span v-if="addPc">Добавить промокомпанию</span>
          </v-toolbar-title>
        </v-toolbar>
        <v-card-text>
          <template v-if="addCountry">
            <form-field :field="dialogForm.country.nameField" v-model="dialogForm.country.name"></form-field>
            <form-field
              :field="dialogForm.abbreviationField"
              v-model="dialogForm.country.abbreviation"
            ></form-field>
          </template>
          <template v-if="addPc">
            <form-field :field="dialogForm.pc.nameField" v-model="dialogForm.pc.name"></form-field>
            <form-field :field="dialogForm.abbreviationField" v-model="dialogForm.pc.abbreviation"></form-field>
          </template>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn
              text
              color="primary"
              @click="addCountry = false, addPc = false, dialog = false"
            >Отмена</v-btn>
            <v-btn color="primary" dark @click="onSubmit">Создать</v-btn>
          </v-card-actions>
        </v-card-text>
      </v-card>
    </v-dialog>
    <v-row justify="center">
      <v-col cols="6">
        <dynamic-form
          :fields="fields"
          title="Новый проект"
          activatorEventName="addProject"
          actionUrl="/projects"
          method="post"
          :fieldsPerRows="[2,1,1,1]"
        ></dynamic-form>
      </v-col>
    </v-row>
    <v-speed-dial right bottom direction="top" v-model="fab" fixed>
      <template v-slot:activator>
        <v-btn color="primary" dark fab>
          <v-icon v-if="fab">mdi-close</v-icon>
          <v-icon v-else>mdi-plus</v-icon>
        </v-btn>
      </template>
      <v-tooltip left fixed>
        <template v-slot:activator="{ on }">
          <v-btn
            fab
            dark
            small
            color="secondary"
            @click="dialog = true, addCountry = true"
            v-on="on"
          >
            <v-icon color="primary">mdi-earth</v-icon>
          </v-btn>
        </template>
        <span>Добавить страну</span>
      </v-tooltip>
      <v-tooltip left>
        <template v-slot:activator="{ on }">
          <v-btn fab dark small color="secondary" @click="dialog = true, addPc = true" v-on="on">
            <v-icon color="primary">mdi-factory</v-icon>
          </v-btn>
        </template>
        <span>Добавить промо компанию</span>
      </v-tooltip>
    </v-speed-dial>
  </div>
</template>
<script>
export default {
  props: ["users", "pcs", "countries"],
  data() {
    return {
      fab: false,
      dialog: false,
      addPc: false,
      addCountry: false,
      localCountries: this.countries,
      localPcs: this.pcs,
      dialogForm: {
        abbreviationField: {
          label: "Аббревиатура",
          type: "string",
          name: "abbreviation",
          rules: ["required"],
          icon: "mdi-tag-outline"
        },
        country: {
          abbreviation: null,
          name: null,
          nameField: {
            label: "Название страны",
            type: "string",
            name: "name",
            rules: ["required"],
            icon: "mdi-earth"
          }
        },
        pc: {
          abbreviation: null,
          name: null,
          nameField: {
            label: "Название промокомпании",
            type: "string",
            name: "name",
            rules: ["required"],
            icon: "mdi-factory"
          }
        }
      },
      fields: [
        {
          type: "select",
          name: "country",
          label: "Государство",
          rules: ["required"],
          items: this.countries,
          icon: "mdi-earth"
        },
        {
          type: "select",
          name: "pc",
          label: "Промо компания",
          rules: ["required"],
          items: this.pcs,
          icon: "mdi-shield-home-outline"
        },
        {
          type: "users",
          name: "kurator_pc",
          label: "Куратор Портфеля ПК стран",
          rules: ["required"],
          icon: "mdi-account-tie",
          users: this.users
        },
        {
          type: "users",
          name: "no",
          label: "НО",
          rules: ["required"],
          icon: "mdi-account-tie",
          users: this.users
        },
        {
          type: "users",
          name: "pc_representative",
          label: "ПК",
          rules: ["required"],
          icon: "mdi-account-tie",
          users: this.users
        }
      ]
    };
  },
  methods: {
    onSubmit() {
      let model = this.addPc ? this.localPcs : this.localCountries;
      let url = this.addPc ? "/api/pc" : "/api/country";
      let name = this.addPc
        ? this.dialogForm.pc.name
        : this.dialogForm.country.name;
      let abbreviation = this.addPc
        ? this.dialogForm.pc.abbreviation
        : this.dialogForm.country.abbreviation;
      axios
        .post(url, {
          name: name,
          abbreviation: abbreviation
        })
        .then(res => {
          model.push(res.data);
          this.addPc
            ? Event.fire("notify", ["Промо компания добавлена!"])
            : Event.fire("notify", ["Страна добавлена!"]);
          this.dialog = this.addCountry = this.addPc = false;
          this.dialogForm.name = this.dialogForm.abbreviation = null;
        });
    }
  }
};
</script>
<style>
</style>