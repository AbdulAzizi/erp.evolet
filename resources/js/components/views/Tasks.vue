<template>
  <div style="height: 80vh">
    <v-row align="center" class="px-3 mb-3">
      <v-text-field
        v-model="searchTask"
        @keyup="filterTask()"
        flat
        dense
        solo
        hide-details
        label="Задачи"
        :disabled="true"
      ></v-text-field>
      <v-tooltip bottom>
        <template v-slot:activator="{ on: tooltip }">
          <div>
            <v-badge overlap :value="filtersLen" :content="filtersLen">
              <template v-slot:badge></template>
              <v-btn
                depressed
                solo
                color="white"
                height="38"
                class="ml-3"
                @click="filtersMenu = true"
                v-on="tooltip"
              >
                <v-icon color="grey">mdi-tune</v-icon>
              </v-btn>
            </v-badge>
          </div>
        </template>
        <span>Фильтры</span>
      </v-tooltip>
      <v-navigation-drawer v-model="filtersMenu" right fixed temporary width="300">
        <v-list-item>
          <v-list-item-content>
            <v-list-item-title class="title">Фильтры</v-list-item-title>
          </v-list-item-content>
          <v-btn icon small @click="filtersMenu = false">
            <v-icon>mdi-close</v-icon>
          </v-btn>
        </v-list-item>
        <v-divider></v-divider>
        <v-card flat tile>
          <v-card-text>
            <v-form ref="filterForms">
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
              <v-autocomplete
                v-if="selectEmployee"
                v-model="employees"
                :items="employeeItems"
                label="Выберите сотрудника"
                class="mb-4"
                item-text="name"
                item-value="id"
                outlined
                flat
                dense
                hide-details
                single-line
                return-object
                chips
                multiple
                no-data-text="Нет сотрудников"
              >
                <template v-slot:selection="data">
                  <v-chip
                    class="my-1"
                    @click="data.select"
                    @click:close="removeItem(data.item, employees)"
                    v-bind="data.attrs"
                    :input-value="data.selected"
                    color="primary"
                    close
                  >
                    <v-avatar left>
                      <v-img :src="thumb(data.item.img)"></v-img>
                    </v-avatar>
                    {{ data.item.fullname.length >= 20 ? data.item.fullname.substring(0, 15) + '...' : data.item.fullname }}
                  </v-chip>
                </template>
                <template v-slot:item="data">
                  <v-list-item-avatar>
                    <img :src="photo(data.item.img)" />
                  </v-list-item-avatar>
                  <v-list-item-content>
                    <v-list-item-title>{{ data.item.fullname }}</v-list-item-title>
                  </v-list-item-content>
                </template>
              </v-autocomplete>
              <v-select
                v-model="selectedStatuses"
                :items="taskStatuses"
                label="Статус задачи"
                class="mb-4"
                item-text="name"
                item-value="id"
                outlined
                flat
                dense
                hide-details
                single-line
                return-object
                clearable
                multiple
              ></v-select>
              <v-autocomplete
                v-model="selectedTags"
                :items="localTags"
                class="mb-4"
                item-text="name"
                item-value="id"
                dense
                outlined
                no-data-text="У вас нет тегов"
                hide-details
                color="primary"
                label="Тег"
                multiple
                chips
                hide-selected
                return-object
                flat
              >
                <template v-slot:selection="data">
                  <v-chip
                    v-bind="data.attrs"
                    :input-value="data.selected"
                    close
                    small
                    color="primary"
                    @click:close="removeItem(data.item, selectedTags)"
                  >{{data.item.name}}</v-chip>
                </template>
              </v-autocomplete>
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
              <v-select
                v-model="author"
                :items="localUsers"
                label="От"
                class="mb-4"
                item-text="fullname"
                item-value="id"
                height="38"
                outlined
                flat
                dense
                hide-details
                single-line
                return-object
              >
                <template v-slot:selection="data">
                  <v-chip
                    @click="data.select"
                    @click:close="author = null"
                    v-bind="data.attrs"
                    :input-value="data.selected"
                    close
                    color="primary"
                  >
                    <v-avatar left>
                      <v-img :src="thumb(data.item.img)"></v-img>
                    </v-avatar>
                    {{ data.item.fullname.length >= 20 ? data.item.fullname.substring(0, 15) + '...' : data.item.fullname }}
                  </v-chip>
                </template>
                <template v-slot:item="data">
                  <v-list-item-avatar>
                    <img :src="photo(data.item.img)" />
                  </v-list-item-avatar>
                  <v-list-item-content>
                    <v-list-item-title>{{ data.item.fullname }}</v-list-item-title>
                  </v-list-item-content>
                </template>
              </v-select>
              <v-select
                v-if="!selectEmployee"
                v-model="responsible"
                :items="localUsers"
                label="Исполнитель"
                class="mb-4"
                item-text="fullname"
                item-value="id"
                height="38"
                outlined
                flat
                dense
                hide-details
                single-line
                return-object
              >
                <template v-slot:selection="data">
                  <v-chip
                    @click="data.select"
                    @click:close="responsible = null"
                    v-bind="data.attrs"
                    :input-value="data.selected"
                    close
                    color="primary"
                  >
                    <v-avatar left>
                      <v-img :src="thumb(data.item.img)"></v-img>
                    </v-avatar>
                    {{ data.item.fullname.length >= 20 ? data.item.fullname.substring(0, 15) + '...' : data.item.fullname }}
                  </v-chip>
                </template>
                <template v-slot:item="data">
                  <v-list-item-avatar>
                    <img :src="photo(data.item.img)" />
                  </v-list-item-avatar>
                  <v-list-item-content>
                    <v-list-item-title>{{ data.item.fullname }}</v-list-item-title>
                  </v-list-item-content>
                </template>
              </v-select>
              <v-select
                v-model="groupTask"
                :items="groupTaskItems"
                label="Группа задач по"
                class="mb-4"
                item-text="name"
                item-value="value"
                height="38"
                outlined
                flat
                dense
                hide-details
                single-line
                return-object
                clearable
              ></v-select>
              <v-switch
                v-if="auth.id == auth.division.head_id"
                label="Задачи сотрудника"
                hide-details
                v-model="selectEmployee"
              />
            </v-form>
            <!-- <v-btn
              block
              dark
              depressed
              color="primary"
              class="my-5"
              
            >Сборосить фильтры</v-btn>-->
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
          <span>Таблица</span>
        </v-tooltip>

        <v-tooltip bottom>
          <template v-slot:activator="{ on }">
            <v-btn small text :value="activeBtn.CALENDAR" dark v-on="on" height="38">
              <v-icon :color="isCalendar ? 'white' : 'grey lighten-0'">mdi-calendar-month</v-icon>
            </v-btn>
          </template>
          <span>Календарь</span>
        </v-tooltip>

        <!-- <v-tooltip bottom>
          <template v-slot:activator="{ on }">
            <v-btn small text :value="activeBtn.KANBAN" dark v-on="on" height="38">
              <v-icon :color="isKanban ? 'white' : 'grey lighten-0'">mdi-view-dashboard</v-icon>
            </v-btn>
          </template>
          <span>Канбан доска</span>
        </v-tooltip>-->
      </v-btn-toggle>
    </v-row>

    <tasks-table :tasks="filteredTasks" v-if="isTable && !displayGroupTasks"></tasks-table>
    <tasks-calendar v-if="isCalendar"></tasks-calendar>
    <!-- <kanban-view v-show="isKanban" :taskStatuses="statuses" :authuser="authuser" /> -->
    <tasks-group-view :tasks="filteredTasks" v-if="displayGroupTasks" />
    <tasks-add :errors="errors" />
    <v-card flat class="pa-3" v-if="loading">
      <v-progress-linear color="primary" indeterminate rounded height="6"></v-progress-linear>
    </v-card>
  </div>
