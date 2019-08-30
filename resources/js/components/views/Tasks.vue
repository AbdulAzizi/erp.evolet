<template>
    <div>
        <v-row>
            <v-col class="pt-0">
                <v-btn-toggle v-model="text" active-class="primary" class="float-right">
                    <v-tooltip bottom>
                        <template v-slot:activator="{ on }">
                            <v-btn small text value="table" @click="isTable = true" dark v-on="on">
                                <v-icon
                                    :color="isTable ? 'white': 'grey lighten-0'"
                                >mdi-table-of-contents</v-icon>
                            </v-btn>
                        </template>
                        <span>таблица</span>
                    </v-tooltip>

                    <v-tooltip bottom>
                        <template v-slot:activator="{ on }">
                            <v-btn
                                small
                                text
                                value="calendar"
                                @click="isTable = false"
                                dark
                                v-on="on"
                            >
                                <v-icon
                                    :color="!isTable ? 'white': 'grey lighten-0'"
                                >mdi-calendar-month</v-icon>
                            </v-btn>
                        </template>
                        <span>календарь</span>
                    </v-tooltip>
                </v-btn-toggle>
            </v-col>
        </v-row>

        <tasks-table :tasks="tasks" :users="users" v-if="isTable"></tasks-table>
        <tasks-calendar :tasks="tasks" v-if="!isTable"></tasks-calendar>

        <tasks-add :users="users" :errors="errors" :tags="tags" />
    </div>
</template>

<script>
export default {
    props: {
      tasks: Array,
      users: Array,
      tags: Array,  
      errors: Array,
    },
    data() {
        return {
            text: "table",
            icon: "justify",
            isTable: true,
            justify: "end"
        };
    },
    methods: {
        selectTab(selectedTab) {
            this.currentTab = selectedTab;
        }
    }
};
</script>

<style>
</style>
