<template>
  <div>
    <v-dialog v-model="dialog" width="600" persistent>
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
              <v-btn text color="primary" @click="clearField()">отмена</v-btn>
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
    const items = this.loadPositions(); // Variable to store responsibilites array

    return {
      dialog: false,
      processTask: {},
      days: 0,
      hours: 0,
      minutes: 0,
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
            data: this.processTask
          })
          .then(res => {
            this.dialog = false; //close dialog
            Event.fire("processTaskCreated", res.data); //Fire event for adding ProcessTas without page reload
            this.clearField() // Clear fields after task create
          });
      }
    },
    prepareData() {
      // Assigning field value to the variable

      this.fields.forEach(elem => {
       
          this.processTask[elem.name] = elem.value; // If value is not days, hours or minutes, store data in object
        
      });
    },
      // Convert days, hours and minutes to milliseconds

    dataToMilliseconds(days, hours, minutes) {
      return days * 86400000 + hours * 3600000 + minutes * 60000;
    },
    clearField(){
      const form = this.$refs.createProcessTaskForm;
      form.resetValidation();
      this.fields.forEach(elem => elem.value = null);
      this.dialog = false;

    }
  }
};
</script>