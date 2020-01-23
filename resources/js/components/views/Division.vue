<template>
    <div>
        <v-tabs v-model="currentView" background-color="white" class="">
            <span class=" grey--text text--darken-2 align-self-center pl-4">{{localDivision.name}}</span>
            <v-spacer></v-spacer>
            <v-tab :value="activeBtn.STRUCTURE">Структура</v-tab>
            <v-tab :value="activeBtn.POSITION">Объязанности</v-tab>
        </v-tabs>

        <v-tabs-items v-model="currentView" class="transparent">
            <!-- <v-tabs-items v-model="currentView" class="transparent"> -->
            <v-tab-item>
                <division-structure
                    :division="localDivision"
                    :is-user-head="isUserHead"
                    :is-root="true"
                />
            </v-tab-item>
            <v-tab-item>
                <positions :positions="localDivision.positions" />
                <add-positions
                    :division="localDivision"
                    v-if="user.position_level.name == 'Руководитель'"
                />
            </v-tab-item>
        </v-tabs-items>
    </div>
</template>

<script>
export default {
    props: ["division", "isUserHead", "isRoot", "oldInputs", "errors", "user"],
    data() {
        return {
            tab: null,
            currentView: null,
            localDivision: this.division
        };
    },

    mounted() {
        this.currentView = this.localCurrentView;
    },

    computed: {
        activeBtn() {
            return Object.freeze({
                STRUCTURE: 1,
                POSITION: 2
            });
        },
        localCurrentView() {
            if (!localStorage.hasOwnProperty("currentView")) {
                return this.activeBtn.STRUCTURE;
            }
            return parseInt(localStorage.currentView);
        },
        isStructure() {
            return this.currentView === this.activeBtn.STRUCTURE;
        },
        isPosition() {
            return this.currentView === this.activeBtn.POSITION;
        }
    },

    watch: {
        currentView(value) {
            localStorage.currentView = value;
        }
    },

    methods: {
        addUser: function() {
            Event.fire("addUser", [
                {
                    type: "input",
                    name: "divisionId",
                    value: this.division.id
                }
            ]);
        }
    },
    created() {
        Event.listen("positionAdded", data => {
            this.localDivision.positions.push(data);
        });
        Event.listen("descriptionAdded", data => {
            this.localDivision.positions.forEach(element => {
                if (element.id == data.id) {
                    element.descriptions = data.descriptions;
                }
            });
        });
    }
};
</script>

<style>
.v-expansion-panel:before {
    box-shadow: none;
}
</style>