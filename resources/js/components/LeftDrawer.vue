<template>
    <v-navigation-drawer
        v-model="drawer"
        fixed
        floating
        clipped
        app
        mobile-breakpoint="960"
        width="240"
        class="elevation-3"
        expand-on-hover
    >
        <v-list dense nav>
            <div v-for="(item, key) in items" :key="'item' + key">
                <v-list-group
                    v-if="item.items"
                    v-model="item.active"
                    :prepend-icon="item.icon"
                    no-action
                >
                    <v-list-item slot="activator">
                        <v-list-item-content>
                            <v-list-item-title>{{
                                item.text
                            }}</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>

                    <v-list-item
                        v-for="(subItem, key) in item.items"
                        :key="'sybItem' + key"
                        :href="subItem.url"
                    >
                        <v-list-item-action>
                            <v-icon>{{ subItem.icon }}</v-icon>
                        </v-list-item-action>

                        <v-list-item-content>
                            <v-list-item-title>{{
                                subItem.text
                            }}</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                </v-list-group>

                <v-list-item :href="item.url">
                    <v-list-item-action v-if="item.icon">
                        <v-badge
                            v-if="
                                item.icon == 'mdi-newspaper' && userRequests > 0
                            "
                            bordered
                            color="primary"
                            overlap
                            :content="userRequests"
                        >
                            <v-icon>{{ item.icon }}</v-icon>
                        </v-badge>
                        <v-icon v-else>{{ item.icon }}</v-icon>
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
                //     headOf: ["Evolet", "НАП", "ОМАР"],
                //     divisions:[]
                // },

                {
                    icon: "mdi-attachment",
                    text: "ЭП",
                    url: "/projects?sortBy=country",
                    positions: [
                        "Программист",
                        "Куратор Промо кампания при Головном офисе (КПГ)",
                        "Научный аналитик",
                        "ПК"
                    ],
                    headOf: ["Evolet", "НАП", "ОМАР"],
                    divisions: []
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
                //     headOf: ["Evolet", "НАП", "ОМАР"],
                //     divisions:[]
                // },

                {
                    icon: "mdi-sort-variant",
                    text: "Фильтры",
                    url: "/admin/products",
                    positions: ["Программист", "РВЗ"],
                    headOf: ["НАП"],
                    divisions: []
                },

                {
                    icon: "mdi-file-document-box-multiple",
                    text: "Файлы",
                    url: "/admin/files",
                    positions: ["Программист", "РВЗ"],
                    headOf: ["НАП"],
                    divisions: []
                },

                {
                    icon: "mdi-factory",
                    text: "Заводы",
                    url: "/factories",
                    positions: ["Программист", "МРБ"],
                    headOf: [],
                    divisions: []
                },
// ###################################################################################
                {
                    icon: "mdi-sitemap",
                    text: "Бизнес процессы",
                    url: "/bp",
                    positions: ["Программист"],
                    headOf: ["ОРПО"],
                    divisions: []
                },
                // {
                //     icon: "mdi-account-tie",
                //     text: "HR",
                //     url: "/human-resources",
                //     positions: ["Программист"],
                //     headOf: ["ОРПО", "ДЧ"],
                //     divisions: []
                // },
                // {
                //     icon: "mdi-account-box-multiple",
                //     text: "Резерв кандидатов",
                //     url: "/head-resumes",
                //     positions: ["Программист"],
                //     headOf: ["*"],
                //     divisions: []
                // },
                {
                    icon: "mdi-account-remove",
                    text: "Уволенный сотрудники",
                    url: "/users/fired",
                    positions: [],
                    headOf: [],
                    divisions: ["ОУПС"]
                },
                {
                    icon: "mdi-script-text",
                    text: "ДО Сотрудников",
                    url: "/hr/positions",
                    positions: ["РВЗ"],
                    headOf: [],
                    divisions: ["ОУПС"]
                },
                {
                    icon: "mdi-chart-timeline",
                    text: "История задач",
                    url: "/timesets",
                    positions: [],
                    headOf: ["*"],
                    divisions: []
                },
                {
                    icon: "mdi-account-search",
                    text: "Задачи сотрудников",
                    url: "/users-tasks",
                    positions: ["РВЗ"],
                    headOf: ["ОУПС"],
                    divisions: []
                },
                {
                    icon: "mdi-newspaper",
                    text: "Заявки сотрудников",
                    url: "/requests/head",
                    positions: ["РВЗ"],
                    headOf: ["*"],
                    divisions: []
                },
                {
                    icon: "mdi-account-group",
                    text: "Сотрудники",
                    url: "/users",
                    positions: [],
                    headOf: [],
                    divisions: ["*"]
                },
                {
                    icon: "mdi-door-open",
                    text: "Турникет",
                    url: "/hr/entries",
                    positions: [],
                    headOf: [],
                    divisions: ["ОУПС"]
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
                //------------------------------------------------------------------------------

                // get union elements of two arrays (link.positions,userResps)
                let unionLinks = link.positions.filter(resp =>
                    userResps.includes(resp)
                );
                // return if there is union element
                if (unionLinks.length) return link;

                //------------------------------------------------------------------------------

                // if link headOf has users division or *
                if (
                    link.headOf.includes(user.division.abbreviation) ||
                    link.headOf.includes("*")
                ) {
                    // if user has the positionLevel of "Руководитель"
                    if (user.position_level.name == "Руководитель") return link;
                }

                //------------------------------------------------------------------------------

                // if link division has users division or *
                if (
                    link.divisions.includes(user.division.abbreviation) ||
                    link.divisions.includes("*")
                ) {
                    return link;
                }

                //------------------------------------------------------------------------------
            });
        }
    },
    created() {
        this.items = [...this.getLinksOf(this.auth_user)];
        Event.listen("toggleDrawer", () => (this.drawer = !this.drawer));
    }
};
</script>
