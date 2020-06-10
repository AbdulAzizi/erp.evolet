<template>
  <component
    :is="horizontalButtons ? 'v-menu':'div'"
    v-bind="horizontalButtons ? {'open-on-hover':true}:{} "
    v-if="localTask"
  >
    <template v-slot:activator="{ on, attrs }">
      <v-card
        v-bind="attrs"
        v-on="on"
        flat
        style="border-radius:40px;"
        class="grey lighten-2 ma-3 text-center"
        min-width="200"
      >
        <!-- minus 4 days coz this is how moment works -->
        {{moment(duration-345600000).days() }} :
        {{moment(duration).hours() }} :
        {{moment(duration).minutes() }} :
        {{moment(duration).seconds() }}
      </v-card>
      <v-btn
        text
        small
        :href="appPath(`tasks/${localTask.id}`)"
      >{{localTask.description.substring(0, 30)}}</v-btn>
    </template>
    <v-list dense rounded>

      <v-list-item class="grey lighten-2 text-center black--text" disabled>
        <v-list-item-content>
          <v-list-item-title>
            {{moment(duration-345600000).days() }} :
            {{moment(duration).hours() }} :
            {{moment(duration).minutes() }} :
            {{moment(duration).seconds() }}
          </v-list-item-title>
        </v-list-item-content>
      </v-list-item>

      <v-list-item class="blue" dark v-if="tasIskNew" @click="play">
        <v-list-item-icon>
          <v-icon>mdi-play</v-icon>
        </v-list-item-icon>
        <v-list-item-content>
          <v-list-item-title>Начать</v-list-item-title>
        </v-list-item-content>
      </v-list-item>

      <v-list-item class="amber darken-3" dark v-if="taskIsPaused" @click="play">
        <v-list-item-icon>
          <v-icon>mdi-play</v-icon>
        </v-list-item-icon>
        <v-list-item-content>
          <v-list-item-title>Продолжить</v-list-item-title>
        </v-list-item-content>
      </v-list-item>

      <v-list-item class="blue-grey" dark v-if="taskIsPlaying" @click="pause">
        <v-list-item-icon>
          <v-icon>mdi-pause</v-icon>
        </v-list-item-icon>
        <v-list-item-content>
          <v-list-item-title>Приостановить</v-list-item-title>
        </v-list-item-content>
      </v-list-item>

      <v-list-item class="green" dark v-if="taskIsPlaying || taskIsPaused" @click="stop">
        <v-list-item-icon>
          <v-icon>mdi-stop</v-icon>
        </v-list-item-icon>
        <v-list-item-content>
          <v-list-item-title>Завершить</v-list-item-title>
        </v-list-item-content>
      </v-list-item>

      <v-list-item class="blue" dark v-if="tasIsClosed" @click="play">
        <v-list-item-icon>
          <v-icon>mdi-replay</v-icon>
        </v-list-item-icon>
        <v-list-item-content>
          <v-list-item-title>Возобновить</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      
    </v-list>
  </component>
</template>
<script>
export default {
  props: {
    task: {
      required: true
    },
    horizontalButtons: {
      required: false,
      default: false
    }
  },
  data() {
    return {
      localTask: this.task,
      duration: 0,
      interval: 1000
    };
  },
  methods: {
    initialization() {
      Event.listen(
        `tasks/${this.localTask.id}/time_sets/changed`,
        time_sets => {
          this.localTask.time_sets = time_sets;
        }
      );
      Event.listen(`tasks/${this.localTask.id}/status/changed`, status => {
        this.localTask.status = status;
      });
      this.calculateTimeSets();
    },
    calculateTimeSets() {
      let sumOfDiffTime = 0;
      let from = 0;
      let to = 0;

      this.localTask.time_sets.forEach(time_set => {
        // Make last start time as a moment object
        from = this.moment(time_set.start_time);
        // if end time exists get it if not get current time
        to = this.moment(time_set.end_time);
        // to = time_set.end_time
        //     ? this.moment(time_set.end_time)
        //     : this.moment();
        // Add diff time to sum
        sumOfDiffTime = sumOfDiffTime + to.diff(from);
      });

      // Set Duration variable as difference time
      this.duration = sumOfDiffTime;
    },
    runTimer() {
      setInterval(() => {
        // Run if status is playing
        if (this.taskIsPlaying) {
          this.duration = this.duration + this.interval;
        }
      }, this.interval);
    },
    play() {
      axios
        .get(this.appPath(`api/tasks/${this.localTask.id}/start`))
        .then(response => {
          this.localTask = response.data;
          Event.fire(`tasks/changed`, this.localTask);
          Event.fire(
            `tasks/${this.localTask.id}/status/changed`,
            this.localTask.status
          );
          Event.fire(
            `tasks/${this.localTask.id}/time_sets/changed`,
            this.localTask.time_sets
          );
        })
        .catch(err => err.messages);
    },
    pause() {
      axios
        .get(this.appPath(`api/tasks/${this.localTask.id}/pause`))
        .then(response => {
          this.localTask = response.data;
          Event.fire(
            `tasks/${this.localTask.id}/status/changed`,
            this.localTask.status
          );
          Event.fire(
            `tasks/${this.localTask.id}/time_sets/changed`,
            this.localTask.time_sets
          );
        })
        .catch(err => err.messages);
    },
    stop() {
      axios
        .get(this.appPath(`api/tasks/${this.localTask.id}/stop`))
        .then(response => {
          this.localTask = response.data;
          Event.fire(
            `tasks/${this.localTask.id}/status/changed`,
            this.localTask.status
          );
          Event.fire(
            `tasks/${this.localTask.id}/time_sets/changed`,
            this.localTask.time_sets
          );
        })
        .catch(err => err.messages);
    }
  },
  created() {
    this.runTimer();

    if (this.localTask) {
      this.initialization();
    }

    Event.listen(`tasks/changed`, task => {
      this.localTask = task;
      this.initialization();
    });
  },
  watch: {
    task(val) {
      this.localTask = val;
    },
    duration(val) {
      //
    }
  },
  computed: {
    tasIskNew() {
      return this.localTask.status.name == "Новый";
    },
    taskIsPaused() {
      return this.localTask.status.name == "Приостановлен";
    },
    taskIsPlaying() {
      if (this.localTask) {
        if (this.localTask.time_sets.length) {
          return this.localTask.status.name == "В процессе";
        }
      } else {
        return false;
      }
    },
    tasIsClosed() {
      return this.localTask.status.name == "Закрытый";
    }
  }
};
</script>