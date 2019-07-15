<template>
    <v-navigation-drawer
        v-model="drawer"
        fixed
        clipped
        floating
        app
        disable-resize-watcher
        :mini-variant="mini"
        mobile-break-point="960"
        width="240"
        class="elevation-3"
    >
        <v-list dense>
            <v-list-tile v-if="mini" @click.stop="mini = !mini">
                <v-list-tile-action>
                    <v-icon>chevron_right</v-icon>
                </v-list-tile-action>
            </v-list-tile>
            <div v-for="(item,key) in items" :key="'item'+key">
                <v-list-group
                    v-if="item.items"
                    v-model="item.active"
                    :prepend-icon="item.icon"
                    no-action
                >
                    <v-list-tile slot="activator">
                        <v-list-tile-content>
                            <v-list-tile-title>{{ item.text }}</v-list-tile-title>
                        </v-list-tile-content>
                    </v-list-tile>

                    <v-list-tile
                        v-for="(subItem,key) in item.items"
                        :key="'sybItem'+key"
                        :href="subItem.url"
                    >
                        <!-- <v-list-tile-action>
								<v-icon>{{ subItem.icon }}</v-icon>
                        </v-list-tile-action>-->

                        <v-list-tile-content>
                            <v-list-tile-title>{{ subItem.text }}</v-list-tile-title>
                        </v-list-tile-content>
                    </v-list-tile>
                </v-list-group>

                <v-list-tile v-else :href="item.url">
                    <v-list-tile-action>
                        <v-icon>{{ item.icon }}</v-icon>
                    </v-list-tile-action>

                    <v-list-tile-content>
                        <v-list-tile-title>{{ item.text }}</v-list-tile-title>
                    </v-list-tile-content>
                </v-list-tile>
            </div>
        </v-list>

        <v-btn bottom left icon fixed @click.stop="mini = !mini" v-if="!mini">
            <v-icon>chevron_left</v-icon>
        </v-btn>
    </v-navigation-drawer>
</template>
<script>
export default {
    props: {
        links: {
            required: false,
            default: () => [
                {
                    icon: "business_center",
                    text: "Линк",
                    items: [
                        { text: "Линк", url: "/products" },
                        { text: "Линк", url: "/products/create" }
                    ]
                },
                { icon: "people", text: "Линк", url: "/divisions" },
                { icon: "home", text: "Линк", url: "/tasks" }
            ]
        }
    },
    data: function() {
        return {
            auth_user: window.Laravel.auth,
            drawer: true,
            mini: true,
            items: this.links,
            responsibilityLinks: {
                "Куратор ПК": [
                    { icon: "style", text: "Проекты", url: "/projects" }
				],
				"НО":[
					{ icon: "people", text: "Продукты", url: "/link" }
				],
				"ПК":[
					{ icon: "home", text: "Продукты", url: "/link2" }
				],
				"Программист":[
					{ icon: "home", text: "Линк Програмиста", url: "/#" }
				]
            }
        };
    },
    methods: {
        getLinks(responsibilities) {
			this.items = [ ];
			
            for (let key = 0; key < responsibilities.length; key++) {

				let sidebarItem = this.responsibilityLinks[ responsibilities[key].name];
				this.items = [ ...this.items, ...sidebarItem ];
			}
			
        }
    },
    created() {
		// console.log(this.responsibilityLinks);
		this.getLinks(this.auth_user.responsibilities);

        Event.listen("toggleDrawer", () => (this.drawer = !this.drawer));
    }
};
</script>