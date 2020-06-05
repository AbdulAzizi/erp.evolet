<template>
  <v-list-item>
    <v-dialog ref="deadlineDialog" v-model="dialog" max-width="400" v-if="edit">
      <template v-slot:activator="{ on }">
        <v-btn v-on="on" height="40" width="40" icon @click class="mr-4 my-2">
          <v-icon>mdi-pencil</v-icon>
        </v-btn>
      </template>
      <v-card>
        <v-toolbar dark color="primary" flat>Изменить приоритет</v-toolbar>
        <v-list nav>
          <v-list-item-group color="primary" v-model="selectedPriority">
            <v-list-item
              v-for="(priority,index) in priorities"
              :key="'priority-'+index"
              :disabled="(index < localTask.priority) && isTaskResponsible"
            >
              <v-list-item-icon>
                <v-icon :color="priority.color">mdi-flag-variant</v-icon>
              </v-list-item-icon>
              <v-list-item-content>
                <v-list-item-title v-text="priority.label"></v-list-item-title>
              </v-list-item-content>
            </v-list-item>
          </v-list-item-group>
        </v-list>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="primary" text @click="dialog = false">Отмена</v-btn>
          <v-btn color="primary" depressed type="submit" @click.prevent="submit">Изменить</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-list-item-avatar v-else>
      <v-icon :color="priorities[localTask.priority].color">mdi-flag-variant</v-icon>
    </v-list-item-avatar>

    <v-list-item-content>
      <v-list-item-title>{{priorities[localTask.priority].label}}</v-list-item-title>
      <v-list-item-subtitle>Приоритет</v-list-item-subtitle>
    </v-list-item-content>
  </v-list-item>
</template>
<script>
export default {
  props: {
    task: {
      required: true
    },
    edit: {
      required: false,
      default: false
    }
  },
  data() {
    return {
      localTask: this.task,
      id: this.task.id,
      selectedPriority: null,
      dialog: false,
      priorities: [
        { id: 0, label: "Низкий", color: "green lighten-3" },
        { id: 1, label: "Средний", color: "blue lighten-3" },
        { id: 2, label: "Высокий", color: "red lighten-3" }
      ],
      allowedToChange: false
    };
  },
  methods: {
    submit() {
      this.dialog = false;
      if (this.localTask.priority != this.selectedPriority) {
        axios
          .put(this.appPath(`api/tasks/${this.id}/priority`), {
            priority: this.selectedPriority
          })
          .then(resp => {
            this.localTask.priority = resp.data;
            Event.fire("notify", ["Приоритет задачи успешно изменён"]);
          });
      }
    }
  },
  watch: {
    dialog(val) {
      if (!val) return;

      this.selectedPriority = this.localTask.priority;
    },
    selectedPriority(val) {
      if (val == undefined) {
        this.selectedPriority = this.localTask.priority;
      }
    }
  },
  computed: {
    isTaskResponsible() {
      return this.auth.id === this.task.responsible_id;
    }
  }
};
</script>