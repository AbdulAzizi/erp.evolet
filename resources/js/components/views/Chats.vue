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
                        <v-list-item-group v-model="selectedChatIndex" color="primary">
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
                                <v-list-item-subtitle>{{ user.position.name }} {{ user.division.abbreviation }}</v-list-item-subtitle>
                            </v-list-item-content>
                        </v-list-item>
                    </v-list>
                </v-tab-item>
            </v-tabs-items>
        </v-navigation-drawer>

        <v-toolbar flat v-if="secetedChat">
            <v-toolbar-title>{{secetedChat.title}}</v-toolbar-title>

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

        <comments v-if="secetedChat" :commentable="secetedChat" type="Chats" />

        <v-navigation-drawer permanent app clipped right v-if="secetedChat">
            <template v-slot:prepend>
                <v-list-item style="padding-bottom:2px;">
                    <v-list-item-avatar>
                        <img :src="photo(secetedChat.admin.img)" />
                    </v-list-item-avatar>

                    <v-list-item-content>
                        <v-list-item-title>
                            {{secetedChat.admin.name}}
                            {{secetedChat.admin.surname}}
                        </v-list-item-title>
                        <v-list-item-subtitle>Администратор</v-list-item-subtitle>
                    </v-list-item-content>
                </v-list-item>
            </template>

            <v-divider></v-divider>
            <v-list dense>
                <v-list-item
                    v-for="( participant, index ) in secetedChat.participants"
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
                        <v-list-item-subtitle>{{participant.position.name}} {{participant.division.abbreviation}}</v-list-item-subtitle>
                    </v-list-item-content>
                </v-list-item>
            </v-list>
        </v-navigation-drawer>
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
            filteredUsers: this.users,
            selectedChatIndex: null,
            secetedChat: null
        };
    },
    created() {
        this.selectedChatIndex = 0;
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
        // On selected chat index change
        selectedChatIndex(val) {
            // Undo selected chat
            this.secetedChat = null;
            // Check if anything was selected
            if (val != null) {
                // Check if selected chat doesnt have details (comments)
                if (this.chats[val].comments == undefined) {
                    // Get selected chat
                    let selectedChatId = this.chats[val].id;
                    // Initialize 'this' to new variable to use later
                    let self = this;
                    // Send request to get details of selected chat
                    axios.get( this.appPath(`api/chats/${selectedChatId}/details`) )
                        // On Respond
                        .then(function(response) {
                            // Append details to local variable chats
                            self.chats[val] = response.data;
                            // Change selected chat
                            self.secetedChat = self.chats[val];
                            console.log(response.data);
                        });
                // If selected chat has details
                } else {
                    // Change selected chat
                    this.secetedChat = this.chats[val];
                }
            }
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