<template>
    <div>
        <v-row no-gutters class="mb-2">
            <v-text-field
                hide-details
                label="Search"
                prepend-inner-icon="mdi-magnify"
                solo
                v-model="search"
                dense
                flat
            ></v-text-field>
        </v-row>
        <v-tabs v-model="tab" class="mb-2">
            <v-tabs-slider></v-tabs-slider>

            <v-tab href="#cards">
                Cards
            </v-tab>

            <v-tab href="#tree">
                Tree
            </v-tab>

            <v-tab href="#list">
                List
            </v-tab>
        </v-tabs>

        <v-tabs-items v-model="tab" class="transparent">
            <v-tab-item value="cards">
                <v-container fluid class="py-0">
                    <v-row justify="start">
                        <v-col
                            v-for="(user, i) in filteredUsers"
                            :key="i"
                            cols="12"
                            sm="6"
                            md="4"
                            lg="4"
                            xl="3"
                            class="pa-1 d-flex align-content-stretch"
                        >
                            <user-card-horizontal :user="user" :link="true" />
                        </v-col>
                    </v-row>
                </v-container>
            </v-tab-item>

            <v-tab-item value="tree">
                <division-structure
                    v-for="(div, index) in filteredDivisions"
                    :key="'div' + index"
                    :division="div"
                    :is-user-head="true"
                />
            </v-tab-item>

            <v-tab-item value="list">
                <v-data-table
                    :headers="[
                        { text: 'Имя', value: 'name' },
                        { text: 'Фамилия', value: 'surname' },
                        { text: 'Майл', value: 'email' },
                        { text: 'Позиция', value: 'position_level.name' }
                    ]"
                    :items="filteredUsers"
                    :items-per-page="-1"
                    class="elevation-1 mt-2"
                    hide-default-footer
                    dense
                ></v-data-table>
            </v-tab-item>
        </v-tabs-items>
    </div>
</template>

<script>
export default {
    props: {
        division: {
            required: true
        },
        userLink: {
            required: false,
            default: false
        }
    },
    data() {
        return {
            search: "",
            localUsers: [],
            filteredUsers: [],
            filteredDivisions: [],
            tab: null
        };
    },
    created() {
        this.pushDevisionUsers(this.division);
        this.filteredUsers = this.localUsers;
        this.filteredDivisions = [this.division];
        console.log(this.localUsers);
    },
    methods: {
        pushDevisionUsers(division) {
            this.localUsers.push(...division.users);
            if (division.children.length) {
                division.children.forEach(div => {
                    this.pushDevisionUsers(div);
                });
            }
        },
        findUserInDivision(division, string) {
            division.users.forEach(user => {
                if (
                    new RegExp(string, "gi").test(user.name) ||
                    new RegExp(string, "gi").test(user.surname)
                ) {
                    this.filteredDivisions.push(division);
                }
            });
            if (division.children.length) {
                division.children.forEach(div => {
                    this.findUserInDivision(div, string);
                });
            }
        }
    },
    watch: {
        search(value) {
            this.filteredUsers = this.localUsers.filter(user => {
                if (new RegExp(value, "gi").test(user.name)) return true;
                if (new RegExp(value, "gi").test(user.surname)) return true;
            });
            this.filteredDivisions = [];
            this.findUserInDivision(this.division, value);
        }
    }
};
</script>
