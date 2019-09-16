<template>
    <v-navigation-drawer
        fixed
        floating
        clipped
        app
        right
        class="elevation-3 right-drawer"
        expand-on-hover
    >
        <template v-slot:prepend>
            <v-tabs>
                <v-tab style="min-width:64px;" class="px-0">
                    <v-icon>mdi-heart</v-icon>
                </v-tab>
                <v-tab style="min-width:64px;" class="px-0">
                    <v-icon>mdi-heart</v-icon>
                </v-tab>
                <v-tab style="min-width:64px;" class="px-0">
                    <v-icon>mdi-heart</v-icon>
                </v-tab>
                <v-tab style="min-width:64px;" class="px-0">
                    <v-icon>mdi-heart</v-icon>
                </v-tab>
            </v-tabs>
            <!-- <v-list-item two-line>
                <v-list-item-avatar>
                    <img src="https://randomuser.me/api/portraits/women/81.jpg" />
                </v-list-item-avatar>

                <v-list-item-content>
                    <v-list-item-title>Jane Smith</v-list-item-title>
                    <v-list-item-subtitle>Logged In</v-list-item-subtitle>
                </v-list-item-content>
            </v-list-item> -->
        </template>

        <!-- <v-divider></v-divider> -->

        <v-list dense shaped>
            <v-list-item v-for="user in users" :key="user.title" @click>
                <!-- <v-list-item-icon>
                    <v-icon>{{ user.icon }}</v-icon>
                </v-list-item-icon>-->
                <v-list-item-avatar>
                    <v-img :src="appPath('img/'+user.img)"></v-img>
                </v-list-item-avatar>

                <v-list-item-content>
                    <v-list-item-title>{{ user.name }} {{ user.surname }}</v-list-item-title>
                    <v-list-item-subtitle>{{user.division.name}}</v-list-item-subtitle>
                </v-list-item-content>
            </v-list-item>
        </v-list>
    </v-navigation-drawer>
</template>
<script>
export default {
    props: {
        users: {
            required: true
        }
    },
    data() {
        return {
            items: [
                { title: "Home", icon: "mdi-home-city" },
                { title: "My Account", icon: "mdi-account" },
                { title: "Users", icon: "mdi-account-group-outline" }
            ]
        };
    },
    created() {
        console.log(this.users);
    }
};
</script>
<style>
.right-drawer .v-slide-group__prev,
.right-drawer .v-slide-group__next {
    display: none;
}
.right-drawer .v-list {
    width: calc(100% + 25px);
    height: 100%;
    overflow-y: scroll;
}
.right-drawer .v-navigation-drawer__content{
        overflow-y: hidden;
}
</style>