<template>
  <div>
    <v-row align="center" class="px-3 mb-3">
        <v-select
          v-model="filter"
          :items="filterItems"
          :label="localFilter.name ? localFilter.name : localFilter"
          item-text="name"
          item-value="url"
          height="38"
          solo
          flat
          dense
          hide-details
          single-line
          return-object
          class="pr-2"
        ></v-select>
        <v-menu>
          <template v-slot:activator="{ on }">
            <v-btn
            v-on="on"
            depressed
            color="white"
            class="mr-2"
            height="38"
            >
            <v-icon :color="localPriorityColor">mdi-flag-variant</v-icon>

            </v-btn>
          </template>
          <v-list>
            <v-list-item
            v-for="(priority, index) in priorityItems" :key="index"
            @click="priorityFilter(priority.url, priority.color)">
            <v-list-item-icon>
              <v-icon :color="priority.color">
                {{priority.icon}}
              </v-icon>
            </v-list-item-icon>
                <v-list-item-title>
                  {{priority.name}}
                </v-list-item-title>
            </v-list-item>
          </v-list>
        </v-menu>
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
        flat
      />
      <v-btn-toggle v-model="currentView" active-class="primary" class="ml-3" mandatory>
        <v-tooltip bottom>
          <template v-slot:activator="{ on }">
            <v-btn small text :value="activeBtn.TABLE" dark v-on="on" height="38">
              <v-icon :color="isTable ? 'white' : 'grey lighten-0'">mdi-table-of-contents</v-icon>
            </v-btn>
          </template>
          <span>таблица</span>
        </v-tooltip>

        <!-- <v-tooltip bottom>
                        <template v-slot:activator="{ on }">
                            <v-btn small text :value="activeBtn.CALENDAR" dark v-on="on">
                                <v-icon
                                    :color="isCalendar ? 'white' : 'grey lighten-0'"
                                >mdi-calendar-month</v-icon>
                            </v-btn>
                        </template>
                        <span>календарь</span>
        </v-tooltip>-->

        <v-tooltip bottom>
          <template v-slot:activator="{ on }">
            <v-btn small text :value="activeBtn.KANBAN" dark v-on="on" height="38">
              <v-icon :color="isKanban ? 'white' : 'grey lighten-0'">mdi-view-dashboard</v-icon>
            </v-btn>
          </template>
          <span>Канбан доска</span>
        </v-tooltip>
      </v-btn-toggle>
    </v-row>

    <tasks-table :tasks="filteredTasks" v-show="isTable"></tasks-table>
    <!-- <tasks-calendar :tasks="tasks" v-show="isCalendar"></tasks-calendar> -->
    <kanban-view
      v-show="isKanban"
      :tasks="filteredTasks"
      :taskStatuses="statuses"
      :authuser="authuser"
    />
    <tasks-add :divisions="divisions" :users="users" :errors="errors" />
  </div>
</template>

<script>
export default {
  props: ["tasks", "divisions", "users", "errors", "statuses", "authuser"],
  data() {
    return {
      currentView: null,
      selectedTags: [],
      filteredTasks: this.tasks,
      myTasks: null,
      filter: null,
      localPriorityColor: 'grey',
      filterItems: [
        {
          name: "Все задачи",
          url: `/tasks?all=true`
        },
        {
          name: "Мои задачи",
          url: `/tasks?responsible_id=${this.authuser.id}`
        },
        {
          name: "Поставленные задачи",
          url: `/tasks?from_id=${this.authuser.id}`
        },
        {
          name: "Наблюдаю задачи",
          url: `/tasks?watcher_id=${this.authuser.id}`
        }
      ],
      priorityItems: [
        {
          name: "Высокий",
          icon: "mdi-flag-variant",
          color: "red lighten-1",
          url: `&priority=2`
        },
        {
          name: "Средний",
          icon: "mdi-flag-variant",
          color: "blue lighten-1",
          url: `&priority=1`
        },
        {
          name: "Низкий",
          icon: "mdi-flag-variant",
          color: "green lighten-1",
          url: `&priority=0`
        },
        {
          name: "Все",
          icon: "mdi-flag-variant",
          color: "grey",
          url: ''
        },
      ],
      localFilter: "Все задачи",
      allTasksUrl: '/tasks?all=true'
    };
  },
  mounted() {
    this.currentView = this.localCurrentView;
    if(localStorage.getItem('filter')){
      try {
        this.localFilter = JSON.parse(localStorage.getItem('filter'))
      } catch(e) {
        localStorage.removeItem('filter');
      }
    }
    if(localStorage.priority) {
      this.localPriorityColor = localStorage.priority;
    }
  },
  methods: {
    priorityFilter(url, color){
      let filter = localStorage.filter ? JSON.parse(localStorage.filter).url : this.allTasksUrl;
      window.location.href = filter + url;
      localStorage.priority = color;
      this.localPriorityColor = localStorage.priority;
    }
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
          let foundTag = localTags.filter(localTag => localTag.id == tag.id);
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
    selectedTags(tags) {
      this.filteredTasks = [];

      this.tasks.forEach(task => {
        let unionTags = task.tags.filter(function(tag) {
          let matchedTags = tags.filter(el => el.id == tag.id);
          return matchedTags.length != 0;
        });

        if (unionTags.length) this.filteredTasks.push(task);
      });

      if (this.filteredTasks.length == 0) {
        this.filteredTasks = this.tasks;
      }
    },
    myTasks(id) {
      this.tasks.forEach(task => {
        if (task.from_id == 5) {
          this.filteredTasks.push(task);
        }
      });
    },
    filter(item) {
      const parsed = JSON.stringify(item);
      localStorage.setItem('filter', parsed);
      window.location.href = item.url;
      this.localFilter = localStorage.filter;
      localStorage.priority = 'grey';
    }
  },
  created() {
    if(window.location.search == '?all=true'){
      localStorage.filter = 'Все задачи';
      localStorage.priority = 'grey';
    }
  }
};
</script>


