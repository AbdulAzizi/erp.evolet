<template>
  <div>
    <v-expansion-panel expand class="elevation-0" :value="[isRoot]">
      <v-expansion-panel-content class="transparent">
        <template v-slot:header>
          <h1 class="title">{{division.name}}</h1>
          <div class="text-xs-right" v-if="!departmentDepth">
            <v-menu offset-y>
              <template v-slot:activator="{ on }">
                <v-btn flat icon v-on="on" class="ma-0 mr-2" @click.native.stop color="rgba(0,0,0,.54)">
                  <v-icon>more_horiz</v-icon>
                </v-btn>
              </template>
              <v-list>
                <v-list-tile @click="">
                  <v-list-tile-title>Добавить</v-list-tile-title>
                </v-list-tile>
                <v-list-tile @click="">
                  <v-list-tile-title>Удалить</v-list-tile-title>
                </v-list-tile>
              </v-list>
            </v-menu>
          </div>
        </template>

        <v-container grid-list-lg fluid px-0 pl-5>
          <v-layout row wrap>
            <v-flex d-flex xs12 md6 lg4 xl3 v-if="division.head">
              <user-card :user="division.head"/>
            </v-flex>
            <v-flex d-flex xs12 md6 lg4 xl3>
              <v-card>
                <v-card-text>
                  <h2 class="subheading">Кол-во сотрудников:</h2>
                  <h2 class="headline">{{ usersCount }}</h2>
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
              v-for="user in divisionWithoutHead"
              :key="user.id"
            >
              <user-card :user="user"/>
            </v-flex>
            <v-flex d-flex xs12 md6 lg4 xl3 v-if="isUserHead && departmentDepth">
              <v-card @click="addUser()" hover>
                <div class="display-4 text-xs-center align-center">+</div>
              </v-card>
            </v-flex>
          </v-layout>

          <div class="mt-2">
            <division
              v-for="subDivision in division.children"
              :key="subDivision.id"
              :division="subDivision"
              :is-user-head="isUserHead"
            />
          </div>
        </v-container>
      </v-expansion-panel-content>
    </v-expansion-panel>
  </div>
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
  methods: {
    addUser: function() {
      Event.fire("addUser", { divisionId: this.division.id });
    }
  },
  data(){
    return {
      some: false,
    }
  },
  computed: {
    dump: function() {},
    divisionWithoutHead: function() {
      if (this.division.head)
        return this.division.users.filter(
          user => user.id !== this.division.head.id
        );
      return this.division.users;
    },
    usersCount: function() {
      return divisionUsersRecursiveCount(this.division);
    },
    departmentDepth: function () {
      return this.division.depth > 2;
    }
  }
};
</script>

<style>
.v-expansion-panel__header {
  background: white;
}
</style>
