<template>
  <div>
    <!-- <v-dialog v-model="taskDialog" max-width="1000">
            <task :item="selectedTask" :users="users"></task>
    </v-dialog>-->
    <v-infinite-scroll @bottom="nextPage()" style="max-height: 80vh; overflow-y: scroll;">
      <v-data-table
        v-if="localTasks.length"
        :headers="headers"
        :items="localTasks"
        class="elevation-1 tasks-table"
        item-key="id"
        hide-default-footer
        :items-per-page="perPage"
        :page.sync="page"
        @page-count="pageCount = $event"
        fixed-header
        no-data-text="У вас нет задач"
        dense
      >
        <template v-slot:item="{ item }">
          <tr :class="(item.readed == 0 ? 'grey lighten-2' : 'white')" @click="displayTask(item)">
            <td @click.stop>
              <v-menu offset-y>
                <template v-slot:activator="{on}">
                  <v-btn icon v-on="on">
                    <v-icon small>mdi-dots-vertical</v-icon>
                  </v-btn>
                </template>
                <v-list dense>
                  <v-list-item @click="mark(item)" v-if="auth.id == item.responsible.id">
                    <v-list-item-title v-if="item.readed">Отметить как не прочитанное</v-list-item-title>
                    <v-list-item-title v-if="!item.readed">Отметить как прочитанное</v-list-item-title>
                  </v-list-item>
                  <v-list-item v-if="taskAuthor(item)" @click="deleteTask(item)">
                    <v-list-item-title>Удалить задачу</v-list-item-title>
                  </v-list-item>
                </v-list>
              </v-menu>
            </td>
            <td @click.stop="filter(item.priority, 'filterByPriority')">
              <priority :id="item.priority" icon></priority>
            </td>
            <td>
              {{item.responsibility_description ? item.responsibility_description.text.substring(0, 30) : 'Удалено' }}
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
                <span v-on="on">{{ item.description.length >= 30 ? item.description.substring(0, 30) + '...' : item.description }}</span>
                </template>
                <div style="max-width: 250px" class="pa-0">{{ item.description }}</div>
              </v-tooltip>
            </td>
            <td>
              <span>{{durObj(item.planned_time)}}</span>
            </td>
            <td>{{ moment(item.deadline).local().format('DD-MM-Y hh:mm') }}</td>
            <td @click.stop="filter(item.from, 'filterByAuthor')">
              <avatar size="30" :user="item.from" :link="false" />
            </td>
            <td @click.stop="filter(item.responsible, 'filterByResponsible')">
              <avatar size="30" :user="item.responsible" :link="false" />
            </td>
            <td>{{ moment(item.created_at).local().format('DD-MM-Y hh:mm') }}</td>
            <td @click.stop="filter(item.status.id, 'filterByStatus')">{{ item.status.name }}</td>
            <td>
              <v-chip
                class="primary mr-1"
                small
                v-for="(tag,index) in item.tags"
                :key="'tag-'+index"
                @click.stop="filter(tag, 'filterByTag')"
              >{{tag.name}}</v-chip>
            </td>
          </tr>
        </template>
      </v-data-table>
      <v-alert type="info" v-else>У вас нет задач</v-alert>
    </v-infinite-scroll>
    <delete-record
      :visible="deleteTaskDialog"
      @close="deleteTaskDialog = false"
      :route="`/api/tasks/${taskId}`"
    ></delete-record>
    <!-- <v-pagination v-if="tasks.length" v-model="page" :length="pageCount"></v-pagination> -->
  </div>
</template>

<script>
import InfiniteScroll from "v-infinite-scroll";
import "v-infinite-scroll/dist/v-infinite-scroll.css";

export default {
  props: {
    tasks: {
      required: true
    }
  },
  components: {
    "v-infinite-scroll": InfiniteScroll
  },
  data() {
    return {
      localTasks: this.tasks,
      headers: [
        { text: "", sortable: false },
        { text: "", value: "priority" },
        { text: "Задача", value: "title" },
        { text: "Описание", value: "description" },
        { text: "Время на задачу", value: "planned_time" },
        { text: "Дедлайн", value: "deadline" },
        { text: "От", value: "from" },
        { text: "Испол", value: "responsible" },
        { text: "Поставлена", value: "created_at" },
        { text: "Статус", value: "status.name" },
        { text: "Теги", value: "tags", width: 100 }
      ],
      selectedTask: null,
      taskDialog: false,
      menu: false,
      page: 1,
      pageCount: 0,
      perPage: 30,
      deleteTaskDialog: false,
      taskId: null
    };
  },
  methods: {
    displayTask(item) {
      window.location.href = "tasks/" + item.id;
    },
    filter(item, eventName) {
      Event.fire(eventName, item);
    },
    mark(task) {
      let dataToSend = task.readed ? 0 : 1;
      axios
        .put(`/api/task/mark/${task.id}`, {
          readed: dataToSend
        })
        .then(res => {
          this.localTasks.forEach(elem => {
            if (elem.id == res.data.id) {
              elem.readed = res.data.readed;
              elem.read_at = res.data.read_at;
            }
          });
        })
        .catch(err => err.messages);
    },
    prevPage() {
      if (this.page == 1) return;
      --this.page;
      this.perPage = 30;
    },
    nextPage() {
      this.perPage += 30;
      Event.fire("loadTasks");
    },
    taskAuthor(task) {
      return this.auth.id === task.from_id;
    },
    deleteTask(task) {
      this.taskId = task.id;
      this.deleteTaskDialog = true;
    }
  },
  created() {
    Event.listen("taskStatusChanged", data => {
      this.localTasks.forEach((task, index) => {
        if (task.id == data.id) {
          this.localTasks[index] = data;
        }
      });
    });
  },
  watch: {
    tasks(val) {
      this.localTasks = val;
    }
  }
};
</script>

<style>
.tasks-table {
  cursor: pointer;
}
.tasks-table th,
.tasks-table td {
  padding-left: 0;
  padding-right: 0;
}
</style>
