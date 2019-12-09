<template>
    <div>
        <v-fab-transition>
            <v-btn :href="appPath('projects/create')" dark fab fixed bottom right color="primary">
                <v-icon>mdi-plus</v-icon>
            </v-btn>
        </v-fab-transition>

        <v-flex xs12 sm6 class="pb-3">
            <span class="mr-3">Сортировать по</span>
            <v-btn-toggle active-class="primary white--text" rounded v-model="sortBy" mandatory>
                <v-btn text small>Странам</v-btn>
                <v-btn text small>ПК</v-btn>
            </v-btn-toggle>
        </v-flex>
        <projects-list
            v-if="sortBy == 0"
            :projects="groupBy( this.projects , project => project.country.name)"
        ></projects-list>
        <projects-card
            v-if="sortBy == 1"
            :projects="groupBy( this.projects , project => project.pc.name)"
        ></projects-card>
    </div>
</template>

<script>
export default {
    props: ["projects"],
    data() {
        return {
            sortBy: 0,
            canAdd: [""],
        };
    },
    methods: {
        groupBy(list, keyGetter) {
            let map = {};
            list.forEach(item => {
                const key = keyGetter(item);
                const collection = map[key];
                if (!collection) {
                    map[key] = [item];
                } else {
                    map[key].push(item);
                }
            });
            return map;
        },
        addProject() {
            Event.fire("addProject");
        }
    }
};
</script>

<style>
</style>
