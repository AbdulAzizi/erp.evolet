<template>
    <v-navigation-drawer
        v-model="drawer"
        fixed
        floating
        clipped
        app
        mobile-break-point="960"
        width="240"
        class="elevation-3"
        expand-on-hover
    >
        <v-list dense nav>
            <div v-for="(item,key) in items" :key="'item'+key">
                <v-list-group
                    v-if="item.items"
                    v-model="item.active"
                    :prepend-icon="item.icon"
                    no-action
                >
                    <v-list-item slot="activator">
                        <v-list-item-content>
                            <v-list-item-title>{{ item.text }}</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>

                    <v-list-item
                        v-for="(subItem,key) in item.items"
                        :key="'sybItem'+key"
                        :href="subItem.url"
                    >
                        <v-list-item-action>
                            <v-icon>{{ subItem.icon }}</v-icon>
                        </v-list-item-action>

                        <v-list-item-content>
                            <v-list-item-title>{{ subItem.text }}</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                </v-list-group>

                <v-list-item :href="item.url">
                    <v-list-item-action v-if="item.icon">
                        <v-icon>{{ item.icon }}</v-icon>
                    </v-list-item-action>

                    <v-list-item-content>
                        <v-list-item-title>{{ item.text }}</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
            </div>
        </v-list>
    </v-navigation-drawer>
</template>
<script>
export default {
    props: {
        // links: {
        //     required: false
        // }
    },
    data: function() {
        return {
            auth_user: window.Laravel.auth,
            drawer: true,
            items: [],
            dynamicLinks: [
                // {
                //     icon: "mdi-attachment",
                //     text: "АСИ",
                //     url: "/#",
                //     positions: [
                //         "Куратор Портфел ПК стран",
                //         "Руководитель ЭН",
                //         "ПК"
                //     ],
                //     divisions: ["Evolet", "НАП", "ОМАР"]
                // },
                {
                    icon: "mdi-attachment",
                    text: "ЭП",
                    url: "/projects?sortBy=country",
                    positions: ["Программист", "Куратор Портфеля ПК стран", "НО", "ПК"],
                    divisions: ["Evolet", "НАП","ОМАР"]
                },
                // {
                //     icon: "mdi-attachment",
                //     text: "ЭН",
                //     url: "/#",
                //     positions: [
                //         "Куратор Портфел ПК стран",
                //         "Руководитель ЭН",
                //         "ПК"
                //     ],
                //     divisions: ["Evolet", "НАП", "ОМАР"]
                // },
                {
                    icon: "mdi-sitemap",
                    text: "Бизнес процессы",
                    url: "/bp",
                    positions: ["Программист"],
                    divisions: ["ОРПО","ОМАР"]
                },
                {
                    icon: "mdi-account-tie",
                    text: "HR",
                    url: "/human-resources",
                    positions: ["Программист"],
                    divisions: ["ОРПО", "ДЧ","ОМАР"]
                },
                {
                    icon: "mdi-account-box-multiple",
                    text: "Резерв кандидатов",
                    url: "/head-resumes",
                    positions: ["Программист"],
                    divisions: ["*","ОМАР"]
                },
                {
                    icon: "mdi-sort-variant",
                    text: "Фильтры",
                    url: "/admin/products",
                    positions: ["Программист","РВЗ"],
                    divisions: ["НАП","ОМАР"]
                },
                {
                    icon: "mdi-file-document-box-multiple",
                    text: "Файлы",
                    url: "/admin/files",
                    positions: ["Программист","РВЗ"],
                    divisions: ["НАП","ОМАР"]
                },
                {
                    icon: "mdi-factory",
                    text: "Заводы",
                    url: "/factories",
                    positions: ["Программист","МРБ"],
                    divisions: ["ОМАР"]
                },
                {
                    icon: "mdi-script-text",
                    text: "ДО",
                    url: "/positions",
                    positions: ["РВЗ","HR"],
                    divisions: []
                }
            ]
        };
    },
    methods: {
        // return links that belong to this User
        getLinksOf(user) {
            // get user positions as array
            let userResps = user.positions.map(resp => resp.name);
            // loop through dynamicLinks
            return this.dynamicLinks.filter(link => {
                // if link division has users division or *
                if (
                    link.divisions.includes(user.division.abbreviation) ||
                    link.divisions.includes("*")
                )
                    if (user.position_level.name == "Руководитель")
                        // if user has the positionLevel of "Руководитель"
                        return link;
                // get union elements of two arrays (link.positions,userResps)
                let unionLinks = link.positions.filter(resp =>
                    userResps.includes(resp)
                );
                // return if there is union element
                if (unionLinks.length) return link;
            });
        }
    },
    created() {
        this.items = [...this.getLinksOf(this.auth_user)];
        Event.listen("toggleDrawer", () => (this.drawer = !this.drawer));
    }
};
</script>
