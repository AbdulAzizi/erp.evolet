<template>
  <div style="height: 70vh">
    <v-sheet height="64">
      <v-toolbar flat color="white">
        <v-btn outlined class="mr-4" color="grey darken-2" @click="setToday">Сегодня</v-btn>
        <v-btn fab text small color="grey darken-2" @click="prev">
          <v-icon small>mdi-chevron-left</v-icon>
        </v-btn>
        <v-btn fab text small color="grey darken-2" @click="next">
          <v-icon small>mdi-chevron-right</v-icon>
        </v-btn>
        <v-toolbar-title v-if="$refs.calendar">{{ $refs.calendar.title }}</v-toolbar-title>
        <v-spacer></v-spacer>
        <v-menu bottom right>
          <template v-slot:activator="{ on, attrs }">
            <v-btn outlined color="grey darken-2" v-bind="attrs" v-on="on">
              <span>{{ typeToLabel[type] }}</span>
              <v-icon right>mdi-menu-down</v-icon>
            </v-btn>
          </template>
          <v-list>
            <v-list-item @click="type = 'day'">
              <v-list-item-title>День</v-list-item-title>
            </v-list-item>
            <v-list-item @click="type = 'week'">
              <v-list-item-title>Неделя</v-list-item-title>
            </v-list-item>
            <v-list-item @click="type = 'month'">
              <v-list-item-title>Месяц</v-list-item-title>
            </v-list-item>
          </v-list>
        </v-menu>
      </v-toolbar>
    </v-sheet>

    <v-calendar
      style="max-height: 80vh"
      ref="calendar"
      v-model="focus"
      :event-color="getEventColor"
      :events="events"
      color="primary"
      :type="type"
      :first-interval="0"
      :interval-format="intervalTime"
      @click:date="showEventCreateDialog"
      @mousedown:time="showEventCreateDialog"
      @mousemove:time="mouseMove"
      @mousedown:event="startDrag"
      @mouseup:time="endDrag"
    >
      <template #event="{ event, timed }">
        <p class="ma-0 pl-2 float-left">
          <span
            class="font-weight-bold"
          >{{event.name.length > 10 ? event.name.substring(0, 9) + '...' : event.name}},</span>
          {{moment(event.start).format('HH:mm')}}
        </p>
        <v-btn
          icon
          v-if="!timed"
          x-small
          dark
          class="float-right"
          @mousedown.stop="displayEventDeleteDialog(event)"
          :disabled="event.repeat"
        >
          <v-icon v-if="!event.repeat">mdi-delete</v-icon>
          <v-icon style="color: white !important" v-else>mdi-repeat</v-icon>
        </v-btn>
        <v-menu v-else offset-x absolute transition="slide-x-transition">
          <template v-slot:activator="{ on }">
            <v-btn dark class="float-right" icon x-small v-on.stop="on">
              <v-icon>mdi-dots-vertical</v-icon>
            </v-btn>
          </template>
          <v-list dense>
            <v-list-item :href="`/tasks/${event.id}`">
              <v-list-item-title>Перейти</v-list-item-title>
            </v-list-item>
            <v-list-item @click="displayEventDeleteDialog(event)">
              <v-list-item-title>Удалить</v-list-item-title>
            </v-list-item>
          </v-list>
        </v-menu>
      </template>
    </v-calendar>
    <v-dialog width="500" v-model="createEventDialog">
      <v-card>
        <v-toolbar dark flat dense color="primary">
          <v-toolbar-title>Добавить задачу в календарь</v-toolbar-title>
        </v-toolbar>
        <v-card-text class="pb-0 px-3">
          <v-row>
            <v-col cols="8" class="pb-0">
              <v-select
                v-model="eventTask"
                :items="tasks"
                item-text="description"
                item-value="id"
                label="Выберите задачу"
                rounded
                filled
                dense
                return-object
              ></v-select>
            </v-col>
            <v-col cols="4">
              <v-select v-model="startTime" :items="timeRange" filled dense rounded label="Время"></v-select>
            </v-col>
          </v-row>
        </v-card-text>
        <v-card-actions class="pt-0 pb-3 px-3">
          <v-spacer />
          <v-btn text color="primary" @click="createEventDialog = false">Отмена</v-btn>
          <v-btn color="primary" @click="createEvent()">Добавить</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-dialog v-model="deleteEventDialog" width="500">
      <v-card v-if="eventForDelete">
        <v-card-title>Хотите убрать из календаря задачу {{eventForDelete.name}} ?</v-card-title>
        <v-card-actions>
          <v-spacer />
          <v-btn text color="primary" @click="deleteEventDialog = false">Нет</v-btn>
          <v-btn text color="red lighten-1" @click="deleteEvent(eventForDelete)">Да</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-dialog v-model="isRepeatDialog">
      <v-card>
        <v-card-title>Изменятся и все последующие повторяющиеся задачи</v-card-title>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
