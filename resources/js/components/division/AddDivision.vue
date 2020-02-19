<template>
  <v-card>
    <v-toolbar dense flat dark color="primary">
      <v-toolbar-title>Добавить подразделение - отдел</v-toolbar-title>
    </v-toolbar>
    <v-form ref="form">
      <v-card-text>
        <form-field
          :field="{
            type: 'string',
            name: 'name',
            label: 'Название',
            rules: ['required']
            }"
          v-model="division.name"
        ></form-field>
        <form-field
          :field="{
            type: 'string',
            name: 'abbreviation',
            label: 'Аббревиатура',
            rules: ['required']
            }"
          v-model="division.abbreviation"
        ></form-field>
      </v-card-text>
      <v-card-actions>
          <v-spacer />
          <v-btn text color="primary" @click="cancel()">отмена</v-btn>
          <v-btn color="primary" @click="submit()">создать</v-btn>
      </v-card-actions>
    </v-form>
  </v-card>
</template>
<script>
export default {
  data() {
    return {
      division: {
        name: null,
        abbreviation: null
      },
      localDivision: null
    };
  },
  methods: {
      submit(){
          const form = this.$refs.form;
          if(form.validate()){
              axios.post("/api/divisions/create", {
                  name: this.division.name,
                  abbreviation: this.division.abbreviation,
                  parent_id: this.localDivision.id
              }).then(res => {
                Event.fire('divisionCreated', {divisionId: this.localDivision.id, division: res.data})
              }).catch(err => err.message);
          }
      },
      cancel(){
        const form = this.$refs.form;
        Event.fire("cancelDivision");
        form.reset();
      }
  },
  created(){
      Event.listen('division', division => {
          this.localDivision = division;
      });
  }
};
</script>