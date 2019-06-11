<template>
  <div>
    <v-expansion-panel expand class="elevation-0">
      <v-expansion-panel-content class="transparent">
        <template v-slot:header>
          <h1 class="title">{{division.name}}</h1>
        </template>

        <v-container grid-list-lg fluid px-0 pl-5>
          <v-layout row wrap>
            <v-flex d-flex xs12 md6 lg4 xl3 v-if="division.head">
              <employee-card :employee="division.head"/>
            </v-flex>
            <v-flex d-flex xs12 md6 lg4 xl3>
              <v-card>
                <v-card-text>
                  <h2 class="subheading">Кол-во сотрудников:</h2>
                  <h2 class="headline">{{ employeeCount }}</h2> 
                </v-card-text>
              </v-card>
            </v-flex>
          </v-layout>

          <v-layout row wrap>
            <v-flex
              d-flex
              xs12
              md6
              lg4
              xl3
              v-for="employee in divisionWithoutHead"
              :key="employee.id"
            >
              <employee-card :employee="employee"/>
            </v-flex>
            <v-flex d-flex xs12 md6 lg4 xl3 v-if="isEmployeeHead && division.depth === 3">
              <v-card @click="addEmployee()" hover>
                <div class="display-4 text-xs-center align-center">+</div>
              </v-card>
            </v-flex>
          </v-layout>

          <div class="mt-2">
            <division
              v-for="subDivision in division.children"
              :key="subDivision.id"
              :division="subDivision"
              :is-employee-head="isEmployeeHead"
            />
          </div>
        </v-container>
      </v-expansion-panel-content>
    </v-expansion-panel>
  </div>
</template>

<script>
const divisionRecursiveCount = function(division) {
  let count = division.employees.length;
  division.children.map(subdivision => {
    count += divisionRecursiveCount(subdivision);
  });
  return count;
};

export default {
  props: ["division", "isEmployeeHead"],
  methods: {
    addEmployee: function() {
      Event.fire("addEmployee", { divisionId: this.division.id });
    }
  },
  computed: {
    dump: function() {},
    divisionWithoutHead: function() {
      if (this.division.head)
        return this.division.employees.filter(
          employee => employee.id !== this.division.head.id
        );
      return this.division.employees;
    },
    employeeCount: function() {
      return divisionRecursiveCount(this.division);
    }
  }
};
</script>

<style>
.v-expansion-panel__header {
  background: white;
}
</style>
