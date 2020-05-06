<template>
  <div>
    <v-row>
      <v-col cols="11">
        <v-card flat v-if="!divisionId">
          <v-card-text class="pa-2">Начните искать задачи сотрудников</v-card-text>
        </v-card>
        <v-alert v-else dense dark height="38" class="ma-0" color="primary">{{ divisionName }}</v-alert>
      </v-col>
      <v-col cols="1">
        <v-btn depressed block :color="divisionId ? 'white' : 'primary'" height="38" @click="filtersMenu = true">
          <v-icon>mdi-filter</v-icon>
        </v-btn>
      </v-col>
    </v-row>
    <v-row>
      <v-col cols="12">
        <v-data-table
          v-if="filteredTasks"
          :headers="headers"
          :items="filteredTasks"
          class="elevation-1 tasks-table"
          item-key="id"
          hide-default-footer
          :items-per-page="-1"
          no-data-text="У вас нет задач"
          dense
        >
          <template v-slot:item="{ item }">
            <tr :class="(item.readed == 0 ? 'grey lighten-2' : 'white')" @click="displayTask(item)">
              <td>
                <priority :id="item.priority" icon></priority>
              </td>
              <td>
                {{item.responsibility_description.text}}
                <v-tooltip bottom>
                  <template v-slot:activator="{ on }">
                    <v-icon small v-if="item.read_at" color="green" v-on="on">mdi-check-all</v-icon>
                  </template>
                  <span>Просмотрено {{ moment(item.read_at).local().format('lll') }}</span>
                </v-tooltip>
              </td>
              <td>
                <v-tooltip bottom>
                  <template v-slot:activator="{ on }">
                    <span
                      v-on="on"
                    >{{ item.description.length >= 30 ? item.description.substring(0, 30) + '...' : item.description }}</span>
                  </template>
                  <div style="max-width: 250px" class="pa-0">{{ item.description }}</div>
                </v-tooltip>
              </td>
              <td>
                <span>{{durObj(item.planned_time)}}</span>
              </td>
              <td>{{ moment(item.deadline).format('DD-MM-Y hh:mm') }}</td>
              <td>
                <avatar size="30" :user="item.from" :link="true" />
              </td>
              <td>
                <avatar size="30" :user="item.responsible" :link="true" />
              </td>
              <td>{{ moment(item.created_at).format('DD-MM-Y hh:mm') }}</td>
              <td>{{ item.status.name }}</td>
              <td>
                <v-chip
                  class="primary mr-1"
                  small
                  v-for="(tag,index) in item.tags"
                  :key="'tag-'+index"
                >{{tag.name}}</v-chip>
              </td>
            </tr>
          </template>
        </v-data-table>
      </v-col>
    </v-row>
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
              v-model="users"
              :items="allUsers"
              label="Выберите сотрудников"
              class="mb-4"
              item-text="name"
              item-value="id"
              outlined
              flat
              dense
              hide-details
              single-line
              chips
              deletable-chips
              no-data-text="Нет сотрудников"
              multiple
              :disabled="Boolean(divisionId)"
            >
              <template v-slot:selection="data">
                <v-chip
                  @click="data.select"
                  @click:close="removeUser(data.item.id)"
                  v-bind="data.attrs"
                  :input-value="data.selected"
                  color="primary"
                  close
                  class="my-1"
                >
                  <v-avatar left>
                    <v-img :src="thumb(data.item.img)"></v-img>
                  </v-avatar>
                  {{ data.item.fullname }}
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
            <v-autocomplete
              v-model="divisionId"
              :items="divisionItems"
              label="Выберите отдел"
              class="mb-4"
              item-text="name"
              item-value="id"
              outlined
              flat
              dense
              hide-details
              single-line
              clearable
              :disabled="users.length > 0"
            >
              <template v-slot:selection="data">
                <div
                  class="my-1 pa-2"
                  @click="data.select"
                  v-bind="data.attrs"
                  :input-value="data.selected"
                >{{ data.item.name }}</div>
              </template>
            </v-autocomplete>
            <v-select
              v-model="statuses"
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
              clearable
              multiple
            >
              <template v-slot:selection="data">
                <v-chip
                  class="my-1"
                  @click="data.select"
                  v-bind="data.attrs"
                  :input-value="data.selected"
                  color="primary"
                  small
                >{{ data.item.name }}</v-chip>
              </template>
            </v-select>
            <v-autocomplete
              v-model="selectedTags"
              :items="tags"
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
              flat
              clearable
            >
              <template v-slot:selection="data">
                <v-chip
                  v-bind="data.attrs"
                  :input-value="data.selected"
                  close
                  small
                  color="primary"
                  @click:close="removeTag(data.item.id)"
                >{{data.item.name}}</v-chip>
              </template>
            </v-autocomplete>
            <v-select
              v-model="priority"
              label="Приоритет"
              class="mb-4"
              :items="priorityItems"
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
          </v-form>
        </v-card-text>
      </v-card>
    </v-navigation-drawer>
     <v-overlay v-if="loading" light opacity="0">
      <v-progress-circular indeterminate size="64" color="primary"></v-progress-circular>
    </v-overlay>
  </div>
