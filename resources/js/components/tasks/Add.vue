<template>
  <v-dialog v-model="dialog" max-width="700">
    <template v-slot:activator="{ on }">
      <v-fab-transition>
        <v-btn v-on="on" dark fab fixed bottom right large color="primary">
          <v-icon>mdi-plus</v-icon>
        </v-btn>
      </v-fab-transition>
    </template>

    <v-card>
      <v-form action="/tasks" method="post" ref="form">
        <input type="hidden" name="_token" :value="csrf_token" />

        <v-toolbar flat color="primary" dark>
          <v-toolbar-title class="font-weight-regular">Новая задача</v-toolbar-title>
        </v-toolbar>

        <v-container>
          <v-layout row wrap>
            <v-flex xs12 v-if="!Array.isArray(errors)">
              <h1 class="subheading red--text text--darken-1 ml-1">Форма заполнена не верно</h1>
            </v-flex>
            <v-flex xs12>
              <form-field
                :field="{
                                type: 'string',
                                name: 'title',
                                label: 'Название',
                                icon: 'mdi-rename-box',
                                rules: ['required'],
                            }"
              />
            </v-flex>
            <v-flex xs12>
              <form-field
                :field="{
                                type: 'text',
                                name: 'description',
                                label: 'Описание',
                                icon: 'mdi-text-subject',
                            }"
              />
            </v-flex>
            <v-flex xs12>
              <form-field
                :field="{
                                type: 'users',
                                users: users,
                                name: 'assignees',
                                label: 'Исполнители',
                                icon: 'mdi-account-tie',
                                hint: 'У каждого исполнителя будет своя отдельная задача',
                                rules: ['required']
                            }"
              />
            </v-flex>
            <v-flex xs12>
              <form-field
                :field="{
                                type: 'date-time',
                                name: 'deadline',
                                label: 'Дедлайн',
                                icon: 'mdi-calendar-clock',
                                rules: ['required']
                            }"
              />
            </v-flex>
            <v-flex xs-12>
              <input type="hidden" name="estimatedTaskTime" :value="estimateTime" />

              <v-container grid-list-xl class="pa-0">
                <v-layout row wrap>
                  <v-flex xs12 class="mb-0 pb-0">
                    <h1 class="subtitle-1 grey--text text--darken-1 ml-1">Время на задачу</h1>
                  </v-flex>
                  <v-flex xs12 md4 class="pt-0">
                    <form-field
                      :field="{
                                            type: 'number',
                                            label: 'Дни',
                                            icon: 'mdi-timelapse',
                                            rules: [rules.taskTimeRule]
                                        }"
                      v-model="estimateDays"
                      ref="estimateDays"
                    />
                  </v-flex>
                  <v-flex xs12 md4 class="pt-0">
                    <form-field
                      :field="{
                                            type: 'number',
                                            label: 'Часы',
                                            rules: [rules.taskTimeRule]
                                        }"
                      v-model="estimateHours"
                      ref="estimateHours"
                    />
                  </v-flex>
                  <v-flex xs12 md4 class="pt-0">
                    <form-field
                      :field="{
                                            type: 'number',
                                            label: 'Минуты',
                                            rules: [rules.taskTimeRule]
                                        }"
                      v-model="estimateMinutes"
                      ref="estimateMinutes"
                    />
                  </v-flex>
                </v-layout>
              </v-container>
            </v-flex>
          </v-layout>
        </v-container>

        <v-card-actions>
          <v-flex>
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
                <v-card-text>
                  <form-field
                    :field="{
                                        type: 'select',
                                        items: priorities,
                                        label: 'Приоритет',
                                        props: {'item-text': 'label'}
                                    }"
                    v-model="selectedPriority"
                  >
                    <template v-slot:item="{ item, index }">
                      <v-icon :color="item.color" class="mr-2">mdi-flag-variant</v-icon>
                      <span>{{ item.label }}</span>
                    </template>

                    <template v-slot:selection="{ item, index }">
                      <v-icon class="mr-2" :color="item.color">mdi-flag-variant</v-icon>
                      <span>{{ item.label }}</span>
                    </template>
                  </form-field>
                </v-card-text>
              </v-card>
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
                        <span v-for="(selectedTag, key) in selectedTags" :key="'selectedTag-'+key">
                          <span class="primary--text title">#</span>
                          {{ selectedTag.name }}
                        </span>
                      </span>
                    </v-btn>

                    <input type="hidden" name="newTags" :value="JSON.stringify(newTags)" />
                    <input type="hidden" name="existingTags" :value="JSON.stringify(existingTags)" />
                  </template>
                  <span>Теги</span>
                </v-tooltip>
              </template>
              <v-card>
                <v-card-text>
                  <form-field
                    :field="{
                                        type: 'combobox',
                                        name: 'tags',
                                        label: 'Теги',
                                        items: tags,
                                        icon: 'mdi-tag',
                                        multiple: true
                                    }"
                    v-model="selectedTags"
                  />
                </v-card-text>
              </v-card>
            </v-dialog>

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
                  <v-layout row>
                    <v-flex xs3>
                      <v-subheader class="justify-end">каждый</v-subheader>
                    </v-flex>
                    <v-flex xs2>
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
                    </v-flex>
                    <v-flex xs7>
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
                    </v-flex>
                  </v-layout>
                  <v-layout row>
                    <v-flex xs3>
                      <v-subheader class="justify-end">в</v-subheader>
                    </v-flex>
                    <v-flex xs9>
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
                    </v-flex>
                  </v-layout>
                  <v-layout row>
                    <v-flex xs3>
                      <v-subheader class="justify-end">заканчивается</v-subheader>
                    </v-flex>
                    <v-flex v-bind="endTimeMenuSizes[endTime.index]">
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
                    </v-flex>

                    <v-flex v-if="endTime.index == 1" xs3>
                      <v-text-field
                        type="number"
                        v-model="endsAfterTimes"
                        min="1"
                        single-line
                        solo
                        rounded
                        filled
                      ></v-text-field>
                    </v-flex>
                    <v-flex v-if="endTime.index == 1" xs3>
                      <v-subheader>раза</v-subheader>
                    </v-flex>

                    <v-flex v-if="endTime.index == 2" xs5>
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
                    </v-flex>
                  </v-layout>
                </v-container>
              </v-card>
            </v-dialog>
          </v-flex>

          <v-spacer></v-spacer>

          <v-btn color="primary" type="submit" @click="submit">Добавить</v-btn>
        </v-card-actions>
      </v-form>
    </v-card>
  </v-dialog>
</template>

<script>
export default {
  props: ["users", "errors", "tags"],
  data() {
    return {
      searchText: null,

      rules: {
        required: value => !!value || "Обязательное поле",
        notEmptyArray: value => {
          return value.length != 0 || "Обязательное поле";
        },
        taskTimeRule: () => !!this.estimateTime || "Обязательное поле"
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
      endsOnDate: null
    };
  },
  created() {
    this.selectedIntervals = 0;
  },
  watch: {
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
    toMilliseconds(days, hours, minutes) {
      return days * 86400000 + hours * 3600000 + minutes * 60000;
    },
    submit(e) {
      this.formHasErrors = false;
      this.formHasErrors = !this.$refs.form.validate();

      if (!this.formHasErrors) return;

      e.preventDefault();
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
