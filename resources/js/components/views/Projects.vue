<template>
    <!-- <v-data-table
        :headers="headers"
        :items="projects"
        class="elevation-1"
        hide-default-footer
        @click:row="goTo"
        :items-per-page="100"
    />-->

    <v-container fluid>
        <v-flex xs12 sm6 class="pb-3">
            <span class="mr-3">Сортировать по</span>
            <v-btn-toggle active-class="primary" rounded v-model="toggle_sort_by">
                <v-btn text small href="/projects?sortBy=country">
                    Странам
                </v-btn>
                <v-btn text small href="/projects?sortBy=pc">
                    ПК
                </v-btn>
            </v-btn-toggle>
        </v-flex>
        <v-expansion-panels multiple >
            <v-expansion-panel 
            v-for="(item,key) in projects" 
            :key="key"  
            >
                <v-expansion-panel-header class="transparent" ripple>
                    {{key}}
                </v-expansion-panel-header>
                <v-expansion-panel-content>
                    <template v-for="(subItem,i) in item" >
                        <v-btn :key="i" class="mr-3 primary" :href="`/products?pc_id=${subItem.pc_id}&country_id=${subItem.country_id}&project_id=${subItem.id}`">
                            <span v-if="key == subItem.pc.name">{{subItem.country.name}}</span>
                            <span v-else>{{subItem.pc.name}}</span>
                        </v-btn>
                    </template>
                </v-expansion-panel-content>
            </v-expansion-panel>
        </v-expansion-panels>
    </v-container>
    <!-- <v-container grid-list-xl text-center fluid>
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
    </v-container> -->
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
            ],
            toggle_sort_by:null
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
    },
    created(){
        var url = new URL(window.location.href);
        this.toggle_sort_by = url.searchParams.get("sortBy") == "country" ? 0 : 1;
        
    },
    watch:{
        toggle_sort_by(val){
            console.log(val);
            
        }
    }
};
</script>

<style>
.v-expansion-panel.transparent::before {
    box-shadow: none !important;
}
</style>
