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

      <dynamic-form
        width="500"
        :fieldsPerRows="[1]"
        :fields="newGroupFields"
        title="Новая Группа"
        activatorEventName="newGroupDialog"
        :actionUrl="appPath('chats')"
        method="post"
        :dialog="true"
      ></dynamic-form>

      <v-tabs-items v-model="tab">
        <v-tab-item>
          <v-list dense shaped style="max-height: calc(100vh - 96px); overflow-y: scroll;">
            <v-list-item-group color="primary" v-model="selectedChatObject">
              <template v-for="(chat, index) in chats">
                <v-list-item v-if="chat.title" :key="'chat-'+index" :value="chat">
                  <v-list-item-avatar>
                    <v-img :src="photo(chat.img)"></v-img>
                  </v-list-item-avatar>
                  <v-list-item-content>
                    <v-list-item-title>{{chat.title}}</v-list-item-title>
                  </v-list-item-content>
                </v-list-item>

                <v-list-item
                  v-if="typeof chat.from === 'object'"
                  :key="'chat-'+index"
                  :value="chat.from"
                >
                  <v-list-item-avatar>
                    <v-img :src="photo(chat.from.img)"></v-img>
                  </v-list-item-avatar>
                  <v-list-item-content>
                    <v-list-item-title>{{ chat.from.name }} {{ chat.from.surname }}</v-list-item-title>
                    <v-list-item-subtitle>{{ chat.from.position_level.name }} {{ chat.from.division.abbreviation }}</v-list-item-subtitle>
                  </v-list-item-content>
                </v-list-item>

                <v-list-item
                  v-if="typeof chat.to === 'object'"
                  :key="'chat-'+index"
                  :value="chat.to"
                >
                  <template>
                    <v-list-item-avatar>
                      <v-img :src="photo(chat.to.img)"></v-img>
                    </v-list-item-avatar>
                    <v-list-item-content>
                      <v-list-item-title>{{ chat.to.name }} {{ chat.to.surname }}</v-list-item-title>
                      <v-list-item-subtitle>{{ chat.to.position_level.name }} {{ chat.to.division.abbreviation }}</v-list-item-subtitle>
                    </v-list-item-content>
                  </template>
                </v-list-item>
              </template>
            </v-list-item-group>
          </v-list>
        </v-tab-item>

        <v-tab-item>
          <v-text-field
            v-model="groupSearch"
            class="ma-3"
            label="Поиск групп"
            single-line
            rounded
            filled
            hide-details
          ></v-text-field>
          <v-divider></v-divider>
          <v-list dense shaped style="max-height: calc(100vh - 177px); overflow-y: scroll;">
            <v-list-item @click="newGroupDialog">
              <v-list-item-avatar color="primary">
                <v-icon color="white">mdi-account-multiple-plus</v-icon>
              </v-list-item-avatar>
              <v-list-item-content>
                <v-list-item-title>Новая Группа</v-list-item-title>
              </v-list-item-content>
            </v-list-item>
            <v-list-item-group v-model="selectedGroup" color="primary">
              <v-list-item
                v-for="(chat, index) in filteredGroups"
                :key="'chat-'+index"
                :value="chat"
              >
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

        <v-tab-item>
          <v-text-field
            v-model="contactSearch"
            class="ma-3"
            label="Поиск контактов"
            single-line
            rounded
            filled
            hide-details
          ></v-text-field>
          <v-divider></v-divider>
          <v-list dense shaped style="max-height: calc(100vh - 177px); overflow-y: scroll;">
            <v-list-item-group v-model="selectedUser" color="primary">
              <v-list-item v-for="user in filteredUsers" :key="user.title" :value="user">
                <!-- <v-list-item-icon>
                            <v-icon>{{ user.icon }}</v-icon>
                </v-list-item-icon>-->
                <v-list-item-avatar>
                  <v-img :src="appPath('img/'+user.img)"></v-img>
                </v-list-item-avatar>

                <v-list-item-content>
                  <v-list-item-title>{{ user.name }} {{ user.surname }}</v-list-item-title>
                  <v-list-item-subtitle>{{ user.position_level.name }} {{ user.division.abbreviation }}</v-list-item-subtitle>
                </v-list-item-content>
              </v-list-item>
            </v-list-item-group>
          </v-list>
        </v-tab-item>
      </v-tabs-items>
    </v-navigation-drawer>

    <v-toolbar flat v-if="selectedChat">
      <v-toolbar-title v-text="selectedChat.title ? selectedChat.title : selectedChat.name"></v-toolbar-title>
      <div class="flex-grow-1"></div>

      <!-- <template v-if="$vuetify.breakpoint.smAndUp">
        <v-btn icon>
          <v-icon>mdi-pencil</v-icon>
        </v-btn>
      </template>-->
    </v-toolbar>

    <v-divider></v-divider>

    <messages v-if="selectedChat" :messageable="selectedChat" :type="selectedType" />

    <v-navigation-drawer
      permanent
      app
      clipped
      right
      v-if="selectedChat && ( selectedType != 'App\\Direct' )"
    >
      <template v-slot:prepend>
        <v-list-item style="padding-bottom:2px;">
          <v-list-item-avatar>
            <img :src="photo(selectedChat.admin.img)" />
          </v-list-item-avatar>

          <v-list-item-content>
            <v-list-item-title>
              {{selectedChat.admin.name}}
              {{selectedChat.admin.surname}}
            </v-list-item-title>
            <v-list-item-subtitle>Администратор</v-list-item-subtitle>
          </v-list-item-content>
        </v-list-item>
        <v-divider></v-divider>
      </template>

      <v-list dense>
        <v-list-item
          v-for="( participant, index ) in selectedChat.participants"
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
            <!-- <v-list-item-subtitle>{{participant.position_level.name}} {{participant.division.abbreviation}}</v-list-item-subtitle> -->
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-navigation-drawer>
  </div>
