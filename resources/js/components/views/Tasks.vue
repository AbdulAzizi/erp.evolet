<template>
  <div>
    <v-row align="center" class="px-3 mb-3">
      <v-col cols="2" class="pl-0">
        <v-select
          v-model="filter"
          :items="filterItems"
          item-text="name"
          item-value="url"
          value="localFilter"
          height="38"
          :label="localFilter"
          solo
          flat
          dense
          hide-details
          single-line
          return-object
        ></v-select>
      </v-col>
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
      localFilter: "Все задачи"
    };
  },
  mounted() {
    this.currentView = this.localCurrentView;
    if (localStorage.filter) {
      this.localFilter = localStorage.filter;
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
      localStorage.filter = item.name;
      window.location.href = item.url;
      this.localFilter = localStorage.filter;
    }
  },
  created() {
    if(window.location.search == ''){
      localStorage.filter = 'Все задачи';
    }
  }
};
</script>


