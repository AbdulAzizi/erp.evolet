<template>
    <!-- <v-data-table
        :headers="headers"
        :items="projects"
        class="elevation-1"
        hide-default-footer
        @click:row="goTo"
        :items-per-page="100"
    />-->

    <!-- <v-container fluid>
        <v-expansion-panels multiple >
            <v-expansion-panel v-for="(country,keyCountryName) in projects" :key="keyCountryName"  class="transparent elavation-0" active-class="primary">
                <v-expansion-panel-header class="transparent" ripple>
                    {{keyCountryName}}
                </v-expansion-panel-header>
                <v-expansion-panel-content>
                    <v-template v-for="(item,i) in country" :key="i">
                        <v-btn class="mr-3 secondary" href="#">{{item.pc.name}}</v-btn>
                    </v-template>
                </v-expansion-panel-content>
            </v-expansion-panel>
        </v-expansion-panels>
    </v-container> -->
    <v-container grid-list-xl text-center fluid>
        <v-layout wrap>
            <v-flex v-for="(countries, pcName) in projects" :key="pcName">
                <v-card>
                    <v-card-title>{{pcName}}</v-card-title>
                    <v-list rounded>
                        <v-list-item-group color="primary">
                            <v-list-item 
                            v-for="(project, i) in countries" 
                            :key="i" 
                            :href="`/products?pc_id=${project.pc_id}&country_id=${project.country_id}&project_id=${project.id}`"
                            >
                                <v-list-item-content>
                                    <v-list-item-title>{{ project.country.name }}</v-list-item-title>
                                </v-list-item-content>
                            </v-list-item>
                        </v-list-item-group>
                    </v-list>
                </v-card>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
export default {
    props: ["projects"],
    data() {
        console.log(this.projects);

        return {
            headers: [
                { text: "Промо Компания", value: "pc.name" },
                { text: "Страна", value: "country.name" }
            ]
        };
    },
    methods: {
        goTo(item) {
            window.location.href =
                "/products?pc_id=" +
                item.pc.id +
                "&country_id=" +
                item.country.id +
                "&project_id=" +
                item.id;
        }
    }
};
</script>

<style>
.v-expansion-panel.transparent::before {
    box-shadow: none !important;
}
</style>