</template>

<script>
export default {
  props: ["users", "groups", "chats"],
  data() {
    return {
      tab: null,

      groupSearch: null,
      filteredUsers: this.users,

      contactSearch: null,
      filteredGroups: this.groups,

      selectedChatObject: null,
      selectedGroup: null,
      selectedUser: null,

      selectedChat: null,
      selectedType: null,

      newGroupFields: [
        {
          type: "string",
          name: "title",
          label: "Название",
          rules: ["required"],
          icon: "mdi-text-short"
        },
        {
          type: "users",
          name: "participants",
          label: "Участники",
          rules: ["required"],
          icon: "mdi-account-group",
          users: this.users,
          multiple: true
        }
      ]
    };
  },
  methods: {
    newGroupDialog() {
      Event.fire("newGroupDialog");
    },
    getMeesages() {
      // make one function to get messages of direct or chat
    }
  },
  created() {
    // console.log(this.chats);
    // if (this.groups.length) this.selectedGroup = this.groups[0].id;
  },
  watch: {
    contactSearch(val) {
      this.filteredUsers = this.users.filter(user => {
        return (
          new RegExp(this.contactSearch, "gi").test(user.name) ||
          new RegExp(this.contactSearch, "gi").test(user.surname)
        );
      });
    },
    groupSearch(val) {
      this.filteredGroups = this.groups.filter(chat => {
        return new RegExp(this.groupSearch, "gi").test(chat.title);
      });
    },
    selectedGroup(group) {
      // Undo selected chat
      this.selectedChat = null;
      this.selectedType = null;
      // Check if anything was selected
      if (group != null) {
        let index = null;
        let chat = null;
        // get chat from groups
        this.groups.map((el, key) => {
          if (el.id == group.id) {
            index = key;
            chat = el;
          }
        });

        // Check if selected chat doesnt have details (messages)
        if (chat.participants == undefined) {
          // Send request to get details of selected chat
          axios
            .get(this.appPath(`api/chats/${group.id}/details`))
            // On Respond
            .then(response => {
              // Append details to local variable groups
              this.groups[index] = response.data;
              // Change selected chat
              this.selectedChat = response.data;
              this.selectedType = "App\\Chat";
            });
          // If selected chat has details
        } else {
          // Change selected chat
          this.selectedChat = chat;
          this.selectedType = "App\\Chat";
        }
      }
    },
    selectedUser(user) {
      this.selectedChat = null;
      this.selectedType = null;
      if (user == null) return;

      axios
        .get(this.appPath(`api/directs/${user.id}`))
        // On response
        .then(response => {
          this.selectedChat = response.data;
          this.selectedType = "App\\Direct";
        });
    },
    selectedChatObject(object) {
      this.selectedChat = null;
      this.selectedType = null;
      if (!object) return;
      if ("admin_id" in object) {
        this.selectedGroup = { ...object };
      } else {
        this.selectedUser = { ...object };
      }
    },
    selectedChat(val) {
      // console.log("selectedChat");
      // console.log(val);
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