<template>
  <v-expansion-panels accordion class="d-inline-flex justify-end division-expansion-panel">
    <v-dialog eager width="600" v-model="addEmployeeDialog">
      <add-employee :division="localDivision" />
    </v-dialog>
    <v-dialog eager persistent width="600" v-model="addDivisionDialog">
      <add-division :division="localDivision" />
    </v-dialog>
    <delete-record
      :route="`/api/divisions/${localDivision.id}`"
      :visible="deleteDivision"
      :caution="usersCount > 0"
      :cautionMsg="`Невозможно удалить. ${localDivision.abbreviation} имеет сотрудников!`"
      @close="deleteDivision = false"
    />
    <v-expansion-panel v-if="isDivision">
      <v-expansion-panel-header class="px-4 py-0">
        {{ localDivision.name }} • {{usersCount}} сотрудников
        <div class="text-sm-right" v-if="hrUser">
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
              <v-list-item @click="addDivision()" v-if="isDepartment">
                <v-list-item-title>Добавить</v-list-item-title>
              </v-list-item>
              <v-list-item @click="deleteDivision = !deleteDivision">
                <v-list-item-title>Удалить</v-list-item-title>
              </v-list-item>
            </v-card>
          </v-menu>
        </div>
      </v-expansion-panel-header>
      <v-expansion-panel-content class="pr-0 py-2">
        <v-container grid-list-lg fluid pa-0>
          <v-row v-if="localDivision.head" class="ma-0">
            <v-col md="12" lg="4" xl="3" class="pa-2 pt-0 pr-0">
              <user-card-horizontal link :user="localDivision.head"></user-card-horizontal>
            </v-col>
          </v-row>
          <v-row class="ma-0">
            <v-col
              md="4"
              xl="3"
              class="pa-2 pt-0 pr-0"
              v-for="user in divisionEmployees"
              :key="user.id"
            >
              <user-card-horizontal link :user="user" v-if="divisionEmployees.length"></user-card-horizontal>
            </v-col>
          </v-row>
          <v-row class="ma-0">
            <v-col cols="4" class="pa-0 pl-2" v-if="hrUser && isSubdivision">
              <v-btn
                outlined
                block
                @click="addEmployeeDialog = !addEmployeeDialog"
                color="primary"
              >Добавить сотрудника</v-btn>
            </v-col>
          </v-row>

          <div>
            <v-expansion-panels>
              <division-structure
                v-for="( subDivision ) in localDivision.children"
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
  props: {
    division: {
      required: true
    },
    isUserHead: {
      required: true
    },
    isRoot: {
      required: false
    }
  },
  data() {
    return {
      isDivision: true,
      localDivision: this.division,
      tab: null,
      items: [],
      addEmployeeDialog: false,
      addDivisionDialog: false,
      deleteDivision: false
    };
  },

  methods: {
    addUser: function() {
      Event.fire("addUser", [
        { type: "input", name: "divisionId", value: this.division.id }
      ]);
    },
    addDivision() {
      Event.fire("division", this.localDivision);
      this.addDivisionDialog = true;
    }
  },

  computed: {
    divisionEmployees: function() {
      const headOfDivisionId = this.localDivision.head_id;
      return this.localDivision.users.filter(
        user => user.id !== headOfDivisionId
      );
    },

    hrUser() {
      const position = this.auth.positions.filter(
        position => position.name == "HR"
      );

      return position.length > 0;
    },
    usersCount: function() {
      return divisionUsersRecursiveCount(this.localDivision);
    },
    isDepartment() {
      return this.localDivision.depth < 3;
    },
    isSubdivision(){
      return this.localDivision.depth >= 3;
    }
  },
  created() {
    Event.listen("userAdded", data => {
      this.addEmployeeDialog = false;
      if (this.localDivision.id == data.divisionId) {
        this.localDivision.users.push(data.user);
        Event.fire("notify", [
          `Создан сотрудник ${data.user.name} ${data.user.surname}`
        ]);
      }
    });
    Event.listen("cancelEmployeeSubmition", data => {
      this.addEmployeeDialog = false;
    });

    Event.listen("divisionCreated", data => {
      if (this.localDivision.id == data.divisionId) {
        this.localDivision.children.push(data.division);
        this.addDivisionDialog = false;
        Event.fire("notify", [`Создан отдел ${data.division.name}`]);
      }
    });

    Event.listen("cancelDivision", dialog => (this.addDivisionDialog = false));
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
.division-expansion-panel .v-expansion-panel-header--active {
  background-color: #6897f5 !important;
  color: white;
}
.division-expansion-panel.theme--light.v-expansion-panels .v-expansion-panel {
  background-color: transparent !important;
}
.division-expansion-panel .v-expansion-panel-header {
  background-color: white;
}
.division-expansion-panel .v-expansion-panel-content__wrap {
  padding-right: 0;
  padding-top: 0px;
  padding-bottom: 0px;
  padding-left: 40px;
}
</style>
