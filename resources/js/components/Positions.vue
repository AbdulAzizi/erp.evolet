<template>
    <div>
        <v-row class="ma-0" justify="end" align="start">
            <add-position
                v-if="createable && localDivisions.length == 1  "
                :divisionId="divisions[0].id"
            />
            <attach-responsibilities-btn
                :positions="localUser.division.positions"
                :user="localUser"
                v-if="localUser && editable"
            />
        </v-row>
        <v-row v-if="(divisions.length == 1 && divisions[0].positions.length == 0) || (localUser && localUser.positions.length == 0)">
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
        <v-row v-if="localUser && localUser.positions.length">
            <v-col cols="6" v-for="(position, index) in localUser.positions" :key="index">
                <position-card :user="localUser" :position="position" :division="divisions" />
            </v-col>
        </v-row>
        <template v-for="(division, index) in localDivisions">
            <v-row
                v-if="division.positions.length > 0 && localDivisions.length > 1"
                :key="'name' + index"
            >
                <v-col cols="12" class="py-0">
                    <v-card flat>
                        <v-toolbar flat dense>
                            <v-toolbar-title>{{ division.name }}</v-toolbar-title>
                            <v-spacer></v-spacer>
                            <add-position v-if="divisions !== undefined && editable" :divisionId="division.id" />
                        </v-toolbar>
                    </v-card>
                </v-col>
            </v-row>
            <v-row v-if="division.positions.length > 0" :key="'position' + index">
                <v-col cols="6" v-for="(position, index ) in division.positions" :key="index">
                    <position-card :position="position" :editable="editable" />
                </v-col>
            </v-row>
        </template>
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
        console.log(this.localUser);
        
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
    }
};
</script>