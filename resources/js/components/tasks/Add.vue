<template>
  <v-dialog v-model="dialog" max-width="700">
    <template v-slot:activator="{ on }">
      <v-fab-transition>
        <v-btn v-on="on" dark fab fixed bottom right color="primary">
          <v-icon>mdi-plus</v-icon>
        </v-btn>
      </v-fab-transition>
    </template>

    <v-card>
      <v-form ref="form">
        <v-toolbar dense flat color="primary" dark>
          <v-toolbar-title class="font-weight-regular">Новая задача</v-toolbar-title>
        </v-toolbar>

        <v-container class>
          <v-row>
            <v-col cols="12" class="py-0" v-if="!Array.isArray(errors)">
              <h1 class="subheading red--text text--darken-1 ml-1">Форма заполнена не верно</h1>
            </v-col>
            <v-col cols="12" class="py-0">
              <form-field
                :field="{
                                    type: 'users',
                                    divisions: divisions,
                                    name: 'assignees',
                                    label: 'Исполнители',
                                    icon: 'mdi-account-tie',
                                    hint: 'У каждого исполнителя будет своя отдельная задача',
                                    rules: ['required'],
                                    multiple: true,
                                    dense:true,
                                    selectedUserEventName:'selectedAssignee'
                                }"
                v-model="assignees"
              />
            </v-col>
            <v-col cols="12" class="pb-0">
              <v-dialog
                ref="responsibilityDialog"
                :return-value.sync="selectedRespOrDescrip"
                max-width="700"
                v-model="responsibilityDialog"
                scrollable
              >
                <template v-slot:activator="{ on }">
                  <v-autocomplete
                    :disabled="userResponsibilityDescriptions.length == 0"
                    prepend-icon="mdi-rename-box"
                    rounded
                    filled
                    label="Должностные задачи"
                    v-model="selectedRespOrDescrip"
                    :items="userResponsibilityDescriptions"
                    item-text="text"
                    item-value="id"
                    return-object
                    multiple
                    v-on="on"
                    readonly
                    ref="selectedRespOrDescrip"
                    :rules="[v=> selectedRespOrDescrip.length != 0 || 'Обязательное поле']"
                    hint="При выборе категории будет поставлена задача для каждой подкатегории"
                    persistent-hint
                  >
                    <template v-slot:selection="data">
                      <v-chip small color="primary">{{ data.item.text }}</v-chip>
                    </template>
                  </v-autocomplete>
                </template>
                <v-card style="overflow:auto;" flat>
                  <v-toolbar dense flat dark class="primary">
                    <v-toolbar-title class="font-weight-regular">Выберите должностную задачу</v-toolbar-title>
                  </v-toolbar>
                  <v-divider />
                  <v-card-text class="py-0">
                    <v-treeview
                      v-model="selectedRespOrDescrip"
                      selection-type="leaf"
                      dense
                      :return-object="true"
                      item-children="descriptions"
                      :items="responsibilities"
                      selected-color="primary"
                      item-text="text"
                      item-key="id"
                      selectable
                      class="mr-0"
                      :rules="[v=> Object.keys(v).length != 0 || 'Обязательное поле']"
                    />
                  </v-card-text>
                  <v-divider />
                  <v-card-actions>
                    <v-spacer />
                    <v-btn text @click="responsibilityDialog = false">Отмена</v-btn>
                    <v-btn
                      text
                      @click="$refs.responsibilityDialog.save(selectedRespOrDescrip)"
                      color="primary"
                    >Сохранить</v-btn>
                  </v-card-actions>
                </v-card>
              </v-dialog>
            </v-col>
            <v-col cols="12" class="py-0">
              <form-field
                :field="{
                                    type: 'text',
                                    name: 'description',
                                    label: 'Описание',
                                    icon: 'mdi-text-subject',
                                    counter: '300',
                                    maxLength:300,
                                    rows:5,
                                    rules: ['required'],
                                }"
                v-model="description"
              />
            </v-col>

            <v-col cols="12" class="py-0">
              <form-field
                :field="{
                                    type: 'date-time',
                                    name: 'deadline',
                                    label: 'Дедлайн',
                                    icon: 'mdi-calendar-clock',
                                    rules: [rules.date],
                                }"
                v-model="deadline"
              />
            </v-col>
            <v-col cols="12" class="py-0">
              <v-container class="pa-0">
                <v-row>
                  <v-col cols="12" class="pt-0">
                    <h1 class="subtitle-1 grey--text text--darken-1 ml-1">Время на задачу</h1>
                  </v-col>
                  <v-col cols="12" md="4" class="py-0">
                    <form-field
                      :field="{
                                                type: 'number',
                                                label: 'Дни',
                                                icon: 'mdi-timelapse',
                                                rules: [rules.taskTimeRule, rules.day],
                                                min:'0'
                                            }"
                      v-model="estimateDays"
                      ref="estimateDays"
                    />
                  </v-col>
                  <v-col cols="12" md="4" class="py-0">
                    <form-field
                      :field="{
                                                type: 'number',
                                                label: 'Часы',
                                                rules: [rules.taskTimeRule, rules.hour],
                                                min:'0',
                                                max:'23'
                                                }"
                      v-model="estimateHours"
                      ref="estimateHours"
                    />
                  </v-col>
                  <v-col cols="12" md="4" class="py-0">
                    <form-field
                      :field="{
                                                type: 'number',
                                                label: 'Минуты',
                                                rules: [rules.taskTimeRule, rules.minute],
                                                min:'0',
                                                max:'59'
                                            }"
                      v-model="estimateMinutes"
                      ref="estimateMinutes"
                    />
                  </v-col>
                </v-row>
              </v-container>
            </v-col>
            <v-col cols="12" class="pt-0">
              <attachments :files="selectedFiles" :deleteBtn="true" />
            </v-col>
            <v-dialog v-model="previewImageDialog" width="500">
              <v-card>
                <v-img :src="image" max-width="500" max-height="500" contain></v-img>
              </v-card>
            </v-dialog>
            <v-col class="py-0" cols="9">
              <tasks-watchers :users="users"></tasks-watchers>

              <v-dialog v-model="prioritiesDialog" width="500">
                <template v-slot:activator="{ on:dialog }">
                  <v-tooltip top>
                    <template v-slot:activator="{ on:tooltip }">
                      <v-btn
                        v-on="{ ...tooltip, ...dialog }"
                        text
                        rounded
                        min-width="0"
                        style="min-width:0"
                        class="ma-0 grey--text px-2 text--darken-1"
                      >
                        <v-icon
                          :color="selectedPriority === null ? '' : priorities[selectedPriority].color "
                        >mdi-flag-variant</v-icon>
                      </v-btn>
                    </template>
                    <span>Приоритет</span>
                  </v-tooltip>
                </template>
                <v-card>
                  <v-card-text class="pa-6">
                    <form-field
                      :field="{
                              type: 'select',
                              items: priorities,
                              label: 'Приоритет',
                              props: {'item-text': 'label'},
                              hideDetails: true
                                            }"
                      v-model="selectedPriority"
                    >
                      <template v-slot:item="{ item }">
                        <v-icon :color="item.color" class="mr-2">mdi-flag-variant</v-icon>
                        <span>{{ item.label }}</span>
                      </template>

                      <template v-slot:selection="{ item }">
                        <v-icon class="mr-2" :color="item.color">mdi-flag-variant</v-icon>
                        <span>{{ item.label }}</span>
                      </template>
                    </form-field>
                  </v-card-text>
                </v-card>
              </v-dialog>

              <v-dialog v-model="startTime" width="500" v-if="false">
                <template v-slot:activator="{ on:dialog }">
                  <v-tooltip top>
                    <template v-slot:activator="{ on:tooltip }">
                      <v-btn
                        v-on="{ ...tooltip, ...dialog }"
                        text
                        rounded
                        min-width="0"
                        style="min-width:0"
                        class="ma-0 grey--text px-2 text--darken-1"
                      >
                        <v-icon :color="startTimeValue ? 'primary' : '' ">mdi-clock</v-icon>
                      </v-btn>
                      <input type="hidden" name="startDate" :value="startTimeValue" />
                    </template>
                    <span>Дата</span>
                  </v-tooltip>
                </template>
                <v-card>
                  <v-card-text>
                    <form-field
                      :field="{
                              type: 'date-time',
                              name: 'startDate',
                              label: 'Когда планируете начать ?',
                              icon: 'mdi-clock',
                              }"
                      v-model="startTimeValue"
                    />
                  </v-card-text>
                </v-card>
              </v-dialog>

              <v-dialog v-model="pollDialog" width="500">
                <template v-slot:activator="{ on:dialog }">
                  <v-tooltip top>
                    <template v-slot:activator="{ on:tooltip }">
                      <v-btn
                        v-on="{ ...tooltip, ...dialog }"
                        text
                        rounded
                        min-width="0"
                        style="min-width:0"
                        class="ma-0 grey--text px-2 text--darken-1"
                      >
                        <v-icon>mdi-poll</v-icon>
                      </v-btn>
                    </template>
                    <span>Опрос</span>
                  </v-tooltip>
                </template>
                <poll-create returnEventName="pollAdded" />
              </v-dialog>
              <v-dialog v-model="tagsDialog" width="500">
                <template v-slot:activator="{ on:dialog }">
                  <v-tooltip top>
                    <template v-slot:activator="{ on:tooltip }">
                      <v-btn
                        v-on="{ ...tooltip, ...dialog }"
                        text
                        rounded
                        min-width="0"
                        style="min-width:0"
                        class="ma-0 grey--text px-2 text--darken-1"
                      >
                        <v-icon :color="selectedTags.length ? 'primary' : '' ">mdi-tag</v-icon>
                        <span>
                          <span
                            v-for="(selectedTag, key) in selectedTags"
                            :key="'selectedTag-'+key"
                          >
                            <span class="primary--text title">#</span>
                            {{ selectedTag.name }}
                          </span>
                        </span>
                      </v-btn>
                    </template>
                    <span>Теги</span>
                  </v-tooltip>
                </template>
                <v-card>
                  <v-toolbar dense flat dark color="primary">
                    <v-toolbar-title>Добавить теги к задаче</v-toolbar-title>
                  </v-toolbar>
                  <v-card-text class="pt-5">
                    <v-form ref="tagForm">
                      <form-field
                        :field="{
                                            type: 'combobox',
                                            name: 'tags',
                                            label: 'Выберите тег (Enter для создания нового)',
                                            items: tags,
                                            icon: 'mdi-tag',
                                            multiple: true,
                                            returnObject: true,
                                            hideDetails: true
                                        }"
                        v-model="selectedTags"
                        v-if="auth.position_level.name == 'Руководитель'"
                      />
                      <form-field
                        :field="{
                                            type: 'autocomplete',
                                            name: 'tags',
                                            label: 'Выберите тег',
                                            items: tags,
                                            icon: 'mdi-tag',
                                            multiple: true,
                                            returnObject: true
                                        }"
                        v-else
                        v-model="selectedTags"
                      />
                    </v-form>
                  </v-card-text>
                  <v-card-actions>
                    <v-spacer />
                    <v-btn text color="primary" @click="cancel('tagForm')">отмена</v-btn>
                    <v-btn depressed color="primary" @click="tagsDialog = false">добавить</v-btn>
                  </v-card-actions>
                </v-card>
              </v-dialog>

              <v-btn icon>
                <v-tooltip top>
                  <template v-slot:activator="{ on }">
                    <label class="inputLabel" @click="$refs.inputFiles.value = ''" v-on="on">
                      <input
                        type="file"
                        multiple
                        name="attachments"
                        style="display: none"
                        ref="inputFiles"
                        :accept="availableFileFormats"
                        @change="addFile()"
                      />
                      <v-icon>mdi-paperclip</v-icon>
                    </label>
                  </template>
                  <span>Прикрепить файл</span>
                </v-tooltip>
              </v-btn>

              <task-repeat />

              <v-dialog v-model="startDateTimeDialog" width="500" v-if="!repeatTask">
                  <template v-slot:activator="{ on:dialog }">
                    <v-tooltip top>
                      <template v-slot:activator="{ on:tooltip }">
                        <v-btn
                          v-on="{ ...tooltip, ...dialog }"
                          text
                          rounded
                          min-width="0"
                          style="min-width:0"
                          class="ma-0 grey--text px-2 text--darken-1"
                        >
                          <v-icon>mdi-clock</v-icon>
                        </v-btn>
                      </template>
                      <span>Дата и время начала задачи</span>
                    </v-tooltip>
                  </template>
                <v-card>
                  <v-toolbar flat dark dense color="primary">
                    <v-toolbar-title>Добавить время начала задачи</v-toolbar-title>
                  </v-toolbar>
                  <v-card-text>
                  <v-form ref="startDateTimeForm">
                  <v-row>
                    <v-col cols="6">
                      <v-menu
                        ref="startDateMenu"
                        v-model="startDateMenu"
                        :close-on-content-click="false"
                        :return-value.sync="startDate"
                        transition="scale-transition"
                        offset-y
                        min-width="200px"
                      >
                        <template v-slot:activator="{ on, attrs }">
                          <v-text-field
                            v-model="startDate"
                            label="Дата начала"
                            readonly
                            hide-details="auto"
                            v-bind="attrs"
                            v-on="on"
                            filled
                            rounded
                            :persistent-hint="true"
                            :rules="[rules.required]"
                          ></v-text-field>
                        </template>
                        <v-date-picker v-model="startDate" no-title scrollable>
                          <v-spacer></v-spacer>
                          <v-btn text color="primary" @click="startDateMenu = false">Отмена</v-btn>
                          <v-btn
                            text
                            color="primary"
                            @click="$refs.startDateMenu.save(startDate)"
                          >Добавить</v-btn>
                        </v-date-picker>
                      </v-menu>
                    </v-col>
                    <v-col cols="6">
                      <v-select
                        v-model="startTime"
                        :items="timeRange"
                        label="Время начала"
                        filled
                        rounded
                        hide-details="auto"
                        :rules="[rules.required]"
                      ></v-select>
                    </v-col>
                  </v-row>
                  </v-form>
                  </v-card-text>
                  <v-card-actions>
                    <v-spacer />
                    <v-btn text color="primary" @click="cancelStartDateTime()">Отмена</v-btn>
                    <v-btn depressed color="primary" @click="addStartDateTime()">Добавить</v-btn>
                  </v-card-actions>
                </v-card>
              </v-dialog>
            </v-col>
            <v-col cols="3" class="py-0" align-self="end">
              <v-btn color="primary float-right" @click="submit()" :loading="loading">Добавить</v-btn>
            </v-col>
          </v-row>
        </v-container>
      </v-form>
    </v-card>
  </v-dialog>
