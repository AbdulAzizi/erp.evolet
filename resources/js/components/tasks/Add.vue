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
      <v-form action="/tasks" method="post" ref="form">
        <input type="hidden" name="_token" :value="csrf_token" />

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
              />
            </v-col>
            <v-col cols="12" class="pb-0">
              <input
                v-if="selectedRespOrDescrip.length"
                type="hidden"
                name="responsibility_description"
                :value="JSON.stringify(selectedRespOrDescrip.map(resDes=>{return resDes.id.split('-')[1];}))"
              />
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
              />
            </v-col>
            <v-col cols="12" class="py-0">
              <input type="hidden" name="estimatedTaskTime" :value="estimateTime" />

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
                      <input type="hidden" name="priority" :value="selectedPriority" />
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
                      <input type="hidden" name="start_date" :value="startTimeValue" />
                    </template>
                    <span>Дата</span>
                  </v-tooltip>
                </template>
                <v-card>
                  <v-card-text>
                    <form-field
                      :field="{
                                                type: 'date-time',
                                                name: 'start_date',
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

                      <input type="hidden" name="newTags" :value="JSON.stringify(newTags)" />
                      <input
                        type="hidden"
                        name="existingTags"
                        :value="JSON.stringify(existingTags)"
                      />
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

              <input type="hidden" name="poll" :value="JSON.stringify(poll)" />

              <v-dialog v-model="reapeatTaskDialog" width="600" v-if="false">
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
                        <v-icon>mdi-repeat</v-icon>
                      </v-btn>
                    </template>
                    <span>Повторение</span>
                  </v-tooltip>
                </template>

                <v-card class="grey lighten-3">
                  <v-container grid-list-md>
                    <v-row row>
                      <v-col xs3>
                        <v-subheader class="justify-end">каждый</v-subheader>
                      </v-col>
                      <v-col xs2>
                        <v-text-field
                          type="number"
                          v-model="intervalNumber"
                          min="1"
                          single-line
                          solo
                          class="text-xs-center"
                          rounded
                          filled
                        ></v-text-field>
                      </v-col>
                      <v-col xs7>
                        <v-select
                          v-model="selectedInterval"
                          :items="timeIntervals[ selectedIntervals ]"
                          item-value="index"
                          item-text="name"
                          return-object
                          solo
                          rounded
                          filled
                        ></v-select>
                      </v-col>
                    </v-row>
                    <v-row row>
                      <v-col xs3>
                        <v-subheader class="justify-end">в</v-subheader>
                      </v-col>
                      <v-col xs9>
                        <form-field
                          :field="{
                            type: 'time',
                            name: 'time',
                            label: 'Выберите время',
                            props: {
                            textField: {
                                'readonly': true,
                                'single-line': true,
                                solo: true,
                                'single-line': true,
                                'prepend-inner-icon': 'mdi-clock-outline'
                            }
                            }
                        }"
                        />
                      </v-col>
                    </v-row>
                    <v-row row>
                      <v-col xs3>
                        <v-subheader class="justify-end">заканчивается</v-subheader>
                      </v-col>
                      <v-col v-bind="endTimeMenuSizes[endTime.index]">
                        <v-select
                          v-model="endTime"
                          :items="endTimeMenu"
                          item-value="index"
                          item-text="label"
                          return-object
                          solo
                          rounded
                          filled
                        ></v-select>
                      </v-col>

                      <v-col v-if="endTime.index == 1" xs3>
                        <v-text-field
                          type="number"
                          v-model="endsAfterTimes"
                          min="1"
                          single-line
                          solo
                          rounded
                          filled
                        ></v-text-field>
                      </v-col>
                      <v-col v-if="endTime.index == 1" xs3>
                        <v-subheader>раза</v-subheader>
                      </v-col>

                      <v-col v-if="endTime.index == 2" xs5>
                        <form-field
                          :field="{
                            type: 'date',
                            name: 'endsOnDate',
                            label: 'Выберите день',
                            props: {
                            textField: {
                                'readonly': true,
                                'single-line': true,
                                solo: true,
                                'single-line': true,
                                'prepend-inner-icon': 'event'
                                }
                            }
                        }"
                        />
                      </v-col>
                    </v-row>
                  </v-container>
                </v-card>
              </v-dialog>
            </v-col>
            <v-col cols="3" class="py-0" align-self="end">
              <v-btn color="primary float-right" type="submit" @click="submit">Добавить</v-btn>
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

      tagsDialog: false,
      selectedTags: [],

      dialog: Array.isArray(this.errors) ? false : true,

      selectedPriority: null,
      prioritiesDialog: false,
      priorities: [
        { id: 0, label: "Низкий", color: "light-blue lighten-4" },
        { id: 1, label: "Средний", color: "amber lighten-1" },
        { id: 2, label: "Высокий", color: "red" }
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

      selectedAssignee: [],

      selectedRespOrDescrip: [],
      userResponsibilityDescriptions: [],

      responsibilities: [],

      responsibilityDialog: false
    };
  },
  created() {
    this.selectedIntervals = 0;
    Event.listen("pollAdded", data => {
      this.poll = data;
      this.pollDialog = false;
    });
    Event.listen("selectedAssignee", userIDs => {
      // console.log("new user selected");
      // console.log(userIDs.length);

      this.fetchResponsibilities(userIDs);

      // console.log("user responsibilities");
      // console.log(this.userResponsibilityDescriptions);
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
    submit(e) {
      this.formHasErrors = false;
      this.formHasErrors = !this.$refs.form.validate();

      if (!this.formHasErrors) return;

      e.preventDefault();
    },
    cancel(form) {
      const localForm = this.$refs[form];

      localForm.reset();

      this.tagsDialog = false;
    }
  },
  computed: {
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
</style>
