<template>
    <div class="positionsPage">
        <!-- Display Attach and Detavh button -->
        <v-row class="ma-0" justify="end" align="start">
            <attach-responsibilities-btn
                :positions="localUser.division.positions"
                :user="localUser"
                v-if="localUser && editable"
            />
        </v-row>
        <!-- Display 'User doesnt have positions' message -->
        <v-row
            v-if="(divisions.length == 1 && divisions[0].positions.length == 0) || (localUser && localUser.positions.length == 0)"
        >
            <v-col>
                <v-alert
                    dense
                    class="pl-6 mb-0"
                    colored-border
                    color="primary"
                    border="left"
                >Должности отсутствуют</v-alert>
            </v-col>
        </v-row>
        <!-- Display users Positions -->
        <v-row v-if="localUser && localUser.positions.length">
            <v-col cols="6" v-for="(position, index) in localUser.positions" :key="index">
                <position-card
                    :user="localUser"
                    :position="position"
                    :detachable="localUser && editable"
                />
            </v-col>
        </v-row>
        <!-- Display Divisions poistions -->
        <v-expansion-panels>
            <v-expansion-panel
                v-for="(division, index) in divisions"
                :key="'panels-'+index"
            >
                <v-expansion-panel-header>{{ division.name }}</v-expansion-panel-header>
                <v-expansion-panel-content class="grey lighten-2">
                    <v-row class="pa-0 ma-0 pb-3">
                        <v-col cols="12" class="pb-0">
                            <add-position
                                v-if="divisions !== undefined && editable"
                                :divisionId="division.id"
                            />
                        </v-col>
                        <v-col
                            cols="6"
                            v-for="(position, index ) in division.positions"
                            :key="index"
                            class="pb-0"
                        >
                            <position-card :position="position" :editable="editable" />
                        </v-col>
                    </v-row>
                </v-expansion-panel-content>
            </v-expansion-panel>
        </v-expansion-panels>
    </div>
</template>
<script>
export default {
    props: {
        editable: {
            default: false,
            required: false
        },
        user: {
            required: false
        },
        createable: {
            required: false,
            defaut: false
        },
        divisions: {
            default: function() {
                return [];
            },
            required: false,
            type: Array
        }
    },
    data() {
        return {
            localUser: this.user,
            localDivisions: this.divisions
        };
    },
    methods: {
        addPositionToDivision(divisionsId) {
            Event.fire("addPositionToDivision", divisionsId);
        }
    },
    created() {
        Event.listen("positionDetached", positionId => {
            this.localUser.positions.forEach((position, index) => {
                if (position.id == positionId) {
                    this.localUser.positions.splice(index, 1);
                }
            });
        });
        Event.listen("deletePosition", positionId => {
            this.localDivisions.forEach(division => {
                division.positions.forEach((position, index) => {
                    if (position.id == positionId) {
                        division.positions.splice(index, 1);
                    }
                });
            });
        });
        Event.listen("newPositionCreated", data => {
            this.localDivisions.forEach(division => {
                if (division.id == data.divisionId) {
                    division.positions.push(data.position);
                }
            });
        });
        Event.listen("addResponsibility", positionId => {});
        Event.listen("responsibilitiesAddedToUser", user => {
            this.localUser = user;
        });
    },
    computed: {
        // divisionsThatHasPositions() {
        //     return this.localDivisions.filter(div => {
        //         return div.positions && div.positions.length;
        //     });
        // }
    }
};
</script>
<style>
.positionsPage .v-expansion-panel-content__wrap {
    padding: 0px;
}
</style>