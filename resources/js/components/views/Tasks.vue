<template>
  <div>
    <v-row align="center" class="px-3 mb-3">
      <v-text-field
        v-model="searchTask"
        @keyup="filterTask()"
        flat
        dense
        solo
        hide-details
        label="Название задачи"
      ></v-text-field>
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
      <v-navigation-drawer v-model="filtersMenu" right fixed temporary width="300">
        <v-list-item>
          <v-list-item-content>
            <v-list-item-title class="title">Фильтры</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-divider></v-divider>
        <v-card flat tile>
          <v-card-text>
            <v-select
              v-if="!selectEmployee"
              v-model="taskCategory"
              :items="filterItems"
              label="Все задачи"
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
              deletable-chips
              no-data-text="Нет сотрудников"
            >
              <template v-slot:selection="data">
                <v-chip
                  @click="data.select"
                  @click:close="employee = null"
                  v-bind="data.attrs"
                  :input-value="data.selected"
                  close
                >
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
            <v-select
              v-model="status"
              :items="taskStatuses"
              label="Статус задачи"
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
            ></v-select>
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
            <v-switch label="Задачи сотрудника" v-model="selectEmployee" />
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
    <kanban-view v-show="isKanban" :taskStatuses="statuses" :authuser="authuser" />
    <tasks-add :divisions="divisions" :users="users" :errors="errors" />
    <v-overlay v-if="loading" light opacity="0">
      <v-progress-circular indeterminate size="64" color="primary"></v-progress-circular>
    </v-overlay>
  </div>
</template>

<script>
export default {
  props: ["divisions", "users", "errors", "statuses", "authuser"],
  data() {
    return {
      selectedTags: [],
      filteredTasks: [],
      employeeItems: [],
      filters: {
        all: true
      },
      currentView: null,
      status: null,
      taskCategory: null,
      employee: null,
      priority: null,
      selectEmployee: false,
      filtersMenu: false,
      searchTask: "",
      page: false,
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
        }
      ],
      taskStatuses: [
        {
          name: "Новый",
          query: "status_id",
          id: 1
        },
        {
          name: "В процессе",
          query: "status_id",
          id: 2
        },
        {
          name: "Приостановлен",
          query: "status_id",
          id: 3
        },
        {
          name: "Закрытый",
          query: "status_id",
          id: 4
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
      loading: false
    };
  },
  mounted() {
    this.currentView = this.localCurrentView;
    this.filters = this.setLocalFilter("filters", this.filters);
    this.priority = this.setLocalFilter("priority", this.priority);
    this.taskCategory = this.setLocalFilter("taskCategory", this.taskCategory);
    this.status = this.setLocalFilter("status", this.status);
    this.selectedTags = this.setLocalFilter("tags", this.selectedTags);
    this.selectEmployee = this.setLocalFilter(
      "selectEmployee",
      this.selectEmployee
    );
    this.employee = this.setLocalFilter("employee", this.employee);
    this.filterTask();
  },
  methods: {
    filterTask() {
      const filtersLen = Object.keys(this.filters).length;
      if (filtersLen > 1 || this.selectEmployee) {
        this.loading = !this.loading;
        axios
          .get(this.appPath("api/tasks/filter"), {
            params: {
              ...this.filters
            }
          })
          .then(res => {
            this.filtersMenu = false;
            this.filteredTasks = res.data;
            this.page = this.loading = false;
            localStorage.setItem("filters", JSON.stringify(this.filters));
          });
      } else {
        this.filteredTasks = [];
        this.page = 1;
        this.paginate();
        this.filtersMenu = false;
        localStorage.setItem("filters", JSON.stringify(this.filters));
      }
      Event.fire("kanbanFilter", this.filters);
    },
    paginate() {
      if (this.page) {
        this.loading = true;
        axios
          .get(this.appPath("api/tasks/paginate"), {
            params: {
              page: this.page,
              all: true
            }
          })
          .then(res => {
            this.filteredTasks.push(...res.data.data);
            this.page >= res.data.last_page ? (this.page = false) : this.page++;
            this.loading = false;
          });
      }
    },
    deleteFilter(param) {
      return delete this.filters[param];
    },
    setFilter(param, value) {
      return (this.filters[param] = value);
    },
    setLocalFilter(filterName, filterItem) {
      if (localStorage.hasOwnProperty(filterName)) {
        const data = localStorage.getItem(filterName);
        return JSON.parse(data);
      }
      return filterItem;
    },
    divisionUsers() {
      let users = [];
      if(this.employeeItems.length == 0){
        axios
          .get(`/api/divisions/users`)
          .then(res => {
            res.data.forEach(item => {
              this.employeeItems.push(item);
            });
          })
          .catch(e => e.message);
        return users;
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
      return +localStorage.currentView;
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
      this.filteredTasks.forEach(task => {
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
      if (value == 3) {
        Event.fire("isKanbanTasks");
      }
    },
    selectedTags(tags) {
      let tagsId = JSON.stringify(tags.map(tag => tag.id));
      if (!tags.length) {
        localStorage.tags = [];
        this.deleteFilter("tags");
      } else {
        this.setFilter("tags", tagsId);
        localStorage.setItem("tags", JSON.stringify(tags));
      }
    },
    searchTask(title) {
      title === "" || this.filters["title"]
        ? this.deleteFilter("title")
        : this.setFilter("title", title);
    },
    taskCategory(newVal, oldVal) {
      if (oldVal) {
        this.deleteFilter(oldVal.query);
        localStorage.taskCategory = null;
      }
      if (newVal) {
        this.setFilter(newVal.query, newVal.user);
        localStorage.setItem("taskCategory", JSON.stringify(newVal));
      }
    },
    employee(newVal) {
      if (newVal) {
        if (this.filters["employee_id"]) {
          this.deleteFilter("employee_id");
        }
        this.page = false;
        this.deleteFilter("all");
        this.setFilter("employee_id", newVal.id);
        localStorage.setItem("employee", JSON.stringify(newVal));
      } else {
        this.deleteFilter("employee_id");
        this.setFilter("all", true);
        localStorage.setItem("employee", JSON.stringify(null));
      }
    },
    priority(item) {
      if (!item) {
        localStorage.priority = null;
        this.deleteFilter("priority");
      } else {
        this.setFilter("priority", item.priority);
        localStorage.setItem("priority", JSON.stringify(item));
      }
    },
    status(item) {
      if (!item) {
        localStorage.status = null;
        this.deleteFilter("status_id");
      } else {
        this.setFilter("status_id", item.id);
        localStorage.setItem("status", JSON.stringify(item));
      }
    },
    selectEmployee(val) {
      if (val && this.taskCategory) {
        this.deleteFilter(this.taskCategory.query);
      }
      if (!val) {
        this.employee = null;
        this.setFilter("all", true);
        this.deleteFilter("employee_id");
        localStorage.setItem("selectEmployee", null);
      } else {
        this.taskCategory = null;
        localStorage.setItem("selectEmployee", JSON.stringify(true));
        this.divisionUsers();
      }
    }
  },
  created() {
    this.paginate();
    Event.listen("loadTasks", data => this.paginate());
  }
};
</script>


