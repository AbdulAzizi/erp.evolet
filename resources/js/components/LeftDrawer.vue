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
        style="z-index:10"
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
                //     responsibilities: [
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
                    responsibilities: ["Куратор Портфеля ПК стран", "НО", "ПК"],
                    divisions: ["Evolet", "НАП"]
                },
                // {
                //     icon: "mdi-attachment",
                //     text: "ЭН",
                //     url: "/#",
                //     responsibilities: [
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
                    responsibilities: ["Программист"],
                    divisions: ["ОРПО"]
                },
                {
                    icon: "mdi-account-tie",
                    text: "HR",
                    url: "/human-resources",
                    responsibilities: ["Программист"],
                    divisions: ["ОРПО", "ДЧ"]
                },
                {
                    icon: "mdi-file",
                    text: "Резерв кандидатов",
                    url: "/head-resumes",
                    responsibilities: [],
                    divisions: ["*"]
                },
                {
                    icon: "mdi-sort-variant",
                    text: "Фильтры",
                    url: "/admin/products",
                    responsibilities: ["РВЗ"],
                    divisions: []
                }
            ]
        };
    },
    methods: {
        // return links that belong to this User
        getLinksOf(user) {
            // get user responsibilities as array
            let userResps = user.responsibilities.map(resp => resp.name);
            // loop through dynamicLinks
            return this.dynamicLinks.filter(link => {
                // if link division has users division or *
                if (
                    link.divisions.includes(user.division.abbreviation) ||
                    link.divisions.includes("*")
                )
                    if (user.position.name == "Руководитель")
                        // if user has the position of "Руководитель"
                        return link;
                // get union elements of two arrays (link.responsibilities,userResps)
                let unionLinks = link.responsibilities.filter(resp =>
                    userResps.includes(resp)
                );
                // return if there is union element
                if (unionLinks.length) return link;
            });
        }
    },
    created() {
        this.items = [...this.getLinksOf(this.auth_user)];
    }
};
</script>
