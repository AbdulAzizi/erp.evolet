<template>
  <v-dialog v-model="show" max-width="600">
    <v-form action="users" method="POST">
      <input type="hidden" name="_token" :value="csrf">
      <v-card>
        <v-toolbar flat color="primary" dark>
          <v-toolbar-title class="font-weight-regular">Новый сотрудник</v-toolbar-title>
        </v-toolbar>
        <v-card-text>
          <v-text-field
            v-model="name"
            name="name"
            label="Имя"
            required
            :error-messages="localErrors.name"
          />
          <v-text-field
            v-model="surname"
            name="surname"
            label="Фамилия"
            required
            :error-messages="localErrors.surname"
          />
          <v-text-field
            v-model="email"
            name="email"
            label="Email"
            required
            :error-messages="localErrors.email"
          />
          <v-select
            v-model="positionId"
            :items="positions"
            item-text="name"
            item-value="id"
            label="Должность"
            :error-messages="localErrors.positionId"
          />
          <input type="hidden" name="positionId" :value="positionId">
          <v-select
            v-model="responsibilityId"
            :items="responsibilities"
            item-text="name"
            item-value="id"
            label="Полномочия"
            :error-messages="localErrors.responsibilityId"
          />
          <input type="hidden" name="responsibilityId" :value="responsibilityId">

          <input type="hidden" name="divisionId" :value="divisionId">
        </v-card-text>

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="primary" flat="flat" @click="show = false">Отмена</v-btn>
          <v-btn color="primary" type="submit">Добавить</v-btn>
        </v-card-actions>
      </v-card>
    </v-form>
  </v-dialog>
</template>

<script>
//TODO use same design as in tasks/Add
export default {
  props: ["positions", "responsibilities", "errors", "oldinputs"],
  data() {
    return {
      localErrors: this.errors,
      localOldInputs: this.oldinputs,

      show: Array.isArray(this.errors) ? false : true,
      csrf: window.Laravel.csrf_token,

      name: "",
      surname: "",
      email: "",
      positionId: "",
      responsibilityId: "",
      divisionId: ''
    };
  },
  created() {
    
    const { name, surname, email, positionId, responsibilityId, divisionId } = this.localOldInputs;

    this.name = name;
    this.surname = surname;
    this.email = email;
    this.positionId = parseInt(positionId);
    this.responsibilityId = parseInt(responsibilityId);
    this.divisionId = divisionId;

    Event.listen("addUser", data => {
      this.localErrors = [];
      this.localOldInputs = null;

      this.show = true;
      this.name = "";
      this.surname = "";
      this.email = "";
      this.positionId = null;
      this.responsibilityId = null;

      this.divisionId = data.divisionId;
    });

  }
};
</script>

<style>
</style>
