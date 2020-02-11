<template>
    <v-container fluid>
        <v-row>
            <v-col :md="addUser ? 9 : 12" sm="12">
                <v-text-field
                    hide-details
                    label="Search"
                    prepend-inner-icon="mdi-magnify"
                    solo
                    v-model="search"
                    dense
                ></v-text-field>
            </v-col>
            <v-col md="3" sm="12" v-if="addUser">
                <v-btn
                    height="38"
                    outlined
                    color="primary"
                    block
                    @click="dialog = true"
                >Добавить сотрудника</v-btn>
            </v-col>
        </v-row>
        <v-row justify="start">
            <v-col
                v-for="(user, i) in filteredUsers"
                :key="i"
                cols="12"
                sm="6"
                md="4"
                lg="4"
                xl="2"
                class="pa-1"
            >
                <user-card-horizontal :user="user" :link="userLink"/>
            </v-col>
        </v-row>
        <v-dialog v-model="dialog" width="600" persistent>
            <add-user />
        </v-dialog>
    </v-container>
</template>

<script>
export default {
    props: {
        users: {
            required: true
        },
        addUser: {
            required: false,
            default: false
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
            dialog: false
        };
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
    }
};
</script>
