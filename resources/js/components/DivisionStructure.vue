<template>
    <v-expansion-panels accordion class="d-inline-flex justify-end division-expansion-panel">
        <v-dialog width="600" v-model="addEmployeeDialog">
            <add-employee :division="division" />
        </v-dialog>
        <v-expansion-panel v-if="isDivision">
            <v-expansion-panel-header class="px-4 py-0" style="min-height:48px !important">
                {{ division.name }} • {{usersCount}} сотрудников
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
            <v-expansion-panel-content class="pr-0 py-2">
                <v-container grid-list-lg fluid pa-0>
                    <v-layout row wrap v-if="division.head">
                        <v-flex xs12 md6 lg4 xl3>
                            <!-- <user-card :user="division.head" /> -->
                            <user-card-horizontal link :user="division.head"></user-card-horizontal>
                        </v-flex>
                    </v-layout>

                    <v-layout row wrap v-if="divisionEmployees.length">
                        <v-flex xs12 md6 lg4 xl3 v-for="user in divisionEmployees" :key="user.id">
                            <user-card-horizontal link :user="user"></user-card-horizontal>
                        </v-flex>
                        <v-flex xs12 md6 lg4 xl3 v-if="isUserHead && departmentDepth">
                            <v-card
                                @click="addEmployeeDialog = !addEmployeeDialog"
                                hover
                                height="100%"
                            >
                                <div class="display-4 text-sm-center align-center">+</div>
                            </v-card>
                        </v-flex>
                    </v-layout>

                    <div>
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
            console.log("clicked");
            
        }
    },

    computed: {
        divisionEmployees: function() {
            const headOfDivisionId = this.localDivision.head_id;
            return this.localDivision.users.filter(
                user => user.id !== headOfDivisionId
            );
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
            Event.fire("notify", [
                `Создан сотрудник ${data.name} ${data.surname}`
            ]);
        });
        Event.listen("cancelEmployeeSubmition", data => {
            this.addEmployeeDialog = false;
        });

        console.log(this.isUserHead);
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
