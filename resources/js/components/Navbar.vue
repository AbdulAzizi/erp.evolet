<template>
  <v-toolbar color="secondary" app flat clipped-right clipped-left fixed dark dense>
    <!-- <v-toolbar-side-icon @blur="toggleDrawer"></v-toolbar-side-icon> -->

    <v-toolbar-title class="mr-3 align-center">
      <a href="/">
        <v-img :src="`${assetPath}img/white-logo.png`" width="150"></v-img>
      </a>
    </v-toolbar-title>

    <v-spacer></v-spacer>

    <v-toolbar-items class="mr-2">
      <v-btn flat>
        <span class="title" v-if="user.employee">{{user.employee.division.abbreviation}}</span>
      </v-btn>
    </v-toolbar-items>

    <v-menu offset-y left>
      <v-avatar slot="activator" color="grey lighten" size="40">
        <img :src="'../img/'+user.img+'.jpg'" alt="avatar">
      </v-avatar>
      <div>
        <v-container grid-list-md text-xs-center class="pa-3 white">
          <v-layout row wrap>
            <v-flex>
              <v-avatar slot="activator" color="grey lighten" size="70">
                <img :src="'../img/'+user.img+'.jpg'" alt="avatar">
              </v-avatar>
            </v-flex>
            <v-flex>
				<v-list dense two-line>
					<h2 class="title font-weight-regular text-left">{{user.name}} {{user.surname}}</h2>
					<h4 class="subtheader font-weight-regular grey--text text-left">Founder of Vuetify.js</h4>
				</v-list>
            </v-flex>
          </v-layout>
        </v-container>

        <v-list dense>
          <v-divider></v-divider>

          <v-list-tile v-for="(item, index) in items" :href="item.link" :key="index">
            <v-list-tile-title>{{ item.title }}</v-list-tile-title>
          </v-list-tile>

          <v-list-tile key="logoutButton" @click="$refs.logoutform.submit()">
            <v-list-tile-content>
              <v-list-tile-title>Выйти</v-list-tile-title>
            </v-list-tile-content>
          </v-list-tile>

          <form ref="logoutform" action="/logout" method="POST" style="display: none;">
            <input type="hidden" name="_token" :value="csrf">
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
			        <v-list-tile
			          v-for="(item, index) in items"
			          :href="item.link"
			          :key="index"
			          @click=""
			        >
			          <v-list-tile-title>{{ item.title }}</v-list-tile-title>
			        </v-list-tile>
			        <v-list-tile
					key="logoutButton"
					@click="$refs.logoutform.submit()"
					>
						<v-list-tile-content>
		               		<v-list-tile-title>Выйти</v-list-tile-title>
			            </v-list-tile-content>
					</v-list-tile>
				</v-list>
				<form ref="logoutform"  action="/logout" method="POST" style="display: none;">
					<input type="hidden" name="_token" :value="csrf">
				</form>
			</div>
    </v-menu>-->

    <!-- <v-btn @blur="toggleRightDrawer" icon>
            <v-icon :style="rightDrawer ? '':'-ms-transform: rotate(180deg); -webkit-transform: rotate(180deg);transform: rotate(180deg);'">chevron_left</v-icon>
    </v-btn>-->
  </v-toolbar>
</template>

<script>
export default {
  data: () => ({
    rightDrawer: false,
    items: [
      // { title: "CV", link: "/cv/create" }
    ],
    csrf: window.Laravel.csrf_token,
    user: window.Laravel.auth,
    assetPath: window.Laravel.asset_path
  }),
  created() {
    // this.items.unshift({title: this.user.name+' '+this.user.surname, link:'/users/'+this.user.id});
    // console.log(this.user);
  },
  methods: {
    toggleDrawer() {
      Event.fire("toggleDrawer");
    },
    toggleRightDrawer() {
      Event.fire("toggleRightDrawer");
      this.rightDrawer = !this.rightDrawer;
    }
  }
};
</script>