</template>

<script>
export default {
  props: ["divisions", "users", "errors"],
  data() {
    return {
      deadline: null,
      searchText: null,

      rules: {
        required: value => !!value || "Обязательное поле",
        notEmptyArray: value => {
          return value.length != 0 || "Обязательное поле";
        },
        taskTimeRule: () => !!this.estimateTime || "Обязательное поле",
        day: val => val < 366 || "Должно быть меньше 366",
        hour: val => val < 24 || "Должно быть меньше 24",
        minute: val => val < 60 || "Должно быть меньше 60",
        date: val => !isNaN(new Date(val.replace(/-/g, "/"))) || "Выберите дату"
      },
      formHasErrors: false,
      csrf_token: window.Laravel.csrf_token,

      reapeatSwitch: false,

      estimateTaskDialog: false,
      estimateDays: null,
      estimateHours: null,
      estimateMinutes: null,

      estimateTime: null,
      assignees: [],

      startDateMenu: false,
      startTime: null,
      startDate: null,
      startDateTimeDialog: false,

      tagsDialog: false,
      selectedTags: [],

      dialog: Array.isArray(this.errors) ? false : true,

      selectedPriority: null,
      prioritiesDialog: false,
      priorities: [
        { id: 0, label: "Низкий", color: "green lighten-3" },
        { id: 1, label: "Средний", color: "blue lighten-3" },
        { id: 2, label: "Высокий", color: "red lighten-3" }
      ],

      reapeatTaskDialog: false,
      reapeatTaskTimeDialog: false,

      intervalNumber: 1,

      selectedInterval: { index: 0, name: "день" },
      selectedIntervals: 0,

      time: null,
      timeIntervals: [
        [
          { index: 0, name: "день" },
          { index: 1, name: "неделю" },
          { index: 2, name: "месяц" },
          { index: 3, name: "год" }
        ],
        [
          { index: 0, name: "дня" },
          { index: 1, name: "недели" },
          { index: 2, name: "месяца" },
          { index: 3, name: "года" }
        ],
        [
          { index: 0, name: "дней" },
          { index: 1, name: "недель" },
          { index: 2, name: "месяцев" },
          { index: 3, name: "лет" }
        ]
      ],
      endTime: { index: 0, label: "никогда" },
      endTimeMenu: [
        { index: 0, label: "никогда" },
        { index: 1, label: "после" },
        { index: 2, label: "в день" }
      ],
      endTimeMenuSizes: [{ xs9: true }, { xs3: true }, { xs4: true }],
      endsAfterTimes: 1,

      endsOnDateDialog: false,
      endsOnDate: null,

      poll: null,
      pollDialog: false,
      startTime: false,
      startTimeValue: null,

      watchers: [],

      selectedAssignee: [],

      selectedRespOrDescrip: [],
      userResponsibilityDescriptions: [],

      responsibilities: [],

      responsibilityDialog: false,
      selectedFiles: [],
      deletedFiles: [],
      image: null,
      previewImageDialog: false,
      description: null,
      error: false,
      loading: false,
      repeatTask: null
    };
  },
  created() {
    this.selectedIntervals = 0;
    Event.listen("pollAdded", data => {
      this.poll = data;
      this.pollDialog = false;
    });
    Event.listen("watchersAdded", data => {
      this.watchers.push(...data);
    });
    Event.listen("selectedAssignee", userIDs => {
      // console.log("new user selected");
      // console.log(userIDs.length);

      this.fetchResponsibilities(userIDs);

      // console.log("user responsibilities");
      // console.log(this.userResponsibilityDescriptions);
    });

    Event.listen("fileError", data => {
      this.error = data;
    });

    Event.listen("repeatTask", data => {
      this.repeatTask = data;
    });
  },
  watch: {
    selectedRespOrDescrip(newVal, oldVal) {
      if (oldVal.length === newVal.length) return;
      let resps = 0;
      newVal.forEach(val => {
        let splitedVal = val.id.split("-");
        if (splitedVal[0] == "resp") {
          resps++;
        } else {
          let responsibility_id = newVal[newVal.length - 1].responsibility_id;
          this.selectedRespOrDescrip = this.selectedRespOrDescrip.filter(
            sel => {
              if ("responsibility_id" in sel) {
                if (sel.responsibility_id == responsibility_id) return sel;
              }
            }
          );
          return true;
        }
      });
      if (resps >= 1) {
        this.selectedRespOrDescrip = [newVal[newVal.length - 1]];
      }
    },
    intervalNumber(value) {
      let reminder = value < 20 ? value : (value - 10) % 10;
      if (reminder == 1) {
        this.selectedIntervals = 0;
      } else if (reminder >= 2 && reminder <= 4) {
        this.selectedIntervals = 1;
      } else {
        this.selectedIntervals = 2;
      }
    },
    estimateTime(value) {
      this.$refs["estimateDays"].validate(true);
      this.$refs["estimateHours"].validate(true);
      this.$refs["estimateMinutes"].validate(true);
    },
    estimateDays(value) {
      this.estimateTime = this.toMilliseconds(
        value,
        this.estimateHours,
        this.estimateMinutes
      );
    },
    estimateHours(value) {
      this.estimateTime = this.toMilliseconds(
        this.estimateDays,
        value,
        this.estimateMinutes
      );
    },
    estimateMinutes(value) {
      this.estimateTime = this.toMilliseconds(
        this.estimateDays,
        this.estimateHours,
        value
      );
    }
  },
  methods: {
    fetchResponsibilities(userIDs) {
      axios
        .post(this.appPath(`api/users/responsibilities`), {
          userIDs: userIDs
        })
        .then(response => {
          this.responsibilities = response.data;
          this.responsibilities.forEach(resp => {
            resp.id = "resp-" + resp.id;
            resp.descriptions.forEach(desc => {
              desc.id = "desc-" + desc.id;
            });
          });

          this.userResponsibilityDescriptions = [];
          response.data.forEach(resp => {
            this.userResponsibilityDescriptions.push(resp);
            resp.descriptions.forEach(descr => {
              this.userResponsibilityDescriptions.push(descr);
            });
          });
        });
    },
    toMilliseconds(days, hours, minutes) {
      return days * 86400000 + hours * 3600000 + minutes * 60000;
    },
    submit() {
      this.loading = true;
      const FORM = this.$refs.form;
      let formData = new FormData();
      let responsibilityDescriptions = this.selectedRespOrDescrip.map(resDes => resDes.id.split("-")[1]);
      let startDateTime = this.moment(`${this.moment(this.startDate)
                          .local()
                          .format("YYYY-MM-DD")} ${this.startTime}`)
                          .local()
                          .valueOf()

      formData.append("assignees", JSON.stringify(this.assignees));
      formData.append("description", this.description);
      formData.append("estimatedTaskTime", JSON.stringify(this.estimateTime));
      formData.append("deadline", this.deadlineWithTz);
      formData.append("watchers", JSON.stringify(this.watchers));
      formData.append("responsibility_description",JSON.stringify(responsibilityDescriptions));
      formData.append("poll", JSON.stringify(this.poll));
      formData.append("priority", JSON.stringify(this.selectedPriority));
      formData.append("newTags", JSON.stringify(this.newTags));
      formData.append("repeatTask", JSON.stringify(this.repeatTask));
      formData.append("existingTags", JSON.stringify(this.existingTags));
      formData.append("startDateTime", JSON.stringify(startDateTime));

      this.selectedFiles.forEach((file, index) => {
        formData.append("attachments[" + index + "]", file);
      });

      if (FORM.validate() && !this.error) {
        axios
          .post("/api/tasks", formData, {
            headers: {
              "Content-Type": "multipart/form-data"
            }
          })
          .then(res => {
            this.loading = false;
            window.location.reload();
          });
      } else {
        this.loading = false;
      }
    },
    cancel(form) {
      const localForm = this.$refs[form];

      localForm.reset();

      this.tagsDialog = false;
    },
    addFile() {
      let files = this.$refs.inputFiles.files;
      this.selectedFiles.push(...files);
    },
    addStartDateTime() {
      const FORM = this.$refs.startDateTimeForm;
      if(FORM.validate()) {
        this.startDateTimeDialog = false;
      }
    },
    cancelStartDateTime() {
      const FORM = this.$refs.startDateTimeForm;
      FORM.reset();
      this.startDateTimeDialog = false;
    }
  },
  computed: {
    deadlineWithTz() {
      let offset = new Date().getTimezoneOffset();
      return this.moment(this.deadline, "YYYY-MM-DD HH:mm")
        .utcOffset(offset)
        .format("YYYY-MM-DD HH:mm");
    },
    estimatedTaskTime() {
      return {
        days: this.estimateDays,
        hours: this.estimateHours,
        minutes: this.estimateMinutes
      };
    },
    newTags() {
      const filteredNewTags = this.selectedTags.filter(tag => tag.id === -1);
      return this.pluck(filteredNewTags, "name");
    },
    existingTags() {
      const filteredExistingTags = this.selectedTags.filter(
        tag => tag.id !== -1
      );
      return this.pluck(filteredExistingTags, "id");
    },
    tags() {
      return this.loadDivisionTags();
    },
    timeRange() {
      let times = [];
      let date = this.moment(new Date("2020-07-01 00:00"));

      while (date.local().format("HH:mm") !== "23:00") {
        date.add(15, "m");
        times.push(date.local().format("HH:mm"));
      }
      return times;
    }
  }
};
</script>

<style>
.priority .v-toolbar__content {
  padding-left: 0;
}
.priority .v-btn--active {
  background-color: #b8cf41;
}
.priority .v-btn--active .v-btn__content {
  color: white;
}
.inputLabel {
  cursor: pointer;
}
</style>
