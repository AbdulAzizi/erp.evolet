<template>
  <v-container fluid class="pa-0">
    <v-row justify="start">
      <v-col class="py-0">
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
              v-model="filters.from"
              label="From"
              readonly
              v-bind="attrs"
              v-on="on"
              solo
              hide-details
              dense
            ></v-text-field>
          </template>
          <v-date-picker v-model="filters.from" @input="fromMenu = false"></v-date-picker>
        </v-menu>
      </v-col>

      <v-col class="py-0">
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
              v-model="filters.to"
              label="To"
              readonly
              v-bind="attrs"
              v-on="on"
              solo
              hide-details
              dense
            ></v-text-field>
          </template>
          <v-date-picker v-model="filters.to" @input="toMenu = false"></v-date-picker>
        </v-menu>
      </v-col>

      <v-col class="py-0" v-if="divisions.length">
        <v-select
          v-model="selectedDivision"
          label="Отделы"
          :items="divisions"
          item-text="name"
          item-value="id"
          hide-details
          solo
          dense
        ></v-select>
      </v-col>
    </v-row>
    <v-row align="center" justify="center">
      <v-card v-if="loading" class="mt-10">
        <v-card-text class="pa-10">
          <v-progress-circular :size="100" color="primary" indeterminate></v-progress-circular>
        </v-card-text>
      </v-card>
      <v-col v-else>
        <v-card>
          <timeline
            :items="preparedTimesets"
            :groups="preparedUsers"
            :options="options"
            :key="timelineKey"
          ></timeline>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>
<script>
import { Timeline } from "vue2vis";
import "vue2vis/dist/vue2vis.css";

export default {
  props: {
    divisions: {
      required: true
    }
  },
  components: {
    Timeline
  },
  data() {
    return {
      preparedUsers: [],
      preparedTimesets: [],
      options: {
        configure: false,
        editable: false,
        stack: false,
        template: function(item, element, data) {
          // console.log(item);
          // console.log(element);
          // console.log(data);
          return `<span>${item.content}</span>`;
        },
        orientation: {
          axis: "both"
        },
        zoomMax: 31557600000,
        zoomMin: 3600000,
        tooltip: {
          followMouse: true
        }
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
        "blue-grey"
      ],
      filters: {
        from: null,
        to: null
      },
      fromMenu: null,
      toMenu: null,
      timesets: [],
      users: [],
      timelineKey: 0, // Needed to rerender component,
      loading: false,
      selectedDivision: null
    };
  },
  async created() {
    this.filters.from = await this.moment()
      .startOf("month")
      .format("YYYY-MM-DD");

    this.filters.to = await this.moment()
      .endOf("month")
      .format("YYYY-MM-DD");
  },
  methods: {
    prepareData() {
      this.preparedUsers = this.users.map(user => {
        return {
          id: user.id,
          content: user.fullname,
          color: this.colors[this.rand(0, this.colors.length - 1)],
          content:
            '<div tabindex="0" role="listitem" aria-selected="false" class="px-2 v-list-item v-list-item--dense theme--light" style="min-height:30px;"><div class="v-avatar my-0 v-list-item__avatar" style="height: 27px; min-width: 27px; width: 27px;"><div class="v-responsive v-image"><div class="v-responsive__sizer" style="padding-bottom: 100%;"></div><div class="v-image__image v-image__image--cover" style="background-image: url(&quot;' +
            this.photo(user.img) +
            '&quot;); background-position: center center;"></div><div class="v-responsive__content" style="width: 128px;"></div></div></div><div class="v-list-item__content py-0"><div class="v-list-item__title">' +
            user.fullname +
            "</div></div></div>"
        };
      });

      this.preparedTimesets = this.timesets.map(timeset => {
        return {
          id: timeset.id,
          title: timeset.task.description,
          content: timeset.task.description,
          group: timeset.task.responsible_id,
          start: this.moment(timeset.start_time),
          end: this.moment(timeset.end_time),
          className:
            this.preparedUsers.filter(user => {
              return user.id == timeset.task.responsible_id;
            })[0].color +
            " " +
            "white--text caption"
        };
      });
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
          params: {
            from: this.filters.from,
            to: this.filters.to,
            division_id: this.selectedDivision
          }
        })
        .then(resp => {
          this.users = resp.data.users;
          this.timesets = resp.data.timesets;

          this.prepareData();
        });
    }
  },
  watch: {
    from(val) {
      if (this.filters.to) {
        this.fetchTimesets();
      }
    },
    to(val) {
      if (this.filters.from) {
        this.fetchTimesets();
      }
    },
    selectedDivision(val) {
      this.fetchTimesets();
    }
  },
  computed: {
    from() {
      return this.filters.from;
    },
    to() {
      return this.filters.to;
    }
  }
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