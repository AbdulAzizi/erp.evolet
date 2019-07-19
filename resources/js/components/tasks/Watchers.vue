<template>
    <v-dialog v-model="watchersDialog" width="500">
        <template v-slot:activator="{ on:dialog }">
            <v-tooltip top>
                <template v-slot:activator="{ on:tooltip }">
                    <v-btn
                        v-on="{ ...tooltip, ...dialog }"
                        text
                        rounded
                        min-width="0"
                        style="min-width:0"
                        class="ma-0 grey--text px-2 text--darken-1"
                    >
                        <v-icon :color="watchers.length ? 'primary' : '' ">mdi-eye-plus</v-icon>

                        <v-avatar
                            size="30"
                            v-for="(watcher, key) in watchersData"
                            :key="'watcher-'+key"
                        >
                            <img :src="photo(watcher.img)">
                        </v-avatar>
                    </v-btn>
                    <input type="hidden" name="watchers" :value="JSON.stringify(watchers)"/>
                </template>
                <span>Наблюдатели</span>
            </v-tooltip>
        </template>
        <v-card>
            <v-card-text>
                <form-field :field="usersField" v-model="watchers"/>
            </v-card-text>
        </v-card>
    </v-dialog>
</template>

<script>
export default {
    props: ["users"],
    data() {
        return {
            watchersDialog: false,
            watchersData: [],
            watchers: [],
            
            usersField: {
                type: "users",
                name: "watchers",
                label: "Наблюдатели",
                users: this.users,
                icon: 'mdi-eye-plus'
            }
        };
    },
    created() {
    },
    watch:{
        watchers(value){
            const selectedUsers = this.users.filter(user => value.includes(user.id));
            this.watchersData = selectedUsers;
        }
    }

};
</script>

<style>
</style>
