<template>
  <v-container fluid class="pa-0">
    <v-row justify="start">
      <v-col class="py-0" v-if="filters.user_id">
        <v-menu
          v-model="dateMenu"
          :close-on-content-click="false"
          :nudge-right="40"
          transition="scale-transition"
          offset-y
          min-width="290px"
        >
          <template v-slot:activator="{ on, attrs }">
            <v-text-field
              v-model="filters.month"
              label="Дата"
              readonly
              v-bind="attrs"
              v-on="on"
              solo
              hide-details
              dense
            ></v-text-field>
          </template>
          <v-date-picker v-model="filters.month" @input="dateMenu = false" type="month"></v-date-picker>
        </v-menu>
      </v-col>

      <v-col class="py-0" v-if="filters.division_id">
        <v-menu
          v-model="fromMenu"
          :close-on-content-click="false"
          :nudge-right="40"
          transition="scale-transition"
          offset-y
          min-width="290px"
        >
          <template v-slot:activator="{ on, attrs }">
            <v-text-field
              v-model="filters.start_time"
              label="From"
              readonly
              v-bind="attrs"
              v-on="on"
              solo
              hide-details
              dense
            ></v-text-field>
          </template>
          <v-date-picker v-model="filters.start_time" @input="fromMenu = false"></v-date-picker>
        </v-menu>
      </v-col>

      <v-col class="py-0" v-if="filters.division_id">
        <v-menu
          v-model="toMenu"
          :close-on-content-click="false"
          :nudge-right="40"
          transition="scale-transition"
          offset-y
          min-width="290px"
        >
          <template v-slot:activator="{ on, attrs }">
            <v-text-field
              v-model="filters.end_time"
              label="To"
              readonly
              v-bind="attrs"
              v-on="on"
              solo
              hide-details
              dense
            ></v-text-field>
          </template>
          <v-date-picker v-model="filters.end_time" @input="toMenu = false"></v-date-picker>
        </v-menu>
      </v-col>

      <v-col class="py-0" v-if="divisions.length">
        <v-select
          v-model="filters.division_id"
          label="Отделы"
          :items="divisions"
          item-text="name"
          item-value="id"
          hide-details
          solo
          dense
        ></v-select>
      </v-col>
      <v-col class="py-0">
        <form-field
          :field="{
            users: users,
            type: 'users',
            label: 'Сотрудники',
            rules: ['required'],
            multiple: false,
            dense: true,
            solo: true,
            rounded: false,
            'hide-details': true
          }"
          v-model="filters.user_id"
        />
      </v-col>
    </v-row>
    <v-row align="center" justify="center">
      <v-card v-if="loading" class="mt-10">
        <v-card-text class="pa-10">
          <v-progress-circular :size="100" color="primary" indeterminate></v-progress-circular>
        </v-card-text>
      </v-card>
      <template v-else>
        <v-col v-if="filters.division_id">
          <v-card>
            <timeline
              :items="preparedTimesets"
              :groups="preparedUsers"
              :options="options"
              :key="timelineKey"
            ></timeline>
          </v-card>
        </v-col>
        <!-- <v-col v-else>
          <v-sheet height="calc( 100vh - 125px )">
            <v-calendar
              ref="calendar"
              :now="moment().format('YYYY-MM-DD')"
              v-model="filters.month"
              :events="events"
              color="primary"
              type="week"
              :first-interval="13"
              :interval-minutes="30"
              :interval-count="36"
              :interval-height="48"
            ></v-calendar>
          </v-sheet>
        </v-col>-->
        <v-container v-else fluid class="white pa-0 ma-2" style="position:relative;overflow:hidden">
          <v-row style="margin-left:90px;margin-right:0;" ref="timeline">
            <span
              class="text-caption"
              :style="'margin-right:' + zoom + 'px; color: #9b9b9b;'"
              v-for="(hour,index) in timeline"
              :key="index"
            >{{hour}}</span>
          </v-row>
          <v-divider class="grey lighten-3" />

          <template v-for="(day,index) in localDays">
            <v-row :key="'day-'+index" style="position:relative;" class="ma-0" align="center">
              <span style="color: #9b9b9b; width:90px;" class="text-caption px-3">{{day.text}}</span>

              <v-tooltip top v-for="(timeset,index) in day.timesets" :key="'timeset-'+index">
                <template v-slot:activator="{ on, attrs }">
                  <v-chip
                    x-small
                    label
                    :style="'min-width:3px; width:' + ((moment(timeset.end_time).diff(moment(timeset.start_time)) / 1000) * 0.015555) + 'px; z-index:2; position: absolute; left:' + calculateLeftSpace(timeset) + 'px;'"
                    :class="day.color + ' white--text px-1'"
                    v-on="on"
                  >{{timeset.task.description}}</v-chip>
                </template>
                <span>{{timeset.task.description}}</span>
              </v-tooltip>
            </v-row>
            <v-divider :key="'divider-'+index" class="grey lighten-3" />
          </template>

          <v-divider
            vertical
            v-for="(hour,index) in timeline"
            :key="'vertical-divider'+index"
            :style="'position:absolute; top:21px;left:'+(90+16+(56*index))+'px'"
          />
        </v-container>
      </template>
    </v-row>
  </v-container>
