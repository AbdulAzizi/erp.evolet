<template>
  <div>
    <v-row>
      <v-col
        cols="6"
        v-for="(responsibility, index ) in localDivision.responsibilities"
        :key="index"
      >
        <v-card>
          <v-toolbar color="primary" dark dense flat>
            <v-toolbar-title>{{responsibility.name}}</v-toolbar-title>
            <v-spacer />
            <edit-add-actions :responsibility="responsibility" />
          </v-toolbar>
          <div>
            <template v-for="(description, subIndex) in responsibility.descriptions">
              <p :key="'list-item-'+index+subIndex" class="ma-4">
                {{ description.text }}
                <span class="float-right mr-3 grey--text">
                  <v-icon small>mdi-timer-sand-full</v-icon>
                  {{ durObj(description.planned_time) }}
                </span>
                <span class="float-right mr-3 grey--text">
                  <v-icon small>mdi-seal</v-icon>
                  {{ description.level }}
                </span>
              </p>
              <v-divider :key="'divider-'+index+subIndex"></v-divider>
            </template>
          </div>
        </v-card>
        <v-card v-if="responsibility.descriptions.length == 0">
          <v-card-text>
            <p class="mt-2">
              Нету инструкций
              <a href="#">добавить?</a>
            </p>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </div>
</template>

<script>
export default {
  props: ["division"],
  data() {
    return {
      localDivision: this.division
    };
  }
};
</script>