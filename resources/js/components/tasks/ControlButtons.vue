<template>
    <div>
        <v-card flat style="padding:2px 0px;" class="grey lighten-2 mb-2 text-center">
            {{moment(duration).hours() }} :
            {{moment(duration).minutes() }} :
            {{moment(duration).seconds() }}
        </v-card>
        <v-btn v-if="tasIskNew" class="mb-2" small depressed block color="blue" dark @click="play">
            <v-icon left>mdi-play</v-icon>Начать
        </v-btn>
        <v-btn
            v-if="taskIsPaused"
            class="mb-2"
            small
            depressed
            block
            color="amber darken-3"
            dark
            @click="play"
        >
            <v-icon left>mdi-play</v-icon>Продолжить
        </v-btn>
        <v-btn
            v-if="taskIsPlaying"
            class="mb-2"
            small
            depressed
            block
            color="blue-grey"
            dark
            @click="pause"
        >
            <v-icon left>mdi-pause</v-icon>Приостановить
        </v-btn>
        <v-btn
            v-if="taskIsPlaying || taskIsPaused"
            class="mb-2"
            small
            depressed
            block
            color="green"
            dark
            @click="stop"
        >
            <v-icon left>mdi-stop</v-icon>Завершить
        </v-btn>
    </div>
</template>
<script>
export default {
    props: {
        task: {
            required: true
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
        calculateTimeSets() {
            let sumOfDiffTime = 0;
            let from = 0;
            let to = 0;

            this.task.time_sets.forEach(time_set => {
                // Make last start time as a moment object
                from = this.moment(time_set.start_time);
                // if end time exists get it if not get current time
                to = time_set.end_time
                    ? this.moment(time_set.end_time)
                    : this.moment();
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
                    let task = response.data;
                    Event.fire("taskStatusChanged", task.status);
                    Event.fire("taskTimeSetsChanged", task.time_sets);
                })
                .catch(err => err.messages);
        },
        pause() {
            axios
                .get(this.appPath(`api/tasks/${this.localTask.id}/pause`))
                .then(response => {
                    let task = response.data;
                    Event.fire("taskStatusChanged", task.status);
                    Event.fire("taskTimeSetsChanged", task.time_sets);
                })
                .catch(err => err.messages);
        },
        stop() {
            axios
                .get(this.appPath(`api/tasks/${this.localTask.id}/stop`))
                .then(response => {
                    let task = response.data;
                    Event.fire("taskStatusChanged", task.status);
                    Event.fire("taskTimeSetsChanged", task.time_sets);
                })
                .catch(err => err.messages);
        }
    },
    created() {
        this.calculateTimeSets();
        this.runTimer();
    },
    watch: {
        task(val) {
            this.localTask = val;
        }
    },
    computed: {
        tasIskNew() {
            return this.task.time_sets.length == 0;
        },
        taskIsPaused() {
            return this.task.status.name == "Приостановлен";
        },
        taskIsPlaying() {
            if (this.task.time_sets.length) {
                return (
                    this.task.time_sets[this.task.time_sets.length - 1]
                        .end_time == null
                );
            } else {
                return false;
            }
        }
    }
};
</script>