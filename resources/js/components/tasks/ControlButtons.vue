<template>
    <v-row class="ma-0" v-if="localTask" justify="end">
        <v-col :class="horizontalButtons ? 'pa-2':'pa-0'" :cols="horizontalButtons ? 4:12">
            <v-card
                flat
                style="padding:2px 0px; width:100%;"
                class="grey lighten-2 mb-2 text-center"
            >
                {{moment(duration).hours() }} :
                {{moment(duration).minutes() }} :
                {{moment(duration).seconds() }}
            </v-card>
        </v-col>
        <v-col
            v-if="tasIskNew"
            :class="horizontalButtons ? 'pa-2':'pa-0'"
            :cols="horizontalButtons ? 4:12"
        >
            <v-btn class="mb-2" small depressed block color="blue" dark @click="play">
                <v-icon left>mdi-play</v-icon>Начать
            </v-btn>
        </v-col>
        <v-col
            v-if="taskIsPaused"
            :class="horizontalButtons ? 'pa-2':'pa-0'"
            :cols="horizontalButtons ? 4:12"
        >
            <v-btn class="mb-2" small depressed block color="amber darken-3" dark @click="play">
                <v-icon left>mdi-play</v-icon>Продолжить
            </v-btn>
        </v-col>
        <v-col
            v-if="taskIsPlaying"
            :class="horizontalButtons ? 'pa-2':'pa-0'"
            :cols="horizontalButtons ? 4:12"
        >
            <v-btn class="mb-2" small depressed block color="blue-grey" dark @click="pause">
                <v-icon left>mdi-pause</v-icon>Приостановить
            </v-btn>
        </v-col>
        <v-col
            v-if="taskIsPlaying || taskIsPaused"
            :class="horizontalButtons ? 'pa-2':'pa-0'"
            :cols="horizontalButtons ? 4:12"
        >
            <v-btn class="mb-2" small depressed block color="green" dark @click="stop">
                <v-icon left>mdi-stop</v-icon>Завершить
            </v-btn>
        </v-col>
    </v-row>
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
            Event.listen(
                `tasks/${this.localTask.id}/status/changed`,
                status => {
                    this.localTask.status = status;
                }
            );
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
        }
    },
    computed: {
        tasIskNew() {
            return this.localTask.time_sets.length == 0;
        },
        taskIsPaused() {
            return this.localTask.status.name == "Приостановлен";
        },
        taskIsPlaying() {
            if (this.localTask.time_sets.length) {
                return (
                    this.localTask.time_sets[
                        this.localTask.time_sets.length - 1
                    ].end_time == null
                );
            } else {
                return false;
            }
        }
    }
};
</script>