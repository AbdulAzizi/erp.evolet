<template>
  <div>
    <v-expansion-panel expand class="elevation-0">
      <v-expansion-panel-content class="transparent">

        <template v-slot:header>
          <h1 class="title">{{division.name}}</h1>
        </template>

        <v-container grid-list-lg>

          <v-layout row wrap>
            <v-flex d-flex xs12 md6 lg3 xl2 v-for="employee in division.employees" :key="employee.id">
              <employee-card :employee="employee"/>
            </v-flex>
          </v-layout>

          <div>
            <division
              v-for="subDivision in division.children"
              :key="subDivision.id"
              :division="subDivision"
            />
          </div>

        </v-container>
      </v-expansion-panel-content>
    </v-expansion-panel>
  </div>
</template>

<script>
export default {
  props: ["division"],
};
</script>

<style>
.v-expansion-panel__header {
  background: white;
}
</style>
