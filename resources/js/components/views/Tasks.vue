<template>
  <div>
    <v-row align="center" class="px-3 mb-3">
      <v-text-field v-model="searchTask" flat dense solo hide-details label="Название задачи"></v-text-field>
      <v-tooltip bottom>
        <template v-slot:activator="{ on }">
          <v-btn
            depressed
            solo
            color="white"
            height="38"
            class="ml-3"
            @click="filtersMenu = !filtersMenu"
            v-on="on"
          >
            <v-icon color="grey">mdi-tune</v-icon>
          </v-btn>
        </template>
        <span>Фильтры</span>
      </v-tooltip>
      <v-navigation-drawer v-model="filtersMenu" right fixed temporary>
        <v-list-item>
          <v-list-item-content>
            <v-list-item-title class="title">Фильтры</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-divider></v-divider>
        <v-card flat tile>
          <v-card-text>
            <v-select
              v-model="taskCategory"
              :items="filterItems"
              label="Задачи"
              class="mb-4"
              item-text="name"
              item-value="url"
              height="38"
              outlined
              flat
              dense
              hide-details
              single-line
              return-object
            ></v-select>
            <v-select
              v-if="selectEmployee"
              v-model="employee"
              :items="employeeItems"
              label="Выберите сотрудника"
              class="mb-4"
              item-text="name"
              item-value="id"
              height="38"
              outlined
              flat
              dense
              hide-details
              single-line
              return-object
              chips
              no-data-text="Нет сотрудников"
            >
              <template v-slot:selection="data">
                <v-chip v-bind="data.attrs" :input-value="data.selected" @click="data.select">
                  <v-avatar left>
                    <v-img :src="thumb(data.item.img)"></v-img>
                  </v-avatar>
                  {{ data.item.name }} {{ data.item.surname }}
                </v-chip>
              </template>
              <template v-slot:item="data">
                <v-list-item-avatar>
                  <img :src="photo(data.item.img)" />
                </v-list-item-avatar>
                <v-list-item-content>
                  <v-list-item-title>{{ data.item.name }} {{ data.item.surname }}</v-list-item-title>
                </v-list-item-content>
              </template>
            </v-select>
            <v-autocomplete
              v-model="selectedTags"
              :items="tasksTags"
              class="mb-4"
              item-text="name"
              item-value="id"
              dense
              outlined
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
            <v-select
              v-model="priority"
              :items="priorityItems"
              label="Приоритет"
              class="mb-4"
              item-text="name"
              item-value="url"
              height="38"
              outlined
              flat
              dense
              hide-details
              single-line
              return-object
              clearable
              @click:clear="priority=null"
            >
              <template v-slot:selection="data">
                <v-icon class="mr-4" :color="data.item.color">mdi-flag-variant</v-icon>
                {{ data.item.name }}
              </template>
              <template v-slot:item="data">
                <v-icon class="mr-4" :color="data.item.color">mdi-flag-variant</v-icon>
                {{ data.item.name }}
              </template>
            </v-select>
            <v-btn block dark depressed color="primary" @click="filterTask()">Фильтр</v-btn>
          </v-card-text>
        </v-card>
      </v-navigation-drawer>
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
      filteredTasks: [],
      myTasks: null,
      filter: {
        all: true
      },
      taskCategory: null,
      filtersMenu: false,
      selectEmployee: false,
      searchTask: "",
      localPriorityColor: "grey",
      employee: null,
      employeeItems: [],
      priority: null,
      taskCategoryQuery: null,
      page: 1,
      filterItems: [
        {
          name: "Мои задачи",
          query: "responsible_id",
          user: this.authuser.id
        },
        {
          name: "Поставленные задачи",
          query: "from_id",
          user: this.authuser.id
        },
        {
          name: "Наблюдаю задачи",
          query: "watcher_id",
          user: this.authuser.id
        },
        {
          name: "Сотрудник",
          query: "",
          user: ""
        },
        {
          name: "Все задачи",
          query: "all",
          user: true
        }
      ],
      priorityItems: [
        {
          name: "Высокий",
          color: "red lighten-1",
          query: "priority",
          priority: 2
        },
        {
          name: "Средний",
          color: "blue lighten-1",
          query: "priority",
          priority: 1
        },
        {
          name: "Низкий",
          color: "green lighten-1",
          query: "priority",
          priority: 0
        }
      ],
      search: null
    };
  },
  mounted() {
    this.currentView = this.localCurrentView;
  },
  methods: {
    filterTask() {
      if (Object.keys(this.filter).length > 1) {
        axios
          .get(this.appPath("api/tasks/filter"), {
            params: {
              ...this.filter
            }
          })
          .then(res => {
            this.filtersMenu = false;
            this.filteredTasks = res.data;
            this.page = false;
          });
      } else {
        this.filteredTasks = [];
        this.page = 1;
        this.paginate(); 
        this.filtersMenu = false;
      }
    },
    paginate() {
      if (this.page) {
        axios
          .get(this.appPath("api/tasks/paginate"), {
            params: {
              page: this.page,
              all: true
            }
          })
          .then(res => {
            this.filteredTasks.push(...res.data.data);
            if (this.page >= res.data.last_page) {
              this.page = false;
            } else {
              this.page++;
            }
          });
      }
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
    },

    divisionUsers() {
      let users = [];

      axios
        .get(`/api/divisions/${this.auth.id}/users`)
        .then(res => {
          res.data.forEach(item => {
            this.employeeItems.push(item);
          });
        })
        .catch(err => err.messages);

      return users;
    }
  },
  watch: {
    currentView(value) {
      localStorage.currentView = value;
    },
    selectedTags(tags) {
      if (tags.length == 0) {
        delete this.filter["tags"];
      } else {
        this.filter["tags"] = JSON.stringify(tags.map(tag => tag.id));
      }
    },
    // function to find task by title
    searchTask(taskName) {
      // collect filtered tasks in filteredTasks
      this.filteredTasks = this.tasks.filter(task => {
        if (new RegExp(taskName, "gi").test(task.title)) return true;
        if (new RegExp(taskName, "gi").test(task.title)) return true;
      });
    },
    myTasks(id) {
      this.tasks.forEach(task => {
        if (task.from_id == 5) {
          this.filteredTasks.push(task);
        }
      });
    },
    taskCategory(task) {
      if (this.filter[this.taskCategoryQuery]) {
        delete this.filter[this.taskCategoryQuery];
      }
      this.filter[task.query] = task.user;
      this.taskCategoryQuery = task.query;
      if (task.name === "Сотрудник") {
        this.selectEmployee = true;
        delete this.filter["all"];
      } else {
        this.selectEmployee = false;
        this.filter["all"] = true;
      }
    },
    employee(employee) {
      if (this.filter["responsible_id"]) {
        delete this.filter["responsible_id"];
      }
      this.filter["responsible_id"] = employee.id;
    },
    priority(item) {
      if (this.priority == null) {
        delete this.filter["priority"];
      } else {
        this.filter[item.query] = item.priority;
      }
    }
  },
  created() {
    this.divisionUsers;
    this.paginate();
    Event.listen("loadTasks", data => this.paginate());
  }
};
</script>


