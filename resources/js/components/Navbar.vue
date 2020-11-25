<template>
  <v-app-bar color="white" app clipped-left clipped-right dense fixed light class="elevation-3">
    <v-app-bar-nav-icon @click="toggleDrawer"></v-app-bar-nav-icon>
    <v-toolbar-title class="mr-3 align-center">
      <a href="/">
        <v-img :src="`${assetPath}img/dark-logo.png`" width="160"></v-img>
      </a>
    </v-toolbar-title>

    <v-spacer></v-spacer>

    
    <task-control-buttons horizontalButtons="true" :task="currentTask" />
    <!-- <v-toolbar-items style="width:600px;" class="d-flex justify-end">
    </v-toolbar-items>-->

    <v-toolbar-items class="mr-2">
      <v-tooltip bottom>
        <template v-slot:activator="{ on }">
          <v-btn
            v-on="on"
            color="grey darken-1"
            text
            :href="appPath('division')"
          >{{user.division.abbreviation}}</v-btn>
        </template>
        <span>Мои отдел</span>
      </v-tooltip>
    </v-toolbar-items>

     <template>
      <v-tooltip bottom>
        <template v-slot:activator="{ on }">
          <v-btn icon v-on="on" :href="appPath('requests')">
            <v-icon color="grey darken-1">mdi-newspaper</v-icon>
          </v-btn>
        </template>
        <span>Заявки</span>
      </v-tooltip>
     </template>

    <template>
      <!-- <v-tooltip bottom>
        <template v-slot:activator="{ on }">
          <v-btn icon v-on="on" :href="appPath('users')">
            <v-icon color="grey darken-1">mdi-account-group</v-icon>
          </v-btn>
        </template>
        <span>Сотрудники</span>
      </v-tooltip> -->

      <v-tooltip bottom>
        <template v-slot:activator="{ on }">
          <v-btn icon v-on="on" :href="appPath('tasks?all=true')">
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
        :user="user"
        tooltip="Уведомления"
        icon="mdi-bell"
        max-width="500"
      ></dropdown-btn>
      <!-- // TODO if there are not notifications display proper text -->
    </template>

    <v-menu offset-y left>
      <template v-slot:activator="{ on: menu }">
        <v-tooltip bottom>
          <template v-slot:activator="{ on: tooltip }">
            <v-avatar v-on="{ ...menu, ...tooltip }" size="40" class="ml-2">
              <img v-if="user.img" :src="thumb(user.img)" alt="avatar" />
              <img
                v-else
                :src="thumb('green-solo-logo.png')"
                style="border-radius:0;"
                alt="avatar"
                class="pa-1 pt-2"
              />
            </v-avatar>
          </template>
          <span>Профиль</span>
        </v-tooltip>
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
                <a :href="appPath('users/' + user.id + '/positions')" class="black--text">
                  <h2
                    class="title font-weight-regular text-sm-left"
                    style="line-height: 25px;"
                  >{{user.name}} {{user.surname}}</h2>
                </a>
                <h4
                  v-for="(position,index) in user.positions"
                  :key="'position-'+index"
                  class="subtheader font-weight-regular grey--text text-sm-left"
                >{{position.label}}</h4>
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
