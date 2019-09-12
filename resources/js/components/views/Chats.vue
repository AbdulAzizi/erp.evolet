<template>
    <div class="chats-view">
        <v-navigation-drawer permanent app clipped>
            <template v-slot:prepend>
                <v-tabs grow v-model="tab">
                    <v-tab  style="min-width:85px;">
                        <v-icon>mdi-forum</v-icon>
                    </v-tab>
                    <v-tab  style="min-width:85px;">
                        <v-icon>mdi-account-group</v-icon>
                    </v-tab>
                    <v-tab  style="min-width:85px;">
                        <v-icon>mdi-account-tie</v-icon>
                    </v-tab>
                </v-tabs>
            </template>

            <v-tabs-items v-model="tab">
                <v-tab-item>Group</v-tab-item>
                <v-tab-item>Chats</v-tab-item>
                <v-tab-item>
                    <v-text-field
                        v-model="search"
                        class="ma-3"
                        label="Search"
                        single-line
                        rounded
                        filled
                        hide-details
                    ></v-text-field>
                    <v-divider></v-divider>
                    <v-list dense shaped style="    max-height: calc(100vh - 201px); overflow-y: scroll;">
                        <v-list-item v-for="user in filteredUsers" :key="user.title" @click>
                            <!-- <v-list-item-icon>
                            <v-icon>{{ user.icon }}</v-icon>
                            </v-list-item-icon>-->
                            <v-list-item-avatar>
                                <v-img :src="appPath('img/'+user.img)"></v-img>
                            </v-list-item-avatar>

                            <v-list-item-content>
                                <v-list-item-title>{{ user.name }} {{ user.surname }}</v-list-item-title>
                                <v-list-item-subtitle>{{ user.position.name }}</v-list-item-subtitle>
                            </v-list-item-content>
                        </v-list-item>
                    </v-list>
                </v-tab-item>
            </v-tabs-items>
        </v-navigation-drawer>

        <v-toolbar flat dense>
            <v-toolbar-title>Title</v-toolbar-title>

            <div class="flex-grow-1"></div>

            <v-toolbar-items>
                <v-btn text>Link 1</v-btn>
                <v-btn text>Link 2</v-btn>
                <v-btn text>Link 3</v-btn>
            </v-toolbar-items>

            <template v-if="$vuetify.breakpoint.smAndUp">
                <v-btn icon>
                    <v-icon>mdi-export-variant</v-icon>
                </v-btn>
                <v-btn icon>
                    <v-icon>mdi-delete-circle</v-icon>
                </v-btn>
                <v-btn icon>
                    <v-icon>mdi-plus-circle</v-icon>
                </v-btn>
            </template>
        </v-toolbar>
        <v-divider></v-divider>

        <comments :commentable="commentable"></comments>
    </div>
</template>

<script>
export default {
    props: ["commentable", "users"],
    data() {
        return {
            tab: null,
            search: null,
            items: [
                { title: "Dashboard", icon: "mdi-view-dashboard" },
                { title: "Photos", icon: "mdi-image" },
                { title: "About", icon: "mdi-help-box" }
            ],
            right: null,
            filteredUsers: this.users
        };
    },
    created() {
        console.log(this.users);
    },
    watch: {
        search(val) {
            this.filteredUsers = this.users.filter(user => {
                return (
                    new RegExp(this.search, "gi").test(user.name) ||
                    new RegExp(this.search, "gi").test(user.surname)
                );
            });
        }
    }
};
</script>

<style>
.chats-view .v-slide-group__prev,
.chats-view .v-slide-group__next {
    display: none;
}
.chats-view .v-tab {
    margin-left: 0 !important;
}
</style>