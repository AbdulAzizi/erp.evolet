<template>
  <div>
    <v-btn icon small class="float-right" color="grey lighten-1" @click="deleteTaskDialog = true">
      <v-icon small>mdi-delete</v-icon>
    </v-btn>
    <v-btn icon small class="float-right" color="grey lighten-1" @click="editDialog = true">
      <v-icon small>mdi-pencil</v-icon>
    </v-btn>
    <v-dialog v-model="deleteTaskDialog" width="400">
      <v-card>
        <v-card-title class="headline">Вы хотите удалить задачу?</v-card-title>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="red lighten-2" text @click="deleteTaskDialog = false">Отмена</v-btn>
          <v-btn color="primary" text @click="deleteTask()">Удалить</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-dialog v-model="editDialog" width="600">
      <v-card>
        <v-toolbar dense flat dark color="primary">
          <v-toolbar-title>Изменить задачу</v-toolbar-title>
        </v-toolbar>
        <v-card-text>
          <v-form ref="editProcessTaskForm" class="mt-5">
            <v-row>
              <v-col v-for="(field, index) in fields" :key="index" :cols="field.col">
                <form-field :field="field" v-model="field.value"></form-field>
              </v-col>
            </v-row>
            <v-card-actions>
              <v-spacer></v-spacer>
            <v-btn text color="primary" @click="editDialog = false">отмена</v-btn>
            <v-btn @click="submit()" color="primary">Отправить</v-btn>
            </v-card-actions>
          </v-form>
        </v-card-text>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
export default {
  props: ["task"],
  data() {
    const items = this.loadResponsibilities();
    return {
      dialog: false,
      deleteTaskDialog: false,
      editDialog: false,
      processTask: {},
      milliseconds: null,
      fields: [
        {
          type: "string",
          label: "Название задачи",
          name: "processTaskName",
          rules: ["required"],
          col: 12,
          value: this.task.title
        },
        {
          type: "select",
          label: "Исполнитель",
          name: "processTaskResponsible",
          rules: ["required"],
          items: items,
          col: 12,
          value: this.task.responsibility.id
        },
        {
          type: "number",
          label: "Дни",
          name: "processTaskDays",
          rules: ["required"],
          col: 4,
          value: null
        },
        {
          type: "number",
          label: "Часы",
          name: "processTaskHours",
          col: 4,
          value: null
        },
        {
          type: "number",
          label: "Минуты",
          name: "processTaskMinutes",
          col: 4,
          value: null
        },
        {
          type: "text",
          label: "Описание",
          name: "processTaskDescription",
          value: this.task.description
        }
      ]
    };
  },
  methods: {
    deleteTask() {
      axios
        .delete(`/api/process/task/delete/${this.task.id}`)
        .then(res => {
          this.deleteTaskDialog = false;
          Event.fire("processTaskDeleted", this.task.id);
          Event.fire("notify", [`Задача ${this.task.title} удалена`]);
        })
        .catch(err => err.messages);
    },
    submit() {
      const form = this.$refs.editProcessTaskForm;
      this.prepareData();
      if (form.validate()) {
        axios
          .put(`/api/process/task/edit/${this.task.id}`, {
            data: this.processTask,
            planned_time: this.milliseconds
          })
          .then(res => {
            this.editDialog = false;
            Event.fire('processTaskUpdated', res.data);
            Event.fire('notify', [`Задача ${this.task.title} обновлена`]);
          })
          .catch(err => err.messages);
      }
    },
    prepareData() {
      // Variables to store days, hours, minutes

      let days = 0;
      let hours = 0;
      let minutes = 0;

      // Assigning field value to the variable

      this.fields.forEach(elem => {
        if (elem.name == "processTaskDays") {
          days = elem.value;
        } else if (elem.name == "processTaskHours") {
          hours = elem.value;
        } else if (elem.name == "processTaskMinutes") {
          minutes = elem.value;
        } else {
          this.processTask[elem.name] = elem.value; // If value is not days, hours or minutes, store data in object
        }
      });

      // Convert days, hours and minutes to milliseconds
      this.dataToMilliseconds(days, hours, minutes);
    },
  }
};
</script>