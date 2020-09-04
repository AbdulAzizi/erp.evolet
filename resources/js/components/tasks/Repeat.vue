<template>
  <v-dialog v-model="reapeatTaskDialog" width="600">
    <template v-slot:activator="{ on:dialog }">
      <v-tooltip top v-if="!task">
        <template v-slot:activator="{ on:tooltip }">
          <v-btn
            v-on="{ ...tooltip, ...dialog }"
            text
            rounded
            min-width="0"
            style="min-width:0"
            class="ma-0 px-2"
            :color="action ? 'primary' : 'grey darken-2'"
            @click="start_date = moment().startOf('day').format('YYYY-MM-DD')"
          >
            <v-icon>mdi-repeat</v-icon>
          </v-btn>
        </template>
        <span>Повторение</span>
      </v-tooltip>
      <div v-if="task.repeat_count > 0 ">
        <v-list-item v-if="!edit">
          <v-list-item-avatar>
            <v-icon>mdi-repeat</v-icon>
          </v-list-item-avatar>
          <v-list-item-content>
            <v-list-item-title>{{ task.repeat.end_date ? `До ${moment(task.repeat.end_date).local().format("DD-MM-Y")}` : "Всегда" }}</v-list-item-title>
            <v-list-item-subtitle>{{ repeatWeekdays }}</v-list-item-subtitle>
          </v-list-item-content>
        </v-list-item>
        <v-list-item v-if="edit">
          <v-list-item-avatar>
            <v-icon v-on="dialog" @click="prepareData()">mdi-pencil</v-icon>
          </v-list-item-avatar>
          <v-list-item-content>
            <v-list-item-title>{{ task.repeat.end_date ? moment(task.repeat.end_date).local().format("DD-MM-Y") : "Всегда" }}</v-list-item-title>
            <v-list-item-subtitle>{{ repeatWeekdays }}</v-list-item-subtitle>
          </v-list-item-content>
        </v-list-item>
      </div>
      <div v-if="task.repeat_count == 0">
        <v-list-item v-if="edit">
          <v-list-item-avatar>
            <v-icon v-on="dialog">mdi-plus</v-icon>
          </v-list-item-avatar>
          <v-list-item-content>
            <v-list-item-title>Сделать повторяющимся</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </div>
    </template>

    <v-card>
      <v-toolbar dark dense flat color="primary">
        <v-toolbar-title>Повторение задачи</v-toolbar-title>
      <v-spacer />
      <v-btn icon v-if="edit && task.repeat_count > 0" @click="deleteRepeat()">
        <v-icon>mdi-delete</v-icon>
      </v-btn>
      </v-toolbar>
      <v-card-text class="pb-0 pt-3">
        <v-form ref="form">
          <v-row>
            <v-col cols="3">
              <v-select
                v-model="range"
                filled
                rounded
                :items="rangeItems"
                label="Кажд."
                hide-details="auto"
                :rules="rules"
              ></v-select>
            </v-col>
            <v-col cols="4">
              <v-select
                v-model="period"
                filled
                rounded
                :items="periodItems"
                item-value="val"
                item-text="text"
                label="Период"
                hide-details="auto"
                :rules="rules"
              ></v-select>
            </v-col>
            <v-col cols="5">
              <v-select
                v-model="action"
                filled
                rounded
                :items="actionItems"
                item-value="id"
                item-text="text"
                label="При завершении"
                hide-details="auto"
                :rules="rules"
              ></v-select>
            </v-col>
            <v-col cols="12">
              <v-select
                v-model="weekDays"
                :items="weekDayItems"
                item-value="id"
                item-text="day"
                label="Выберите дни недели"
                filled
                rounded
                multiple
                small-chips
                hide-details
              >
                <template v-slot:selection="{ item }">
                  <v-chip color="primary" small class="mt-3 mb-1">{{ item.day }}</v-chip>
                </template>
              </v-select>
            </v-col>
            <v-col cols="4">
              <v-menu
                ref="startDateMenu"
                v-model="startDateMenu"
                :close-on-content-click="false"
                :return-value.sync="start_date"
                transition="scale-transition"
                offset-y
                min-width="200px"
              >
                <template v-slot:activator="{ on, attrs }">
                  <v-text-field
                    v-model="start_date"
                    label="Дата начала"
                    readonly
                    hide-details="auto"
                    v-bind="attrs"
                    v-on="on"
                    filled
                    rounded
                    :persistent-hint="true"
                  ></v-text-field>
                </template>
                <v-date-picker v-model="start_date" no-title scrollable>
                  <v-spacer></v-spacer>
                  <v-btn text color="primary" @click="startDateMenu = false">Отмена</v-btn>
                  <v-btn text color="primary" @click="$refs.startDateMenu.save(start_date)">Добавить</v-btn>
                </v-date-picker>
              </v-menu>
            </v-col>
            <v-col cols="4">
              <v-select v-model="startTime" :items="timeRange" label="Время начала" filled rounded></v-select>
            </v-col>
            <v-col cols="4">
              <v-menu
                ref="menu"
                v-model="menu"
                :close-on-content-click="false"
                :return-value.sync="end_date"
                transition="scale-transition"
                offset-y
                min-width="200px"
              >
                <template v-slot:activator="{ on, attrs }">
                  <v-text-field
                    v-model="end_date"
                    label="Дата завершения"
                    readonly
                    hide-details="auto"
                    v-bind="attrs"
                    v-on="on"
                    filled
                    rounded
                    :persistent-hint="true"
                    hint="*Необязательное поле"
                  ></v-text-field>
                </template>
                <v-date-picker v-model="end_date" no-title scrollable>
                  <v-spacer></v-spacer>
                  <v-btn text color="primary" @click="menu = false">Отмена</v-btn>
                  <v-btn text color="primary" @click="$refs.menu.save(end_date)">Добавить</v-btn>
                </v-date-picker>
              </v-menu>
            </v-col>
          </v-row>
        </v-form>
      </v-card-text>
      <v-card-actions>
        <v-spacer />
        <v-btn text color="primary" @click="cancel()">Отмена</v-btn>
        <v-btn v-if="!edit || task.repeat_count == 0" color="primary" @click="create()">Создать</v-btn>
        <v-btn v-else color="primary" @click="editRepeatTask()">Изменить</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>
