<template>
  <div>
    <!-- <v-dialog v-model="taskDialog" max-width="1000">
            <task :item="selectedTask" :users="users"></task>
    </v-dialog>-->
    
    
    <!-- <v-card>You dont have any tasks</v-card> -->
    <v-data-table
      v-if="localTasks.length"
      :headers="headers"
      :items="localTasks"
      class="elevation-1 tasks-table"
      item-key="id"
      hide-default-footer
      :items-per-page="-1"
      height="calc(100vh - 120px)"
      fixed-header
      no-data-text="У вас нет задач"
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
          <td>{{item.title}}</td>
          <td>
            <priority :id="item.priority" icon></priority>
          </td>
          <td>
           <span>{{durObj(item.planned_time)}}</span>
          </td>
          <td>{{ moment(item.deadline).fromNow() }}</td>
          <td>
            <avatar :user="item.from" />
          </td>
          <td>
            <avatar :user="item.responsible" />
          </td>
          <td>{{ moment(item.created_at).fromNow() }}</td>
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
    <v-alert type="info" v-else>
      У вас нет задач
    </v-alert>
  </div>
</template>

<script>
export default {
  props: {
    tasks: Array
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
        { text: "Исполнитель", value: "responsible", sort: false},
        { text: "CreatedAt", value: "created_at" },
        { text: "Статус", value: "status" },
        { text: "Теги", value: "tags", width: 100 }
      ],
      selectedTask: null,
      taskDialog: false,
      menu: false
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
        }).catch(err => err.messages);
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
</style>
