<template>
    <div>
        <v-row>
            <v-col class="pt-0">
                <v-autocomplete
                    v-model="selectedTags"
                    :items="tasksTags"
                    item-text="name"
                    item-value="id"
                    dense
                    solo
                    no-data-text="У вас нет задачи с таким тегом"
                    chips
                    hide-details
                    small-chips
                    color="primary"
                    label="Тег"
                    multiple
                    hide-selected
                    deletable-chips
                    return-object
                />
            </v-col>
            <v-col class="pt-0">
                <v-btn-toggle
                    v-model="currentView"
                    active-class="primary"
                    class="float-right"
                    mandatory
                >
                    <v-tooltip bottom>
                        <template v-slot:activator="{ on }">
                            <v-btn small text :value="activeBtn.TABLE" dark v-on="on">
                                <v-icon
                                    :color="isTable ? 'white' : 'grey lighten-0'"
                                >mdi-table-of-contents</v-icon>
                            </v-btn>
                        </template>
                        <span>таблица</span>
                    </v-tooltip>

                    <v-tooltip bottom>
                        <template v-slot:activator="{ on }">
                            <v-btn small text :value="activeBtn.CALENDAR" dark v-on="on">
                                <v-icon
                                    :color="isCalendar ? 'white' : 'grey lighten-0'"
                                >mdi-calendar-month</v-icon>
                            </v-btn>
                        </template>
                        <span>календарь</span>
                    </v-tooltip>

                    <v-tooltip bottom>
                        <template v-slot:activator="{ on }">
                            <v-btn small text :value="activeBtn.KANBAN" dark v-on="on">
                                <v-icon
                                    :color="isKanban ? 'white' : 'grey lighten-0'"
                                >mdi-view-dashboard</v-icon>
                            </v-btn>
                        </template>
                        <span>Канбан доска</span>
                    </v-tooltip>
                </v-btn-toggle>
            </v-col>
        </v-row>

        <tasks-table :tasks="filteredTasks" :users="users" v-show="isTable"></tasks-table>
        <tasks-calendar :tasks="tasks" v-show="isCalendar"></tasks-calendar>
        <kanban-view
            v-show="isKanban"
            :tasks="filteredTasks"
            :users="users"
            :taskStatuses="statuses"
            :authuser="authuser"
        />
        <tasks-add :divisions="divisions" :errors="errors" :tags="tags" />
    </div>
</template>

<script>
export default {
    props: ["tasks", "divisions", "tags", "errors", "statuses", "authuser"],
    data() {
        return {
            currentView: null,
            selectedTags: [],
            filteredTasks: this.tasks
        };
    },
    mounted() {
        this.currentView = this.localCurrentView;
    },
    computed: {
        activeBtn() {
            return Object.freeze({
                TABLE: 1,
                CALENDAR: 2,
                KANBAN: 3
            });
        },
        localCurrentView() {
            if (!localStorage.hasOwnProperty("currentView")) {
                return this.activeBtn.TABLE;
            }
            return parseInt(localStorage.currentView);
        },
        isTable() {
            return this.currentView === this.activeBtn.TABLE;
        },
        isCalendar() {
            return this.currentView === this.activeBtn.CALENDAR;
        },
        isKanban() {
            return this.currentView === this.activeBtn.KANBAN;
        },
        tasksTags() {
            // Make empty array to collect all tags
            let localTags = [];
            // Loop through tasks
            this.tasks.forEach(task => {
                // Loop through tags of task
                task.tags.forEach(tag => {
                    // Get tag that matches from localTags
                    let foundTag = localTags.filter(
                        localTag => localTag.id == tag.id
                    );
                    // Check if didnt find any matches
                    if (foundTag.length == 0) {
                        // Push tag to localTags
                        localTags.push(tag);
                    }
                });
            });
            // return array of tags
            return localTags;
        }
    },
    watch: {
        currentView(value) {
            localStorage.currentView = value;
        },
        selectedTags(tags){

            this.filteredTasks = [];

            this.tasks.forEach( (task) => {
                let unionTags = task.tags.filter( function(tag){
                    let matchedTags = tags.filter( el => el.id == tag.id );
                    return matchedTags.length != 0;
                });
                
                if( unionTags.length )
                    this.filteredTasks.push( task );
            });
            
            if (this.filteredTasks.length == 0) {
                this.filteredTasks = this.tasks
            }
        },
        filteredTasks(val){
            console.log(val);
        }
    },
    created() {
        // console.log(this.tasksTags);
    }
};
</script>


