<template>
  <div>
    <v-container fluid class="pa-0">
      <v-row justify="start">
        <v-col class="py-0">
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
            <v-date-picker
              v-model="filters.month"
              @input="dateMenu = false"
              type="month"
            ></v-date-picker>
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
              'hide-details': true,
            }"
            v-model="filters.user_id"
          />
        </v-col>
      </v-row>
      <v-row v-if="loading" align="center" justify="center" style="height:80vh">
        <v-progress-circular
          :size="100"
          color="primary"
          indeterminate
        ></v-progress-circular>
      </v-row>
    </v-container>
    <v-container fluid class="pa-0 mt-3 white" v-if="!loading">
      <v-row no-gutters>
        <div>
          <span style="color: #9b9b9b" class="text-caption px-1"
            >Name Surname</span
          >
          <v-divider class="grey lighten-3" />
          <template v-for="(entry, index) in entries">
            <span
              :key="'entry-fulname-' + index"
              style="color: #9b9b9b"
              class="text-caption px-1"
              >{{ entry.user.fullname }}</span
            >
            <v-divider
              :key="'divider-fullname-' + index"
              class="grey lighten-3"
            />
          </template>
        </div>
        <v-divider vertical />
        <div>
          <span style="color: #9b9b9b" class="text-caption px-1">Date</span>
          <v-divider class="grey lighten-3" />
          <template v-for="(entry, index) in entries">
            <span
              :key="'entry-date-' + index"
              style="color: #9b9b9b"
              class="text-caption px-1"
              >{{ entry.date }}</span
            >
            <v-divider :key="'divider-date-' + index" class="grey lighten-3" />
          </template>
        </div>
        <v-divider vertical />
        <div>
          <span style="color: #9b9b9b" class="text-caption px-1">Sign In</span>
          <v-divider class="grey lighten-3" />
          <template v-for="(entry, index) in entries">
            <span
              :key="'entry-sign_in-' + index"
              style="color: #9b9b9b"
              class="text-caption px-1"
              >{{ entry.sign_in }}</span
            >
            <v-divider
              :key="'divider-sign_in-' + index"
              class="grey lighten-3"
            />
          </template>
        </div>
        <v-divider vertical />
        <div>
          <span style="color: #9b9b9b" class="text-caption px-1">Sign Out</span>
          <v-divider class="grey lighten-3" />
          <template v-for="(entry, index) in entries">
            <span
              :key="'entry-sign_out-' + index"
              style="color: #9b9b9b"
              class="text-caption px-1"
              >{{ entry.sign_out }}</span
            >
            <v-divider
              :key="'divider-sign_out-' + index"
              class="grey lighten-3"
            />
          </template>
        </div>
        <v-divider vertical />
        <v-row no-gutters>
          <v-col
            cols="12"
            class="pa-0"
            style="position: relative; overflow: hidden"
          >
            <v-row no-gutters>
              <span :style="' width:' + 100 / 18 / 2 + '%;'"></span>
              <span
                v-for="(hour, index) in getHours"
                :key="'hour-text-' + index"
                :style="
                  'padding:2px 0;color: #9b9b9b; width:' + 100 / 18 + '%;'
                "
                class="text-caption text-center"
                >{{ hour }}</span
              >
              <span :style="' width:' + 100 / 18 / 2 + '%;'"></span>
            </v-row>
            <v-divider />
            <template v-for="(entry, index) in entries">
              <v-row
                align="center"
                style="min-height: 24px"
                no-gutters
                :key="'timesets-' + index"
              >
                <v-tooltip
                  top
                  v-for="(timeset, index) in entry.timesets"
                  :key="'timeset-' + index"
                >
                  <template v-slot:activator="{ on, attrs }">
                    <v-chip
                      x-small
                      label
                      :style="
                        'min-width:3px; width:' +
                        moment
                          .duration(
                            moment(timeset.end_time).diff(
                              moment(timeset.start_time)
                            )
                          )
                          .asHours() *
                          (100 / 18) +
                        '%; z-index:2; position: absolute; left:' +
                        calculateLeftSpace(timeset.start_time) +
                        '%;'
                      "
                      :class="entry.color + ' white--text px-1'"
                      v-on="on"
                      >{{ timeset.task.description }}</v-chip
                    >
                  </template>
                  <span>
                    {{ timeset.task.description }}
                    (
                    {{ moment(timeset.start_time).local().format("HH:mm") }}
                    -
                    {{ moment(timeset.end_time).local().format("HH:mm") }}
                    )
                  </span>
                </v-tooltip>
              </v-row>
              <v-divider :key="'timesets-divider-' + index" />
            </template>
            <v-divider
              vertical
              v-for="(hour, index) in getHours"
              :key="'vertical-divider' + index"
              :style="
                'position:absolute; top:24px;left:' +
                (100 / 18) * (index + 1) +
                '%'
              "
            />
          </v-col>
        </v-row>
      </v-row>
    </v-container>
  </div>
</template>
<script>
export default {
  props: {
    divisions: {
      required: true,
    },
    users: {
      required: true,
    },
  },
  data() {
    return {
      filters: {
        month: null,
        division_id: null,
        user_id: null,
      },
      dateMenu: null,
      entries: [],
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
      loading: false,
    };
  },
  async created() {
    this.filters.division_id = await this.auth.division.id;

    this.filters.month = await this.moment().format("YYYY-MM");
  },
  methods: {
    calculateLeftSpace(date) {
      let n = 100 / 18;
      return (
        (this.moment(date).local().hours() - 6) * n +
        this.moment(date).local().minutes() * (n / 60)
      );
    },
    fetchTimesets() {
      this.loading = true;
      axios
        .get(this.appPath(`api/timesets`), {
          params: this.filters,
        })
        .then((resp) => {
          this.timesets = resp.data.timesets;
          this.entries = resp.data.entries;

          this.entries.forEach((entry) => {
            entry.timesets = [];
            entry.color = this.colors[this.rand(0, this.colors.length - 1)];
            this.timesets.forEach((timeset) => {
              if (
                this.moment(timeset.start_time).format("YYYY-MM-DD") ==
                  entry.date &&
                entry.user_id == timeset.task.responsible_id
              ) {
                entry.timesets.push(timeset);
              }
            });
          });
          this.loading = false;
        });
    },
    rand(min, max) {
      return Math.floor(Math.random() * (max - min + 1)) + min;
    },
  },
  watch: {
    division_id(val) {
      if (val != null && this.filters.month != null) {
        this.filters.user_id = null;
        this.fetchTimesets();
      }
    },
    user_id(val) {
      if (val != null && this.filters.month != null) {
        this.filters.division_id = null;
        this.fetchTimesets();
      }
    },
    month(val) {
      if (this.filters.division_id != null || this.filters.user_id != null) {
        this.fetchTimesets();
      }
    },
  },
  computed: {
    division_id() {
      return this.filters.division_id;
    },
    user_id() {
      return this.filters.user_id;
    },
    month() {
      return this.filters.month;
    },
    getHours() {
      let hours = [];
      for (let i = 7; i < 24; i++) {
        hours.push((i < 10 ? "0" + i : i) + ":00");
      }
      return hours;
    },
  },
};
</script>