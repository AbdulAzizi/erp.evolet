<template>
  <div class="group_tasks">
    <v-expansion-panels v-for="(task, index) in localTasks" :key="index">
      <v-expansion-panel class="mb-3">
        <v-expansion-panel-header>{{ groupType == "description" ? task[0].description : task[0].responsibility_description.text}}</v-expansion-panel-header>
        <v-expansion-panel-content>
          <v-infinite-scroll style="max-height: 80vh; overflow-y: scroll;">
            <v-data-table
              v-if="localTasks"
              :headers="headers"
              :items="task"
              class="elevation-1 tasks-table"
              item-key="id"
              hide-default-footer
              :items-per-page="task.length"
              fixed-header
              no-data-text="У вас нет задач"
              dense
            >
              <template v-slot:item="{ item }">
                <tr :class="(item.readed == 0 ? 'grey lighten-2' : 'white')">
                  <td @click.stop>
                    <v-menu offset-y>
                      <template v-slot:activator="{on}">
                        <v-btn icon v-on="on">
                          <v-icon small>mdi-dots-vertical</v-icon>
                        </v-btn>
                      </template>
                      <v-list dense>
                        <v-list-item>
                          <v-list-item-title v-if="item.readed">Отметить как не прочитанное</v-list-item-title>
                          <v-list-item-title v-if="!item.readed">Отметить как прочитанное</v-list-item-title>
                        </v-list-item>
                        <v-list-item>
                          <v-list-item-title>Удалить задачу</v-list-item-title>
                        </v-list-item>
                      </v-list>
                    </v-menu>
                  </td>
                  <td>
                    <priority :id="item.priority" icon></priority>
                  </td>
                  <td>
                    {{item.responsibility_description.text}}
                    <v-icon small v-if="item.read_at" color="green">mdi-check-all</v-icon>
                  </td>
                  <td>{{ item.description.substring(0, 30) + '...' }}</td>
                  <td>
                    <span>{{durObj(item.planned_time)}}</span>
                  </td>
                  <td>{{ moment(item.deadline).format('L') }}</td>
                  <td>
                    <avatar size="30" :user="item.from" :link="false" />
                  </td>
                  <td>
                    <avatar size="30" :user="item.responsible" :link="false" />
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
          </v-infinite-scroll>
        </v-expansion-panel-content>
      </v-expansion-panel>
    </v-expansion-panels>
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
      groupType: null,
      headers: [
        { text: "" },
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
      ]
    };
  },
  methods: {
    nextPage() {
      this.perPage += 30;
      Event.fire("loadTasks");
    }
  },
  mounted() {
    this.groupType = localStorage.groupType ? localStorage.groupType : this.groupType;
  },
  created() {
    Event.listen("groupType", data => {
      localStorage.setItem("groupType", data);
      this.groupType = data;
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
.group_tasks .v-expansion-panel-content__wrap {
  padding: 0 !important;
}
</style>