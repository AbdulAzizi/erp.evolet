<template>
  <div>
    <v-row>
      <v-col cols="6">
        <v-data-table
          :items="calculateFromTasks"
          :headers="fromHeaders"
          :items-per-page="-1"
          hide-default-footer
          dense
        >
          <template v-slot:item="{ item }">
            <tr @click="redirect(item.user, 'author')" class="clickable">
              <td>
                <avatar size="30" :user="item.user" :link="true" :fullname="true" />
              </td>
              <td>{{item.amount}}</td>
            </tr>
          </template>
        </v-data-table>
      </v-col>
      <v-col cols="6">
        <v-data-table
          :items="calculateToTasks"
          :headers="toHeaders"
          :items-per-page="-1"
          hide-default-footer
          dense
        >
          <template v-slot:item="{ item }">
            <tr @click="redirect(item.user, 'responsible')" class="clickable">
              <td>
                <avatar size="30" :user="item.user" :link="true" :fullname="true" />
              </td>
              <td>{{item.amount}}</td>
            </tr>
          </template>
        </v-data-table>
      </v-col>
    </v-row>
  </div>
</template>
<script>
export default {
  props: {
    fromtasks: {
      required: false
    },
    totasks: {
      required: false
    }
  },
  data() {
    return {
      fromHeaders: [
        { text: "Пользователь", value: "title" },
        { text: "Поставлено задач мне", value: "description" }
      ],
      toHeaders: [
        { text: "Пользователь", value: "title" },
        { text: "Поставлено мной задач", value: "description" }
      ],
      localTasks: this.tasks
    };
  },
  computed: {
    calculateFromTasks() {
      let tasks = [];
      for (const key in this.fromtasks) {
        tasks.push({
          user: this.fromtasks[key][0].from,
          amount: this.fromtasks[key].length
        });
      }
      return tasks;
    },
    calculateToTasks() {
      let tasks = [];

      for (const key in this.totasks) {
        tasks.push({
          user: this.totasks[key][0].responsible,
          amount: this.totasks[key].length
        });
      }
      return tasks;
    }
  },
  methods: {
    redirect(user, type) {
      localStorage.clear();
      localStorage.setItem("filters", JSON.stringify({all: true}));
      localStorage.setItem(
        type,
        JSON.stringify({ id: user.id, fullname: user.fullname })
      );
      window.location.replace("/tasks?all=true");
    }
  }
};
</script>
<style>
  .clickable {
    cursor: pointer;
  }
</style>