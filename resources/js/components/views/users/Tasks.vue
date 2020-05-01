<template>
  <div>
    <v-row>
      <v-col cols="11">
        <v-card flat>
          <v-card-text class="pa-2">Начните искать задачи сотрудников</v-card-text>
        </v-card>
      </v-col>
      <v-col cols="1">
        <v-btn depressed block color="primary" height="38" @click="filtersMenu = true">
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
            >
              <template v-slot:selection="data">
                <v-chip
                  @click="data.select"
                  @click:close="removeItem(data.item.id)"
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
                  @click:close="removeItem(data.item)"
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
            <!-- <v-select
              :items="divisions"
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
              multiple
            >
              <template v-slot:selection="data">
                <div
                  class="my-1 pa-2"
                  @click="data.select"
                  v-bind="data.attrs"
                  :input-value="data.selected"
                  style="background: #6897f5; color: white; border-left-radius: 30%"
                >{{ data.item.name }}</div>
              </template>
            </v-select> -->
          </v-form>
        </v-card-text>
      </v-card>
    </v-navigation-drawer>
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
      divisions: this.loadDivisions(),
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
      if (Object.keys(this.filters).length > 0) {
        axios
          .get(this.appPath("api/users/tasks"), {
            params: {
              ...this.filters
            }
          })
          .then(res => {
            this.filteredTasks = res.data;
            this.getTags(res.data);
          });
      } else {
        this.filteredTasks = null;
        this.tags = [];
      }
    },
    getUsers() {
      axios.get(this.appPath("api/users")).then(res => {
        this.allUsers = res.data;
      });
    },
    removeItem(itemId) {
      this.users = this.users.filter(userId => userId !== itemId);
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
    getTags(tasks) {
      tasks.forEach(task => {
        task.tags.forEach(tag => {
          this.tags.push({
            id: tag.id,
            name: tag.name
          });
        });
      });
    }
  },
  watch: {
    users(items) {
      if (!items.length) {
        delete this.filters.user_id;
      } else {
        this.filters.user_id = JSON.stringify(items);
      }
      this.getFilteredTasks();
    },
    priority(item) {
      if (!item) {
        delete this.filters.priority;
      } else {
        this.filters.priority = item.id;
      }
      this.getFilteredTasks();
    },
    statuses(items) {
      if (!items.length) {
        delete this.filters.status_id;
      } else {
        this.filters.status_id = JSON.stringify(items);
      }
      this.getFilteredTasks();
    },
    selectedTags(items) {
      if (!items.length) {
        delete this.filters.tag_id;
      } else {
        this.filters.tag_id = JSON.stringify(items);
      }
      this.getFilteredTasks();
    }
  },
  created() {
    this.getUsers();
    this.getStatuses();
  }
};
</script>