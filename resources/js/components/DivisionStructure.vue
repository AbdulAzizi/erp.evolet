<template>
  <v-expansion-panels accordion class="d-inline-flex justify-end">
    <v-dialog width="600" v-model="addEmployeeDialog">
      <add-employee :division="division" />
    </v-dialog>
    <v-expansion-panel class="transparent" v-if="isDivision">
      <v-expansion-panel-header class="white">
        <h1 class="title">{{ division.name }}</h1>
        <div class="text-sm-right" v-if="!departmentDepth">
          <v-menu offset-y>
            <template v-slot:activator="{ on }">
              <v-btn
                text
                small
                right
                icon
                v-on="on"
                class="ma-0 mr-2"
                @click.native.stop
                color="rgba(0,0,0,.54)"
              >
                <v-icon>mdi-dots-horizontal</v-icon>
              </v-btn>
            </template>
            <v-card>
              <v-list-item @click="addDivision()">
                <v-list-item-title>Добавить</v-list-item-title>
              </v-list-item>
              <v-list-item @click="addDivision()">
                <v-list-item-title>Удалить</v-list-item-title>
              </v-list-item>
            </v-card>
          </v-menu>
        </div>
      </v-expansion-panel-header>
      <v-expansion-panel-content>
        <v-container grid-list-lg fluid px-0 pl-5>
          <v-layout row wrap>
            <v-flex xs12 md6 lg4 xl3 v-if="division.head">
              <user-card :user="division.head" />
            </v-flex>
            <v-flex xs12 md6 lg4 xl3>
              <v-card height="100%">
                <v-card-text>
                  <h2 class="subheading">Кол-во сотрудников:</h2>
                  <h2 class="headline">{{ usersCount }}</h2>
                </v-card-text>
              </v-card>
            </v-flex>
          </v-layout>

          <v-layout row wrap>
            <v-flex xs12 md6 lg4 xl3 v-for="user in divisionEmployees" :key="user.id">
              <user-card :user="user" />
            </v-flex>
            <v-flex xs12 md6 lg4 xl3 v-if="isUserHead && departmentDepth">
              <v-card @click="addEmployeeDialog = !addEmployeeDialog" hover height="100%">
                <div class="display-4 text-sm-center align-center">+</div>
              </v-card>
            </v-flex>
          </v-layout>

          <div class="mt-2">
            <v-expansion-panels>
              <division-structure
                v-for="( subDivision ) in division.children"
                :key="subDivision.id"
                :division="subDivision"
                :is-user-head="isUserHead"
              />
            </v-expansion-panels>
          </div>
        </v-container>
      </v-expansion-panel-content>
    </v-expansion-panel>
  </v-expansion-panels>
</template>

<script>
const divisionUsersRecursiveCount = function(division) {
  let count = division.users.length;
  division.children.map(subdivision => {
    count += divisionUsersRecursiveCount(subdivision);
  });
  return count;
};

export default {
  props: ["division", "isUserHead", "isRoot"],

  data() {
    return {
      isDivision: true,
      localDivision: this.division,
      tab: null,
      items: [],
      addEmployeeDialog: false
    };
  },

  methods: {
    addUser: function() {
      Event.fire("addUser", [
        { type: "input", name: "divisionId", value: this.division.id }
      ]);
    },
    addDivision: function() {
      Event.fire("addDivision", [
        {
          type: "input",
          name: "parentDivisionId",
          value: this.division.id
        } //<form-field />
      ]);
    }
  },

  computed: {
    divisionEmployees: function() {
     const headOfDivisionId = this.localDivision.head_id;
      return this.localDivision.users.filter(user => user.id !== headOfDivisionId)
    },
    usersCount: function() {
      return divisionUsersRecursiveCount(this.division);
    },
    departmentDepth: function() {
      return this.division.depth > 0;
    }
  },
  created() {    
    Event.listen("userAdded", data => {
      this.addEmployeeDialog = false;
      this.localDivision.users.push(data);
      Event.fire('notify', [`Создан сотрудник ${data.name} ${data.surname}`]);
    });
    Event.listen('cancelEmployeeSubmition', data => {
      this.addEmployeeDialog = false;
    })
  }
};
</script>

<style>
.divisions .v-expansion-panel:before {
  box-shadow: none;
  border-radius: 0;
}

.v-expansion-panel-header {
  border-radius: 0;
}
</style>