export default {
  data: () => ({
    tasks: null,
    eventTask: null,
    changeEvent: null,
    drag: true,
    calendarDate: null,
    createEventDialog: false,
    deleteEventDialog: false,
    isRepeatDialog: false,
    eventForDelete: null,
    repeatTasks: [],
    startTime: "08:30",
    focus: "",
    type: "month",
    typeToLabel: {
      month: "Месяц",
      week: "Неделя",
      day: "День"
    },
    events: [],
    colors: [
      "#2196F3",
      "#3F51B5",
      "#673AB7",
      "#00BCD4",
      "#4CAF50",
      "#FF9800",
      "#757575"
    ],
    repeatColor: null
  }),
  mounted() {
    this.$refs.calendar.checkChange();
    this.loadTasksForCalendarEvents();
    this.repeatColor = this.colors[this.rnd(0, this.colors.length - 1)];
  },
  methods: {
    getEventColor(event) {
      return event.color;
    },
    intervalTime(interval) {
      return interval.time;
    },
    setToday() {
      this.focus = "";
    },
    prev() {
      this.$refs.calendar.prev();
    },
    next() {
      this.$refs.calendar.next();
    },
    // Function for picking random colors for events
    rnd(a, b) {
      return Math.floor((b - a + 1) * Math.random()) + a;
    },
    // Passing milliseconds to calculate end date from planned time
    calculateEndDate(start, end) {
      return this.moment(Number(start) + Number(end)).format(
        "YYYY-MM-DD HH:mm"
      );
    },
    // Start dragging the event
    startDrag(event) {
      if (event.timed && this.drag) {
        this.changeEvent = event.event;
        this.drag = true;
      } else {
        window.location.href = "/tasks/" + event.event.id;
      }
    },
    // Change Event time locally on mouse move
    mouseMove(tms) {
      if (this.changeEvent && !this.changeEvent.repeat) {
        let startDate = `${tms.date} ${this.roundTime(tms)}`;
        let endDate = this.calculateEndDate(
          this.moment(this.changeEvent.start).valueOf(),
          this.changeEvent.planned_time
        );

        this.changeEvent.start = startDate;
        this.changeEvent.end = endDate;
      }
    },
    displayEventDeleteDialog(event) {
      this.eventForDelete = event;
      this.deleteEventDialog = true;
    },
    endDrag(event) {
      this.updateEvent(this.changeEvent);
      this.changeEvent = null;
    },

    // Round time to nearest quarter
    roundTime(time) {
      let roundMinute = (Math.round(time.minute / 15) * 15) % 60;
      let minute = roundMinute === 0 ? `${roundMinute}0` : roundMinute;
      let hour = time.hour < 10 ? `0${time.hour}` : time.hour;

      if (time.minute > 45) {
        minute = "00";
        hour = time.hour <= 8 ? `0${time.hour + 1}` : time.hour + 1;
      }

      return `${hour}:${minute}`;
    },
    // Show event create dialog if event is not dragging
    showEventCreateDialog(item) {
      if (!this.changeEvent) {
        if (!this.tasks) {
          this.loadUserTasks();
        }
        this.createEventDialog = true;
        this.calendarDate = item.date;
        this.startTime = item.time ? this.roundTime(item) : "08:30"; // set time by default to 8:30 start of work day
      }
    },
    // Load user tasks where user is responsible
    loadUserTasks() {
      axios.get("/api/userResponsibleTasks").then(res => {
        this.tasks = res.data;
      });
    },
    createEvent() {
      let startDate = this.moment(`${this.calendarDate} ${this.startTime}`)
        .local()
        .valueOf();

      axios
        .post("/api/createCalendarEvent", {
          id: this.eventTask.id,
          startDate: startDate
        })
        .then(res => {
          this.pushEvent(res.data);
          this.createEventDialog = false;
          this.loadUserTasks();
          Event.fire("notify", [
            `${res.data.description} успешно добавлен в календарь`
          ]);
          if (res.data.repeat.length > 0) window.location.reload();
        });
    },
    updateEvent(event) {
      if (!event.repeat) {
        axios.post("/api/createCalendarEvent", {
          id: event.id,
          startDate: this.moment(event.start).valueOf()
        });
      }
    },
    deleteEvent(event) {
      axios
        .post("/api/deleteCalendarEvent", {
          taskId: event.id
        })
        .then(res => {
          // this.spliceFromArray(this.events, event);
          // this.deleteEventDialog = false;
          // this.loadUserTasks();
          window.location.reload();
          Event.fire("notify", [`${event.name} успешно удален из календаря`]);
        });
    },
    loadTasksForCalendarEvents() {
      let currentDate = this.moment();
      axios.get("/api/tasksForCalendarEvents").then(res => {
        res.data.forEach((task, index) => {
          this.pushEvent(task);
          if (task.repeat && !task.repeated) {
            this.createRepeatTasks(task, task.repeat.range_period);
          }
        });
      });
    },
    // Push data to events array
    pushEvent(event) {
      let startDate = this.moment(Number(event.start_date)).format(
        "YYYY-MM-DD HH:mm"
      );
      let endDate = this.calculateEndDate(event.start_date, event.planned_time);
      this.events.push({
        name: event.description,
        start: startDate,
        end: endDate,
        id: event.id,
        planned_time: event.planned_time,
        color: this.colors[this.rnd(0, this.colors.length - 1)],
        repeat: false
      });
    },
    spliceFromArray(arr, event) {
      arr.forEach((item, index) => {
        if (item.id == event.id) arr.splice(index, 1);
      });
    },
    pushRepeatEvents(arr, period) {
      let color = this.colors[this.rnd(0, this.colors.length - 1)];
      let addDays = period;

      arr.forEach(el => {
        let daysOfWeek = JSON.parse(el.repeat.weekdays);
        let checkDayOfWeek = daysOfWeek.includes(
          this.moment(Number(el.start_date) + addDays * el.repeat.range).day()
        );
        let start = this.moment(
          (Number(el.start_date) + addDays) * el.repeat.range
        ).format("YYYY-MM-DD HH:mm");
        let end = this.calculateEndDate(
          Number(el.start_date) + addDays * el.repeat.range,
          Number(el.planned_time)
        );
        let daysInMonth = this.moment(start).daysInMonth() * 86400000;

        if (checkDayOfWeek) {
          this.events.push({
            name: el.description,
            start: start,
            end: end,
            id: el.id,
            planned_time: el.planned_time,
            color: this.events.find(event => event.id == el.id).color,
            repeat: true
          });
        }

        addDays += el.repeat.range_period == 'month' ? daysInMonth : period;
      });
    },
    createRepeatTasks(task, period) {
      let dayArr = [];
      let weekArr = [];
      let monthArr = [];
      let yearArr = [];
      let arr = [];
      let startDate = this.moment(Number(task.start_date)) || this.moment();
      let daysInMonth = this.moment(Number(task.start_date)).daysInMonth() * 86400000;
      if (task.repeat.end_date) {
        while (
          startDate
            .startOf("day")
            .diff(this.moment(task.repeat.end_date).startOf("day"), "days") < 0
        ) {
          startDate = this.moment(startDate).add(task.repeat.range, period);
          switch (period) {
            case "day":
              dayArr.push(task);
              break;
            case "week":
              weekArr.push(task);
              break;
            case "month":
              monthArr.push(task);
              break;
            case "year":
              yearArr.push(task);
              break;
            default:
              arr.push(task);
              break;
          }
        }
      } else {
        for (let i = 0; i < 365; i++) {
          arr.push(task);
        }
      }

      switch (period) {
        case "day":
          this.pushRepeatEvents(dayArr, 86400000);
          break;
        case "week":
          this.pushRepeatEvents(weekArr, 604800000);
          break;
        case "month":
          this.pushRepeatEvents(monthArr, daysInMonth);
          break;
        case "year":
          this.pushRepeatEvents(yearArr, 31536000000);
          break;
        default:
          this.pushRepeatEvents(arr, 86400000);
          break;
      }
    }
  },
  computed: {
    // Create time range to create events
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