</template>
<script>
import { Timeline } from "vue2vis";
import "vue2vis/dist/vue2vis.css";

export default {
  props: {
    divisions: {
      required: true,
    },
    users: {
      required: true,
    },
  },
  components: {
    Timeline,
  },
  data() {
    return {
      preparedUsers: [],
      preparedTimesets: [],
      options: {
        configure: false,
        editable: false,
        stack: false,
        template: function (item, element, data) {
          // console.log(item);
          // console.log(element);
          // console.log(data);
          return `<span>${item.content}</span>`;
        },
        orientation: {
          axis: "both",
        },
        zoomMax: 31557600000,
        zoomMin: 3600000,
        tooltip: {
          followMouse: true,
        },
      },
      colors: [
        "red",
        "pink",
        "purple",
        "deep-purple",
        "indigo",
        "blue",
        "light-blue",
        "cyan",
        "teal",
        "green",
        "light-green",
        "lime",
        "amber",
        "orange",
        "deep-orange",
        "blue-grey",
      ],
      filters: {
        month: null,
        start_time: null,
        end_time: null,
        division_id: null,
        user_id: null,
      },
      dateMenu: null,
      fromMenu: null,
      toMenu: null,
      timesets: [],
      timelineUsers: [],
      timelineKey: 0, // Needed to rerender component,
      loading: false,

      timeline: [],
      zoom: 24,
      localDays: [],
    };
  },
  async created() {
    this.filters.start_time = await this.moment()
      .startOf("month")
      .format("YYYY-MM-DD");

    this.filters.end_time = await this.moment()
      .endOf("month")
      .format("YYYY-MM-DD");

    if (this.isExeption) {
      this.filters.division_id = this.auth.division.id;
    }
  },
  methods: {
    prepareCustomTimeline() {
      this.timeline = [];
      this.localDays = [];

      for (let i = 0; i < 24; i++) {
        this.timeline.push((i < 10 ? "0" + i : i) + ":00");
      }
      let res = this.filters.month.split("-");
      let year = res[0];
      let month = parseInt(res[1]) - 1;

      let date = this.moment().set({ year: year, month: month });

      let startMonth = this.moment(date).startOf("month");
      let endMonth = this.moment(date).endOf("month");

      while (startMonth <= endMonth) {
        this.localDays.push({
          text: startMonth.format("YYYY-MM-DD"),
          timesets: [],
          color: this.colors[this.rand(0, this.colors.length - 1)]
        });
        startMonth = startMonth.add(1, "days");
      }
      // push timeset for each day
      this.localDays.forEach((day) => {
        day.timesets = [];
        this.timesets.forEach((timeset) => {
          if (
            this.moment(timeset.start_time).format("YYYY-MM-DD") == day.text
          ) {
            day.timesets.push(timeset);
          }
        });
      });
    },
    prepareData() {
      this.preparedUsers = this.timelineUsers.map((user) => {
        return {
          id: user.id,
          content: user.fullname,
          color: this.colors[this.rand(0, this.colors.length - 1)],
          content:
            '<div tabindex="0" role="listitem" aria-selected="false" class="px-2 v-list-item v-list-item--dense theme--light" style="min-height:30px;">' +
            '<div class="v-avatar my-0 v-list-item__avatar" style="height: 27px; min-width: 27px; width: 27px;">' +
            '<div class="v-responsive v-image">' +
            '<div class="v-responsive__sizer" style="padding-bottom: 100%;"></div>' +
            '<div class="v-image__image v-image__image--cover" style="background-image: url(&quot;' +
            this.photo(user.img) +
            '&quot;); background-position: center center;"></div>' +
            '<div class="v-responsive__content" style="width: 128px;"></div>' +
            "</div>" +
            "</div>" +
            '<div class="v-list-item__content py-0">' +
            '<div class="v-list-item__title">' +
            user.fullname +
            "</div>" +
            "</div>" +
            "</div>",
        };
      });

      this.preparedTimesets = this.timesets.map((timeset) => {
        return {
          id: timeset.id,
          title: timeset.task.description,
          content: timeset.task.description,
          group: timeset.task.responsible_id,
          start: this.moment(timeset.start_time),
          end: this.moment(timeset.end_time),
          className:
            this.preparedUsers.filter((user) => {
              return user.id == timeset.task.responsible_id;
            })[0].color +
            " " +
            "white--text caption",
        };
      });

      // initialize custom timeline
      if (this.filters.month) {
        this.prepareCustomTimeline();
      }

      this.loading = false;
      this.timelineKey += 1; // Needed to rerender component
    },
    rand(min, max) {
      return Math.floor(Math.random() * (max - min + 1)) + min;
    },
    fetchTimesets() {
      this.loading = true;
      axios
        .get(this.appPath(`api/timesets`), {
          params: this.filters,
        })
        .then((resp) => {
          this.timelineUsers = resp.data.users;
          this.timesets = resp.data.timesets;

          this.prepareData();
        });
    },
    isExeption() {
      let exeptions = ["ОРПО", "ОУПС", "Evolet"];

      exeptions.forEach((ex) => {
        if (ex == this.auth.division.abbreviation) return true;
      });

      return false;
    },
    calculateLeftSpace(timeset) {
      return (
        106 +
        this.moment(timeset.start_time).local().hours() * 56 +
        this.moment(timeset.start_time).local().minutes() * (56 / 60)
      );
    },
  },
  watch: {
    start_time(val) {
      if (this.filters.end_time) {
        this.fetchTimesets();
      }
    },
    end_time(val) {
      if (this.filters.start_time) {
        this.fetchTimesets();
      }
    },
    division_id(val) {
      if (val != null) {
        this.filters.user_id = null;
        this.filters.month = null;
        this.fetchTimesets();
      }
    },
    user_id(val) {
      if (val != null) {
        this.filters.division_id = null;
        this.filters.month = this.moment().format("YYYY-MM");
        this.fetchTimesets();
      }
    },
    month(val) {
      this.filters.start_time = null;
      this.filters.end_time = null;
      this.fetchTimesets();
    },
  },
  computed: {
    events() {
      return this.timesets.map((timeset) => {
        return {
          start: this.moment(timeset.start_time)
            .local()
            .format("YYYY-MM-DD HH:mm:ss"),
          end: this.moment(timeset.end_time)
            .local()
            .format("YYYY-MM-DD HH:mm:ss"),
          name: timeset.task.description,
        };
      });
    },
    start_time() {
      return this.filters.start_time;
    },
    end_time() {
      return this.filters.end_time;
    },
    division_id() {
      return this.filters.division_id;
    },
    user_id() {
      return this.filters.user_id;
    },
    month() {
      return this.filters.month;
    },
  },
};
</script>
<style>
.vis-item-content {
  padding: 0px 10px !important;
}
.vis-inner {
  padding: 0px !important;
}
</style>