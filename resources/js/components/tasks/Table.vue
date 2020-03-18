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
                  <v-list-item @click="mark(item)">
                    <v-list-item-title v-if="item.readed">Отметить как не прочитанное</v-list-item-title>
                    <v-list-item-title v-if="!item.readed">Отметить как прочитанное</v-list-item-title>
                  </v-list-item>
                </v-list>
              </v-menu>
            </td>
            <td>{{item.responsibility_description.title}}</td>
            <td>
              <priority :id="item.priority" icon></priority>
            </td>
            <td>
              <span>{{durObj(item.planned_time)}}</span>
            </td>
            <td>{{ moment(item.deadline).format('L') }}</td>
            <td>
              <avatar size="30" :user="item.from" />
            </td>
            <td>
              <avatar size="30" :user="item.responsible" />
            </td>
            <td>{{ moment(item.created_at).format('L') }}</td>
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
      <v-alert type="info" v-else>У вас нет задач</v-alert>
    </v-infinite-scroll>
    <!-- <v-pagination v-if="tasks.length" v-model="page" :length="pageCount"></v-pagination> -->
  </div>
</template>

<script>
import InfiniteScroll from "v-infinite-scroll";
import "v-infinite-scroll/dist/v-infinite-scroll.css";

export default {
  props: {
    tasks: Array
  },
  components: {
    "v-infinite-scroll": InfiniteScroll
  },
  data() {
    return {
      localTasks: this.tasks,
      headers: [
        { text: "" },
        { text: "Задача", value: "title" },
        { text: "Приоритет", value: "priority" },
        { text: "Время на задачу", value: "planned_time" },
        { text: "Дедлайн", value: "deadline" },
        { text: "От", value: "from", sort: false },
        { text: "Исполнитель", value: "responsible", sort: false },
        { text: "Поставленна", value: "created_at" },
        { text: "Статус", value: "status" },
        { text: "Теги", value: "tags", width: 100 }
      ],
      selectedTask: null,
      taskDialog: false,
      menu: false,
      page: 1,
      pageCount: 0,
      perPage: 30
    };
  },
  methods: {
    displayTask(item) {
      window.location.href = "tasks/" + item.id;
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
            }
          });
        })
        .catch(err => err.messages);
    },
    prevPage() {
      if(this.page == 1) return 
      --this.page;
      this.perPage = 30;
    },
    nextPage(){
      this.perPage += 30;
      Event.fire('loadTasks');
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
  },
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
