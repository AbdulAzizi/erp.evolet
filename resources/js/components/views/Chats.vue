<template>
    <div class="chats-view">
        <v-navigation-drawer permanent app clipped>
            <template v-slot:prepend>
                <v-tabs grow v-model="tab">
                    <v-tab style="min-width:85px;">
                        <v-icon>mdi-forum</v-icon>
                    </v-tab>
                    <v-tab style="min-width:85px;">
                        <v-icon>mdi-account-group</v-icon>
                    </v-tab>
                    <v-tab style="min-width:85px;">
                        <v-icon>mdi-account-tie</v-icon>
                    </v-tab>
                </v-tabs>
            </template>

            <v-tabs-items v-model="tab">
                <v-tab-item>
                    <v-list dense shaped>
                        <v-list-item-group v-model="selectedChat" color="primary">
                            <v-list-item v-for="(chat, index) in chats" :key="'chat-'+index">
                                <v-list-item-avatar>
                                    <v-img :src="photo(chat.img)"></v-img>
                                </v-list-item-avatar>
                                <v-list-item-content>
                                    <v-list-item-title>{{chat.title}}</v-list-item-title>
                                </v-list-item-content>
                            </v-list-item>
                        </v-list-item-group>
                    </v-list>
                </v-tab-item>
                <v-tab-item>Group</v-tab-item>
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
                    <v-list
                        dense
                        shaped
                        style="max-height: calc(100vh - 177px); overflow-y: scroll;"
                    >
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

        <v-toolbar flat  v-if="selectedChat != null">
            <v-toolbar-title>{{chats[selectedChat].title}}</v-toolbar-title>

            <div class="flex-grow-1"></div>

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

        <!-- <div>{{chats[selectedChat]}}</div> -->

        <comments
        v-if="selectedChat != null"
        :commentable="chats[selectedChat]"
        type="Chats"
        />
        
        <v-navigation-drawer permanent app clipped right v-if="selectedChat != null">
            <template v-slot:prepend>
                <v-list-item style="padding-bottom:2px;">
                    <v-list-item-avatar>
                        <img :src="photo(chats[selectedChat].admin.img)" />
                    </v-list-item-avatar>

                    <v-list-item-content>
                        <v-list-item-title>
                            {{chats[selectedChat].admin.name}}
                            {{chats[selectedChat].admin.surname}}
                        </v-list-item-title>
                        <v-list-item-subtitle>Admin</v-list-item-subtitle>
                    </v-list-item-content>
                </v-list-item>
            </template>

            <v-divider></v-divider>
            <v-list dense>
                <v-list-item
                    v-for="( participant, index ) in chats[selectedChat].participants"
                    :key="'participant-'+index"
                >
                    <v-list-item-avatar>
                        <v-img :src="photo(participant.img)"></v-img>
                    </v-list-item-avatar>

                    <v-list-item-content>
                        <v-list-item-title>
                            {{participant.name}}
                            {{participant.surname}}
                        </v-list-item-title>
                        <v-list-item-subtitle>{{participant.position.name}}</v-list-item-subtitle>
                    </v-list-item-content>
                </v-list-item>
            </v-list>
        </v-navigation-drawer>

        <!-- <comments :commentable="commentable"></comments> -->
    </div>
</template>

<script>
export default {
    props: ["users", "chats"],
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
            filteredUsers: this.users,
            selectedChat: 0
        };
    },
    created() {
        // console.log(this.chats);
    },
    watch: {
        search(val) {
            this.filteredUsers = this.users.filter(user => {
                return (
                    new RegExp(this.search, "gi").test(user.name) ||
                    new RegExp(this.search, "gi").test(user.surname)
                );
            });
        },
        selectedChat(val) {
            // console.log(this.chats[this.selectedChat]);
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