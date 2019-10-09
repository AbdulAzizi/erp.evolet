<template>
  <p>
    <v-btn icon small v-if="play" @click="startTask">
      <v-icon>mdi-play-circle-outline</v-icon>
    </v-btn>
    <v-btn icon small v-if="pause" @click="pauseTask">
      <v-icon>mdi-pause-circle-outline</v-icon>
    </v-btn>
    <v-scroll-x-transition>
      <v-btn icon small v-if="stop" @click="stopTask">
        <v-icon>mdi-stop-circle</v-icon>
      </v-btn>
    </v-scroll-x-transition>
    <span>{{timer}}</span>
  </p>
</template>
<script>
export default {
  props: ["task"],
  data() {
    return {
      play:
        this.task.status_id == 1 ||
        (this.task.time_sets[this.task.time_sets.length - 1].end_time !==
          null &&
          this.task.status_id !== 3),
      pause: this.task.time_sets.length
        ? this.task.time_sets[this.task.time_sets.length - 1].end_time == null
        : false,
      stop: this.task.status_id == 2,
      timeSetId: this.task.time_sets.length
        ? this.task.time_sets[this.task.time_sets.length - 1].id
        : null,
      timer: null
    };
  },
  methods: {
    startTask() {
      axios
        .post("/api/start-task", {
          task_id: this.task.id
        })
        .then(response => {
          let responseData = response.data;
          // let time = this.moment().startOf("day");
          this.task.time_sets = responseData.time_sets;
          this.task.status_id = responseData.status_id;
          Event.fire("taskStarted", {});
          this.timeSetId =
            responseData.time_sets[responseData.time_sets.length - 1].id;
          this.play = false;
          this.pause = this.stop = true;
          // setInterval(timer => {
          //   this.timer = time.add(1, "s").format("LTS");
          // }, 1000);
        })
        .catch(err => err.messages);
    },
    pauseTask() {
      axios
        .put(`/api/pause-task/${this.timeSetId}`, {
          task_id: this.task.id
        })
        .then(response => {
          let responseData = response.data;
          this.task.time_sets = responseData.time_sets;
          let timeSet = this.task.time_sets[this.task.time_sets.length - 1];
          this.play = this.stop = true;
          this.pause = false;
        })
        .catch(err => err.messages);
    },
    stopTask() {
      axios
        .put(`/api/stop-task/${this.timeSetId}`, {
          task_id: this.task.id
        })
        .then(response => {
          console.log(response.data);
          Event.fire("stopTask", response.data);
          this.play = this.pause = this.stop = false;
        })
        .catch(err => err.messages);
    }
  },
  created() {}
};
</script>