<template>
    <v-container fluid>
        <v-row>
            <v-col cols="12" class="pa-1">
                <v-text-field
                    hide-details
                    label="Search"
                    prepend-inner-icon="mdi-magnify"
                    solo
                    v-model="search"
                    dense
                ></v-text-field>
            </v-col>
        </v-row>
        <v-row justify="start">
            <v-col
                v-for="(user, i) in filteredUsers"
                :key="i"
                cols="12"
                sm="6"
                md="4"
                lg="3"
                xl="3"
                class="pa-1 d-flex align-content-stretch"
            >
                <user-card-horizontal :user="user" :link="userLink"/>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
export default {
    props: {
        users: {
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
            filteredUsers: this.users,
        };
    },

    created() {
        // Event listeners

        Event.listen("userAdded", user => {
            this.dialog = false;
            this.filteredUsers.push(user);
            Event.fire("notify", [
                `Добавлен новый сотрудник ${user.name} ${user.surname}`
            ]);
        });

        Event.listen("cancelUserAdding", dialog => (this.dialog = false));
    },

    watch: {
        search(value) {
            this.filteredUsers = this.users.filter(user => {
                if (new RegExp(this.search, "gi").test(user.name)) return true;
                if (new RegExp(this.search, "gi").test(user.surname))
                    return true;
            });
        }
    },
};
</script>
