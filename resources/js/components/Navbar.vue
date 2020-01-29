<template>
    <v-app-bar color="white" app clipped-left clipped-right dense fixed light class="elevation-3">
        <v-app-bar-nav-icon @click="toggleDrawer"></v-app-bar-nav-icon>
        <v-toolbar-title class="mr-3 align-center">
            <a href="/">
                <v-img :src="`${assetPath}img/dark-logo.png`" width="160"></v-img>
            </a>
        </v-toolbar-title>

        <v-spacer></v-spacer>

        <v-toolbar-items class="mr-2">
            <v-btn
                color="grey darken-1"
                text
                :href="appPath('division')"
            >{{user.division.abbreviation}}</v-btn>
        </v-toolbar-items>
        <template v-if="false">
            <v-tooltip bottom>
                <template v-slot:activator="{ on }">
                    <v-btn icon v-on="on" :href="appPath('users')">
                        <v-icon color="grey darken-1">mdi-account-group</v-icon>
                    </v-btn>
                </template>
                <span>Сотрудники</span>
            </v-tooltip>

            <v-tooltip bottom>
                <template v-slot:activator="{ on }">
                    <v-btn icon v-on="on" :href="appPath('tasks')">
                        <v-icon color="grey darken-1">mdi-bulletin-board</v-icon>
                    </v-btn>
                </template>
                <span>Задачи</span>
            </v-tooltip>

            <v-tooltip bottom>
                <template v-slot:activator="{ on }">
                    <v-btn icon v-on="on" :href="appPath('chats')">
                        <v-icon color="grey darken-1">mdi-forum</v-icon>
                    </v-btn>
                </template>
                <span>Сообщения</span>
            </v-tooltip>

            <dropdown-btn
                :items="auth.notifications"
                :user="user"
                tooltip="Уведомления"
                icon="mdi-bell"
                max-width="500"
            ></dropdown-btn>
            <!-- // TODO if there are not notifications display proper text -->
        </template>

        <v-menu offset-y left>
            <template v-slot:activator="{ on }">
                <v-avatar v-on="on" size="40" class="ml-2">
                    <img v-if="user.img" :src="photo(user.img)" alt="avatar" />
                    <img
                        v-else
                        :src="photo('green-solo-logo.svg')"
                        style="border-radius:0;"
                        alt="avatar"
                        class="pa-1 pt-2"
                    />
                </v-avatar>
            </template>
            <div>
                <v-container grid-list-md text-xs-center class="pa-3 white">
                    <v-layout row wrap>
                        <v-flex>
                            <v-avatar slot="activator" color="grey lighten" size="70">
                                <img v-if="user.img" :src="photo(user.img)" alt="avatar" />
                                <img
                                    v-else
                                    :src="'../img/green-solo-logo.svg'"
                                    style="border-radius:0;"
                                    alt="avatar"
                                    size="40"
                                    class="pa-2 pt-3"
                                />
                            </v-avatar>
                        </v-flex>
                        <v-flex>
                            <v-list dense two-line class="pt-0">
                                <a :href="'/users/' + user.id " class="black--text">
                                    <h2
                                        class="title font-weight-regular text-sm-left"
                                        style="line-height: 25px;"
                                    >{{user.name}} {{user.surname}}</h2>
                                </a>
                                <h4
                                    v-for="(position,index) in user.positions"
                                    :key="'position-'+index"
                                    class="subtheader font-weight-regular grey--text text-sm-left"
                                >{{position.name}}</h4>
                            </v-list>
                        </v-flex>
                    </v-layout>
                </v-container>
                <v-list dense class="pa-0">
                    <v-divider></v-divider>

                    <!-- <v-list-item v-for="(item, index) in items" :href="item.link" :key="index">
            <v-list-item-title>{{ item.title }}</v-list-item-title>
                    </v-list-item>-->
                    <template v-if="false">
                        <v-list-item :href="appPath('users/'+auth.id)">
                            <v-list-item-content>
                                <v-list-item-title>Профиль</v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>

                        <v-list-item :href="appPath('resume/index')">
                            <v-list-item-content>
                                <v-list-item-title>Добавленные резюме</v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                    </template>
                    <v-list-item @click="$refs.logoutform.submit()">
                        <v-list-item-content>
                            <v-list-item-title>Выйти</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>

                    <form ref="logoutform" action="/logout" method="POST" style="display: none;">
                        <input type="hidden" name="_token" :value="csrf" />
                    </form>
                </v-list>
            </div>
        </v-menu>

        <!-- {{user.name}} {{user.surname}} -->
        <!-- <v-menu offset-y>
			<v-avatar
	          color="grey lighten"
	          slot="activator"
	        >
	          <img :src="'../img/'+user.image+'.jpg'" alt="avatar">
	        </v-avatar>
	        <div>
		        <v-list light>
			        <v-list-item
			          v-for="(item, index) in items"
			          :href="item.link"
			          :key="index"
			          @click=""
			        >
			          <v-list-item-title>{{ item.title }}</v-list-item-title>
			        </v-list-item>
			        <v-list-item
					key="logoutButton"
					@click="$refs.logoutform.submit()"
					>
						<v-list-item-content>
		               		<v-list-item-title>Выйти</v-list-item-title>
			            </v-list-item-content>
					</v-list-item>
				</v-list>
				<form ref="logoutform"  action="/logout" method="POST" style="display: none;">
					<input type="hidden" name="_token" :value="csrf">
				</form>
			</div>
        </v-menu>-->

        <!-- <v-btn @blur="toggleRightDrawer" icon>
            <v-icon :style="rightDrawer ? '':'-ms-transform: rotate(180deg); -webkit-transform: rotate(180deg);transform: rotate(180deg);'">chevron_left</v-icon>
        </v-btn>-->
    </v-app-bar>
</template>

<script>
export default {
    props: ["user"],
    data() {
        return {
            rightDrawer: false,
            items: [
                // { title: "CV", link: "/cv/create" }
            ],
            assetPath: window.Laravel.asset_path
        };
    },
    created() {
        // this.items.unshift({title: this.name+' '+this.surname, link:'/users/'+this.id});
    },
    methods: {
        toggleRightDrawer() {
            Event.fire("toggleRightDrawer");
            this.rightDrawer = !this.rightDrawer;
        },
        toggleDrawer() {
            Event.fire("toggleDrawer");
        }
    }
};
</script>

<style>
a {
    text-decoration: none;
}
.v-app-bar__items .v-btn {
    border-radius: 0;
}
</style>