</template>

<script>
export default {
  props: ["errors", "authuser"],
  data() {
    return {
      selectedTags: [],
      filteredTasks: [],
      employeeItems: [],
      authorItems: [],
      responsibleItems: [],
      localTags: [],
      taskStatuses: [],
      selectedStatuses: [],
      employees: [],
      localUsers: [],
      author: null,
      groupTask: null,
      responsible: null,
      filters: {
        all: true,
      },
      currentView: null,
      taskCategory: null,
      priority: null,
      selectEmployee: false,
      filtersMenu: false,
      searchTask: "",
      page: false,
      filtersLen: null,
      displayGroupTasks: false,
      filterItems: [
        {
          name: "Мои задачи",
          query: "responsible_id",
          user: this.authuser.id,
        },
        {
          name: "Поставленные задачи",
          query: "from_id",
          user: this.authuser.id,
        },
        {
          name: "Наблюдаю задачи",
          query: "watcher_id",
          user: this.authuser.id,
        },
      ],
      priorityItems: [
        {
          name: "Высокий",
          color: "red lighten-1",
          query: "priority",
          id: 2,
        },
        {
          name: "Средний",
          color: "blue lighten-1",
          query: "priority",
          id: 1,
        },
        {
          name: "Низкий",
          color: "green lighten-1",
          query: "priority",
          id: 0,
        },
      ],
      groupTaskItems: [
        {
          name: "Тип задачи",
          value: "responsibility_description_id",
        },
        {
          name: "Описание задачи",
          value: "description",
        },
        {
          name: "Автор задачи",
          value: "from_id",
        },
      ],
      loading: false,
    };
  },
  beforeMount() {
    this.filters = this.setLocalFilter("filters", this.filters);
    this.currentView = this.localCurrentView;
    this.priority = this.setLocalFilter("priority", this.priority);
    this.taskCategory = this.setLocalFilter("taskCategory", this.taskCategory);
    this.selectedStatuses = this.setLocalFilter(
      "status",
      this.selectedStatuses
    );
    this.selectedTags = this.setLocalFilter("tags", this.selectedTags);
    this.selectEmployee = this.setLocalFilter(
      "selectEmployee",
      this.selectEmployee
    );
    this.employees = this.setLocalFilter("employee", this.employees);
    this.author = this.setLocalFilter("author", this.author);
    this.responsible = this.setLocalFilter("responsible", this.responsible);
    this.groupTask = this.setLocalFilter("groupTask", this.groupTask);

    if (this.filters.all && Object.keys(this.filters).length == 1) {
      this.filter();
    }
  },
  methods: {
    filterTask() {
      this.loading = true;
      axios
        .get(this.appPath("api/tasks/filter"), {
          params: {
            ...this.filters,
          },
        })
        .then((res) => {
          this.filteredTasks = res.data;
          this.page = this.loading = false;
          this.countFilters();
          localStorage.setItem("filters", JSON.stringify(this.filters));
          this.displayGroupTasks = false;
        });
      Event.fire("kanbanFilter", this.filters);
    },
    paginate() {
      if (this.page) {
        this.loading = true;
        axios
          .get(this.appPath("api/tasks/paginate"), {
            params: {
              page: this.page,
              all: true,
            },
          })
          .then((res) => {
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
      if (this.employeeItems.length == 0) {
        axios
          .get(`/api/divisions/users`)
          .then((res) => {
            res.data.forEach((item) => {
              this.employeeItems.push(item);
            });
          })
          .catch((e) => e.message);
        return users;
      }
    },
    countFilters() {
      let filtersLen = Object.keys(this.filters).length;
      if (this.groupTask && this.filters.all) {
        this.filtersLen = filtersLen;
      } else if (this.filters.all) {
        this.filtersLen = filtersLen - 1;
      } else {
        this.filtersLen = filtersLen;
      }
    },
    loadGroupTasks() {
      this.loading = true;
      this.countFilters();
      axios
        .get(this.appPath(`api/tasks/groupBy/${this.groupTask.value}`), {
          params: {
            ...this.filters,
          },
        })
        .then((res) => {
          this.filteredTasks = res.data;
          this.page = this.loading = false;
          this.displayGroupTasks = true;
          this.loading = false;
        });
    },
    getStatuses() {
      if (this.taskStatuses.length == 0) {
        axios.get("api/statuses").then((res) => {
          res.data.forEach((status) => {
            this.taskStatuses.push({
              name: status.name,
              query: "status_id",
              id: status.id,
            });
          });
        });
      }
    },
    getTaskTags() {
      if (this.localTags.length == 0) {
        axios.get(this.appPath(`api/tasks/tags`)).then((res) => {
          this.localTags.push(...res.data);
        });
      }
    },
    removeItem(item, model) {
      model.forEach((tag, index) => {
        if (item.id == tag.id) {
          model.splice(index, 1);
        }
      });
    },
    filter() {
      const filtersLen = Object.keys(this.filters).length;
      this.countFilters();
      if (this.groupTask) {
        this.loadGroupTasks();
      } else if (filtersLen > 1 || this.selectEmployee) {
        this.filterTask();
      } else {
        this.filteredTasks = [];
        this.page = 1;
        localStorage.setItem("filters", JSON.stringify(this.filters));
        this.displayGroupTasks = false;
        this.paginate();
      }
    },
  },
  computed: {
    activeBtn() {
      return Object.freeze({
        TABLE: 1,
        CALENDAR: 2,
        KANBAN: 3,
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
  },
  watch: {
    filtersMenu(val) {
      if (val) {
        this.getTaskTags();
        this.getStatuses();
        
        axios.get(this.appPath("api/users")).then((resp) => {
          this.localUsers = resp.data;
        });
      }
    },
    currentView(value) {
      localStorage.currentView = value;
      if (value == 3) {
        Event.fire("isKanbanTasks");
      }
    },
    selectedTags(tags) {
      let tagsId = JSON.stringify(tags.map((tag) => tag.id));
      if (!tags.length) {
        localStorage.removeItem("tags");
        this.deleteFilter("tags");
      } else {
        this.setFilter("tags", tagsId);
        localStorage.setItem("tags", JSON.stringify(tags));
      }
      this.filter();
    },
    searchTask(title) {
      title === "" || this.filters["title"]
        ? this.deleteFilter("title")
        : this.setFilter("title", title);
    },
    taskCategory(newVal, oldVal) {
      if (oldVal) {
        localStorage.removeItem("taskCategory");
        this.deleteFilter(oldVal.query);
      }
      if (newVal) {
        this.setFilter(newVal.query, newVal.user);
        localStorage.setItem("taskCategory", JSON.stringify(newVal));
      }
      this.filter();
    },
    employees(items) {
      let employeeIds = JSON.stringify(items.map((item) => item.id));
      if (!items.length) {
        this.deleteFilter("employee_id");
        this.setFilter("all", true);
        localStorage.removeItem("employee");
      } else {
        if (this.filters["employee_id"]) {
          this.deleteFilter("employee_id");
        }
        this.page = false;
        this.deleteFilter("all");
        this.setFilter("employee_id", employeeIds);
        localStorage.setItem("employee", JSON.stringify(items));
      }
      this.filter();
    },
    priority(item) {
      if (!item) {
        localStorage.removeItem("priority");
        this.deleteFilter("priority");
      } else {
        this.setFilter("priority", item.id);
        localStorage.setItem("priority", JSON.stringify(item));
      }
      this.filter();
    },
    selectedStatuses(statuses) {
      let statusesId = JSON.stringify(statuses.map((status) => status.id));
      if (!statuses.length) {
        localStorage.removeItem("status");
        this.deleteFilter("status_id");
      } else {
        this.setFilter("status_id", statusesId);
        localStorage.setItem("status", JSON.stringify(statuses));
      }
      this.filter();
    },
    selectEmployee(val) {
      if (val && this.taskCategory) {
        this.deleteFilter(this.taskCategory.query);
      }
      if (!val) {
        this.employees = [];
        this.setFilter("all", true);
        this.deleteFilter("employee_id");
        localStorage.setItem("selectEmployee", null);
      } else {
        this.taskCategory = null;
        localStorage.setItem("selectEmployee", JSON.stringify(true));
        this.deleteFilter("task_responsible_id");
        localStorage.removeItem("responsible");
        this.responsible = null;
        this.divisionUsers();
      }
    },
    author(item) {
      if (!item) {
        localStorage.author = null;
        this.deleteFilter("author_id");
      } else {
        this.setFilter("author_id", item.id);
        localStorage.setItem(
          "author",
          JSON.stringify({ id: item.id, fullname: item.fullname })
        );
      }
      this.filter();
    },
    responsible(item) {
      if (!item) {
        localStorage.responsible = null;
        this.deleteFilter("task_responsible_id");
      } else {
        this.setFilter("task_responsible_id", item.id);
        localStorage.setItem(
          "responsible",
          JSON.stringify({ id: item.id, fullname: item.fullname })
        );
      }
      this.filter();
    },
    groupTask(item) {
      if (!item) {
        localStorage.groupTask = null;
        this.filter();
      } else {
        localStorage.setItem("groupTask", JSON.stringify(item));
        Event.fire("groupType", item.value);
        this.loadGroupTasks();
      }
    },
  },
  created() {
    // localStorage.clear(); // Remove after localstorage cleared
    Event.listen("loadTasks", (data) => this.paginate());
    Event.listen("filterByPriority", (data) => {
      this.priorityItems.forEach((item) => {
        if (item.id == data) {
          this.priority = item;
        }
      });
      this.filters.priority = data;
      this.filter();
    });
    Event.listen("filterByStatus", (statusId) => {
      this.selectedStatuses = [];
      this.taskStatuses.forEach((item) => {
        if (item.id == statusId) {
          this.selectedStatuses.push(item);
        }
      });
      this.filters.status_id = JSON.stringify(
        this.selectedStatuses.map((status) => status.id)
      );
      this.filter();
    });
    Event.listen("filterByTag", (tag) => {
      this.selectedTags = [];
      this.selectedTags.push(tag);
      this.filters.tags = JSON.stringify(
        this.selectedTags.map((tag) => tag.id)
      );
      this.filterTask();
    });
    Event.listen("filterByResponsible", (user) => {
      if (user.id == this.auth.id) {
        this.taskCategory = this.filterItems.find(
          (item) => item.name == "Мои задачи"
        );
        this.filters.responsible_id = user.id;
      } else {
        this.responsibleItems.push(user);
        this.responsible = user;
        this.filters.task_responsible_id = user.id;
      }
      this.filter();
    });
    Event.listen("filterByAuthor", (user) => {
      if (user.id == this.auth.id) {
        this.taskCategory = this.filterItems.find(
          (item) => item.name == "Поставленные задачи"
        );
        this.filters.from_id = user.id;
      } else {
        this.authorItems.push(user);
        this.author = user;
        this.filters.author_id = user.id;
      }
      this.filter();
    });
  },
};
</script>


