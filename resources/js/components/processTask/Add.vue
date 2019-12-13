<template>
  <div>
    <v-dialog v-model="dialog" width="600">
      <v-card>
        <v-toolbar dense flat dark color="primary">
          <v-toolbar-title>Добавить задачу</v-toolbar-title>
        </v-toolbar>
        <v-card-text>
          <v-form ref="createProcessTaskForm" class="mt-5">
            <v-row>
              <v-col v-for="(field, index) in fields" :key="index" :cols="field.col">
                <form-field :field="field" v-model="field.value"></form-field>
              </v-col>
            </v-row>
            <v-card-actions>
              <v-spacer></v-spacer>
            <v-btn text color="primary" @click="cancelSubmit()">отмена</v-btn>
            <v-btn color="primary" @click="submit(processId)">добавить</v-btn>
            </v-card-actions>
          </v-form>
        </v-card-text>
      </v-card>
    </v-dialog>
    <v-btn small depressed color="primary" @click="dialog = true">Добавить задачу</v-btn>
  </div>
</template>
<script>
export default {
  props: ["processId"],
  data() {
    const items = this.getResponsibilities(); // Variable to store responsibilites array

    return {
      dialog: false,
      processTask: {},
      milliseconds: null,
      fields: [
        {
          type: "string",
          label: "Название задачи",
          name: "processTaskName",
          rules: ["required"],
          col: 12,
          value: null
        },
        {
          type: "select",
          label: "Исполнитель",
          name: "processTaskResponsible",
          rules: ["required"],
          items: items,
          col: 12,
          value: null
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
          value: null
        }
      ]
    };
  },
  methods: {
    // Submit data

    submit(id) {
      // Prepare data before request
      const form = this.$refs.createProcessTaskForm;
      this.prepareData();
      if (form.validate()) {
        axios
          .post("/api/process/tasks/create", {
            processId: id,
            data: this.processTask,
            planned_time: this.milliseconds
          })
          .then(res => {
            this.dialog = false; //close dialog
            Event.fire("processTaskCreated", res.data); //Fire event for adding ProcessTas without page reload
            form.reset(); // Clear fields after task create
          });
      }
    },
    cancelSubmit(){
      const form = this.$refs.createProcessTaskForm;
      form.reset();
      this.dialog = false
    },
    dataToMilliseconds(days, hours, minutes) {
      this.milliseconds = days * 86400000 + hours * 3600000 + minutes * 60000;
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
    getResponsibilities() {
      let items = []; // Arrat to push response data

      axios
        .get("/api/responsibilities")
        .then(res => {
          res.data.forEach(item => {
            items.push({ name: item.name, id: item.id }); // collect data and store in array
          });
        })
        .catch(err => err.messages);

      // return collected items for field
      return items;
    }
  }
};
</script>