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
      <v-row
        v-if="loading"
        align="center"
        justify="center"
        style="height: 80vh"
      >
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
          <span
            style="color: #9b9b9b; display: block; min-height: 20px"
            class="text-caption px-1"
            >Имя Фамилия</span
          >
          <v-divider />
          <template v-for="(entry, index) in entries">
            <span
              :key="'entry-fulname-' + index"
              style="color: #9b9b9b; display: block; min-height: 20px"
              :class="'text-caption px-1 ' + getRowColor(entry)"
              >{{ entry.user.fullname }}</span
            >
            <v-divider :key="'divider-fullname-' + index" />
          </template>
        </div>

        <v-divider vertical />

        <div>
          <span
            style="color: #9b9b9b; display: block; min-height: 20px"
            class="text-caption px-1"
            >День</span
          >
          <v-divider />
          <template v-for="(entry, index) in entries">
            <span
              :key="'entry-date-' + index"
              style="color: #9b9b9b; display: block; min-height: 20px"
              :class="'text-caption px-1 ' + getRowColor(entry)"
              >{{ entry.date }}</span
            >
            <v-divider :key="'divider-date-' + index" />
          </template>
        </div>

        <v-divider vertical />

        <div>
          <span
            style="color: #9b9b9b; display: block; min-height: 20px"
            class="text-caption px-1"
            >Вх</span
          >
          <v-divider />
          <template v-for="(entry, index) in entries">
            <span
              :key="'entry-sign_in-' + index"
              style="color: #9b9b9b; display: block; min-height: 20px"
              :class="'text-caption px-1 ' + getRowColor(entry)"
              >{{ entry.sign_in }}</span
            >
            <v-divider :key="'divider-sign_in-' + index" />
          </template>
        </div>

        <v-divider vertical />

        <div>
          <span
            style="color: #9b9b9b; display: block; min-height: 20px"
            class="text-caption px-1"
            >Вых</span
          >
          <v-divider />
          <template v-for="(entry, index) in entries">
            <span
              :key="'entry-sign_out-' + index"
              style="color: #9b9b9b; display: block; min-height: 20px"
              :class="'text-caption px-1 ' + getRowColor(entry)"
              >{{ entry.sign_out }}</span
            >
            <v-divider :key="'divider-sign_out-' + index" />
          </template>
        </div>

        <v-divider vertical />

        <div>
          <span
            style="color: #9b9b9b; display: block; min-height: 20px"
            class="text-caption px-1"
            >Опоз</span
          >
          <v-divider />
          <template v-for="(entry, index) in entries">
            <span
              :key="'entry-late-' + index"
              style="color: #9b9b9b; display: block; min-height: 20px"
              :class="'text-caption px-1 ' + getRowColor(entry)"
              >{{ entry.late }}</span
            >
            <v-divider :key="'divider-late-' + index" />
          </template>
        </div>

        <v-divider vertical />

        <div>
          <span
            style="color: #9b9b9b; display: block; min-height: 20px"
            class="text-caption px-1"
            >Рань</span
          >
          <v-divider />
          <template v-for="(entry, index) in entries">
            <span
              :key="'entry-late-' + index"
              style="color: #9b9b9b; display: block; min-height: 20px"
              :class="'text-caption px-1 ' + getRowColor(entry)"
              >{{ entry.early }}</span
            >
            <v-divider :key="'divider-late-' + index" />
          </template>
        </div>

        <v-divider vertical />

        <div>
          <span
            style="color: #9b9b9b; display: block; min-height: 20px"
            class="text-caption px-1"
            >Прис</span
          >
          <v-divider />
          <template v-for="(entry, index) in entries">
            <span
              :key="'entry-late-' + index"
              style="color: #9b9b9b; display: block; min-height: 20px"
              :class="'text-caption px-1 ' + getRowColor(entry)"
              >{{ entry.present }}</span
            >
            <v-divider :key="'divider-late-' + index" />
          </template>
        </div>

        <v-divider vertical />

        <div>
          <span
            style="color: #9b9b9b; display: block; min-height: 20px"
            class="text-caption px-1"
            >Овер</span
          >
          <v-divider />
          <template v-for="(entry, index) in entries">
            <span
              :key="'entry-late-' + index"
              style="color: #9b9b9b; display: block; min-height: 20px"
              :class="'text-caption px-1 ' + getRowColor(entry)"
              >{{ entry.overtime }}</span
            >
            <v-divider :key="'divider-late-' + index" />
          </template>
        </div>

        <v-divider vertical />

        <v-row no-gutters>
          <v-col
            cols="10"
            class="pa-0"
            style="position: relative; overflow: hidden"
          >
            <v-row no-gutters>
              <span :style="'width:' + 100 / 18 / 2 + '%;'"></span>
              <span
                v-for="(hour, index) in getHours"
                :key="'hour-text-' + index"
                :style="' color: #9b9b9b; width:' + 100 / 18 + '%;'"
                class="text-caption text-center"
                >{{ hour }}</span
              >
              <span :style="'width:' + 100 / 18 / 2 + '%;'"></span>
            </v-row>

            <v-divider />

            <template v-for="(entry, index) in entries">
              <v-row
                align="center"
                style="min-height: 20px"
                no-gutters
                :key="'timesets-' + index"
                :class="getRowColor(entry)"
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
                        'min-width: 3px; width:' +
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
                'position:absolute; top:21px;left:' +
                (100 / 18) * (index + 1) +
                '%'
              "
            />
          </v-col>
          <v-divider vertical />
          <v-col cols="2" style="margin-left: -1px; width: 100px">
            <span
              style="
                padding-left: 4px;
                color: #9b9b9b;
                display: block;
                min-height: 20px;
              "
              class="text-caption"
              >Коментарии</span
            >
            <v-divider />

            <template v-for="(entry, index) in entries">
              <v-hover
                :key="'timeset-comment-' + index"
                v-slot:default="{ hover }"
              >
                <div style="position: relative">
                  <span
                    style="
                      color: #9b9b9b;
                      display: block;
                      min-height: 20px;
                      margin-left: 1px;
                    "
                    :class="
                      'text-caption px-1 text-truncate ' + getRowColor(entry)
                    "
                    >{{ entry.comment }}</span
                  >
                  <v-btn
                    v-if="hover"
                    icon
                    absolute
                    @click="editComment(entry)"
                    x-small
                    style="top: 0px; right: 0px"
                  >
                    <v-icon size="12">mdi-pencil</v-icon>
                  </v-btn>
                </div>
              </v-hover>
              <v-divider :key="'timeset-comment-divider-' + index" />
            </template>
          </v-col>
        </v-row>
      </v-row>
    </v-container>
    <v-dialog v-model="comment.dialog" max-width="500">
      <v-card>
        <v-text-field
          dense
          single-line
          hide-details
          outlined
          v-model="comment.model"
        >
          <template v-slot:append>
            <v-icon @click="cancelEdit()">mdi-close</v-icon>
            <v-icon @click="saveComment()">mdi-check</v-icon>
          </template>
        </v-text-field>
      </v-card>
    </v-dialog>
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
      comment: {
        dialog: null,
        model: null,
        entry: null,
      },
    };
  },
  async created() {
    this.filters.division_id = await this.auth.division.id;

    this.filters.month = await this.moment().format("YYYY-MM");
  },
  methods: {
    cancelEdit() {
      this.comment.dialog = false;
    },
    saveComment() {
      axios
        .put(this.appPath(`api/entries/${this.comment.entry.id}`), {
          comment: this.comment.model,
        })
        .then((resp) => {
          this.entries.forEach((e) => {
            if (e.id == this.comment.entry.id) {
              e.comment = resp.data.comment;
            }
          });
          this.comment.dialog = false;
          Event.fire("notify", ["Сохранено успешно!"]);
        });
    },
    editComment(entry) {
      this.comment.dialog = true;
      this.comment.model = entry.comment;
      this.comment.entry = entry;
    },
    getRowColor(entry) {
      if (
        this.moment(entry.date, "YYYY:MM:DD").day() != 6 &&
        this.moment(entry.date, "YYYY:MM:DD").day() != 0
      ) {
        if (entry.user.fullname == "Итоги") {
          return "blue lighten-4";
        }
      } else {
        return "grey lighten-4";
      }
    },
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
          // get the id of the first
          let userID = this.entries.length ? this.entries[0].user_id : 0;
          // initialize totals
          let totalPresent = this.moment.duration("00:00:00");
          let totalLate = this.moment.duration("00:00:00");
          let totalOvertime = this.moment.duration("00:00:00");
          let totalEarly = this.moment.duration("00:00:00");
          // make own index
          let index = 0;

          this.entries.forEach((entry) => {
            if (entry.user.position_level_id == 1) {
              entry.hoursPerDay = 11;
              entry.timeToLeave = "19:30";
            } else {
              entry.hoursPerDay = 9;
              entry.timeToLeave = "17:30";
            }

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

            if (entry.sign_in) {
              entry.sign_in = this.moment(entry.sign_in, "HH:mm:ss").format(
                "HH:mm"
              );
              if (entry.sign_in > "08:30") {
                entry["late"] = this.moment(
                  this.moment(entry.sign_in, "HH:mm").diff(
                    this.moment("08:30", "HH:mm")
                  )
                ).format("HH:mm");
              } else {
                entry["late"] = "00:00";
              }
            } else {
              entry["sign_in"] = "";
              entry["late"] = "";
            }

            if (entry.sign_out) {
              entry.sign_out = this.moment(entry.sign_out, "HH:mm:ss").format(
                "HH:mm"
              );
              if (entry.timeToLeave > entry.sign_out) {
                entry["early"] = this.moment(
                  this.moment(entry.timeToLeave, "HH:mm").diff(
                    this.moment(entry.sign_out, "HH:mm")
                  )
                ).format("HH:mm");
              } else {
                entry["early"] = "00:00";
              }
            } else {
              entry["sign_out"] = "";
              entry["early"] = "";
            }

            if (entry.sign_in && entry.sign_out) {
              entry["present"] = this.moment(
                this.moment(entry.sign_out, "HH:mm").diff(
                  this.moment(entry.sign_in, "HH:mm")
                )
              ).format("HH:mm");
              // ----------------------------------------------

              if (
                entry["present"] >
                this.moment(entry.hoursPerDay, "H").format("HH:mm")
              ) {
                entry["overtime"] = this.moment(
                  this.moment(entry["present"], "HH:mm") -
                    this.moment(entry.hoursPerDay, "H")
                ).format("HH:mm");
              } else {
                entry["overtime"] = "00:00";
              }
            } else {
              entry["present"] = "";
              entry["overtime"] = "";
            }
            // if it is different User or the last one
            if (userID == entry.user_id && index != this.entries.length - 1) {
              // add to totals
              if (entry["present"] != "") {
                totalPresent.add(this.moment.duration(entry["present"]));
              }
              if (entry["late"] != "") {
                totalLate.add(this.moment.duration(entry["late"]));
              }
              if (entry["overtime"] != "") {
                totalOvertime.add(this.moment.duration(entry["overtime"]));
              }
              if (entry["early"] != "") {
                totalEarly.add(this.moment.duration(entry["early"]));
              }
            } else {
              // get id of a new user
              userID = entry.user_id;
              // make total row object and push totals
              let tempEntry = { user: {} };
              tempEntry.user_id = entry.user_id;
              tempEntry.user.fullname = "Итоги";
              tempEntry.date = this.moment(entry.date).format("YYYY-MM");
              tempEntry.sign_in = "";
              tempEntry.sign_out = "";
              tempEntry.present = this.msToTime(totalPresent);
              tempEntry.late = this.msToTime(totalLate);
              tempEntry.overtime = this.msToTime(totalOvertime);
              tempEntry.early = this.msToTime(totalEarly);
              // if last user push
              if (index == this.entries.length - 1) {
                this.entries.push(tempEntry);
              }
              // if not push with index 
              else {
                this.entries.splice(index, 0, tempEntry);
              }
              // clear totals
              totalPresent = this.moment.duration("00:00:00");
              totalLate = this.moment.duration("00:00:00");
              totalOvertime = this.moment.duration("00:00:00");
              totalEarly = this.moment.duration("00:00:00");
              // increment after total row push
              index++;
            }
            // increment index
            index++;
          });
          this.loading = false;
        });
    },
    rand(min, max) {
      return Math.floor(Math.random() * (max - min + 1)) + min;
    },
    msToTime(duration) {
      var milliseconds = parseInt((duration % 1000) / 100),
        seconds = Math.floor((duration / 1000) % 60),
        minutes = Math.floor((duration / (1000 * 60)) % 60),
        hours = Math.floor(duration / (1000 * 60 * 60));

      hours = hours < 10 ? "0" + hours : hours;
      minutes = minutes < 10 ? "0" + minutes : minutes;
      seconds = seconds < 10 ? "0" + seconds : seconds;

      return hours + ":" + minutes;
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