</template>
<script>
export default {
  data() {
    return {
      filteredTasks: null,
      filtersMenu: false,
      allUsers: null,
      filters: {},
      users: [],
      taskStatuses: [],
      statuses: [],
      tags: [],
      selectedTags: [],
      priority: null,
      divisionId: null,
      loading: false,
      divisionItems: this.loadDivisions(), // it comes from app.js
      headers: [
        { text: "", value: "priority" },
        { text: "Задача", value: "title" },
        { text: "Описание", value: "description" },
        { text: "Время на задачу", value: "planned_time" },
        { text: "Дедлайн", value: "deadline" },
        { text: "От", value: "from", sort: false },
        { text: "Испол", value: "responsible", sort: false },
        { text: "Поставлена", value: "created_at" },
        { text: "Статус", value: "status" },
        { text: "Теги", value: "tags", width: 100 }
      ],
      priorityItems: [
        {
          name: "Высокий",
          color: "red lighten-1",
          id: 2
        },
        {
          name: "Средний",
          color: "blue lighten-1",
          id: 1
        },
        {
          name: "Низкий",
          color: "green lighten-1",
          id: 0
        }
      ]
    };
  },
  methods: {
    displayTask(item) {
      window.location.href = "tasks/" + item.id;
    },
    getFilteredTasks() {
      // check filters length, if empty, don't send response
      if (Object.keys(this.filters).length > 0) {
        this.loading = true;
        axios
          .get(this.appPath("api/users/tasks"), {
            params: {
              ...this.filters
            }
          })
          .then(res => {
            this.filteredTasks = res.data;
            this.getTags(res.data);
            this.loading = false;
          });
      } else {
        this.filteredTasks = null;
        this.tags = [];
      }
    },
    getDivisionTasks() {
      this.loading = true;
      axios
        .get(this.appPath(`api/divisions/${this.divisionId}/tasks`), {
          params: {
            ...this.filters
          }
        })
        .then(res => {
          this.filteredTasks = res.data;
          this.getTags(res.data);
          this.loading = false;
        });
    },
    getUsers() {
      axios.get(this.appPath("api/users")).then(res => {
        this.allUsers = res.data;
      });
    },
    removeUser(userId) {
      this.users = this.users.filter(itemId => itemId !== userId);
    },
    removeTag(tagId) {
      this.selectedTags = this.selectedTags.filter(itemId => itemId !== tagId);
    },
    getStatuses() {
      if (this.taskStatuses.length == 0) {
        axios.get("api/statuses").then(res => {
          res.data.forEach(status => {
            this.taskStatuses.push({
              name: status.name,
              id: status.id
            });
          });
        });
      }
    },
    // Get tags of filtered tasks
    getTags(tasks) {
      tasks.forEach(task => {
        task.tags.forEach(tag => {
          this.tags.push({
            id: tag.id,
            name: tag.name
          });
        });
      });
    },
    // Set filters from forms and filter
    setFiltersAndFilterTasks(value, item) {
      if (Array.isArray(item)) {
        !item.length
          ? delete this.filters[value]
          : (this.filters[value] = JSON.stringify(item));
      } else {
        !item ? delete this.filters[value] : (this.filters[value] = item.id);
      }
      this.divisionId ? this.getDivisionTasks() : this.getFilteredTasks();
    }
  },
  computed: {
    divisionName() {
      let name = null;
      this.divisionId
        ? this.divisionItems.filter(division => {
            division.id == this.divisionId ? (name = division.name) : "";
          })
        : "";

      return name;
    }
  },
  watch: {
    users(items) {
      this.setFiltersAndFilterTasks("user_id", items);
    },
    priority(item) {
      this.setFiltersAndFilterTasks("priority", item);
    },
    statuses(items) {
      this.setFiltersAndFilterTasks("status_id", items);
    },
    selectedTags(items) {
      this.setFiltersAndFilterTasks("tag_id", items);
    },
    divisionId(id) {
      id ? this.getDivisionTasks() : (this.filteredTasks = this.tags = null);
    }
  },
  created() {
    this.getUsers();
    this.getStatuses();
  }
};
</script>