<script>
export default {
  props: {
    task: {
      required: false,
      default: false
    },
    edit: {
      required: false,
      default: false
    }
  },
  data() {
    return {
      range: 1,
      period: "day",
      startDateMenu: false,
      menu: false,
      action: null,
      end_date: null,
      startTime: "08:00",
      weekDays: [1, 2, 3, 4, 5, 6, 0],
      reapeatTaskDialog: false,
      rules: [v => !!v || "Обязательное поле"],
      timeError: false,
      localTask: this.task,
      start_date: null
    };
  },
  methods: {
    create() {
      let data = {
        taskId: this.task ? this.task.id : null,
        range: this.range,
        period: this.period,
        action: this.action,
        end_date: this.end_date,
        weekDays: this.weekDays,
        startTime: this.moment(
          `${this.moment(this.start_date)
            .local()
            .format("YYYY-MM-DD")} ${this.startTime}`
        )
          .local()
          .valueOf()
      };

        if (this.task) {
          // Add repeat to existing task
          this.addRepeatToTask(data);
        } else {
          Event.fire("repeatTask", data);
          this.reapeatTaskDialog = false;
        }
      
    },
    addRepeatToTask(data) {
      axios.post("/api/repeatition", data).then(res => res.data);
    },
    editRepeatTask() {
      let data = {
        range: this.range,
        period: this.period,
        action: this.action,
        end_date: this.end_date,
        weekdays: JSON.stringify(this.weekDays),
        startTime: this.moment(
          `${this.moment(this.start_date)
            .local()
            .format("YYYY-MM-DD")} ${this.startTime}`
        )
          .local()
          .valueOf(),
        taskId: this.task.id
      };

      axios
        .post(`/api/repeatition/${this.task.repeat.id}`, data)
        .then(res => {
          this.reapeatTaskDialog = false;
          this.task.repeat = res.data;
          Event.fire("notify", ["Изменения успешно выполнены"]);
          // console.log(res.data);
        });
    },
    cancel() {
      this.reapeatTaskDialog = false;
      this.action = null;
      Event.fire("repeatTask", null);
    },
    prepareData() {
      this.range = this.task.repeat.range;
      this.period = this.task.repeat.range_period;
      this.action = this.task.repeat.action;
      this.end_date = this.task.repeat.end_date;
      this.startTime = this.task.start_date ? this.moment(Number(this.task.start_date)).format(
        "HH:mm"
      ) : '08:00';

      this.start_date = this.task.start_date ? this.moment(Number(this.task.start_date)).format("YYYY-MM-DD") : this.moment().local().format("YYYY-MM-DD");
      this.weekDays = JSON.parse(this.task.repeat.weekdays);
    },
    deleteRepeat() {
      axios.post(`/api/repeatition/${this.task.id}/delete`).then(res => {
        this.reapeatTaskDialog = false;
        this.localTask = res.data;
      });
    }
  },
  computed: {
    rangeItems() {
      let range = [];
      for (let i = 1; i < 100; i++) {
        range.push(i);
      }
      return range;
    },
    periodItems() {
      return this.range === 1
        ? [
            {
              val: "day",
              text: "день"
            },
            {
              val: "week",
              text: "неделю"
            },
            {
              val: "month",
              text: "месяц"
            },
            {
              val: "year",
              text: "год"
            }
          ]
        : [
            {
              val: "day",
              text: "дн."
            },
            {
              val: "week",
              text: "нед."
            },
            {
              val: "month",
              text: "мес."
            },
            {
              val: "year",
              text: "г."
            }
          ];
    },
    actionItems() {
      return [
        {
          id: 1,
          text: "Создать задачу"
        },
        {
          id: 2,
          text: "Изменить статус"
        }
      ];
    },
    weekDayItems() {
      return [
        {
          id: 1,
          day: "Понедельник"
        },
        {
          id: 2,
          day: "Вторник"
        },
        {
          id: 3,
          day: "Среда"
        },
        {
          id: 4,
          day: "Четверг"
        },
        {
          id: 5,
          day: "Пятница"
        },
        {
          id: 6,
          day: "Суббота"
        },
        {
          id: 0,
          day: "Воскресенье"
        }
      ];
    },
    timeRange() {
      let times = [];
      let date = this.moment(new Date("2020-07-01 00:00"));

      while (date.local().format("HH:mm") !== "23:00") {
        date.add(15, "m");
        times.push(date.local().format("HH:mm"));
      }
      return times;
    },
    repeatWeekdays() {
      let res = [];
      if (this.task.repeat) {
        let daysInNum = JSON.parse(this.task.repeat.weekdays);
        let daysInStr = [
          { id: 0, text: "сб" },
          { id: 1, text: "пн" },
          { id: 2, text: "вт" },
          { id: 3, text: "ср" },
          { id: 4, text: "чт" },
          { id: 5, text: "пт" },
          { id: 6, text: "вс" }
        ];
        daysInStr.forEach((item, index) => {
          daysInNum.forEach(element => {
            if (element === item.id) {
              res.push(item.text);
            }
          });
        });
        return res.join(", ");
      }
    }
  }
};
</script>