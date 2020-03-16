<template>
    <v-row>
        <v-col cols="12">
            <v-data-table
                :headers="timesetHeaders"
                :items="localTimesets"
                item-key="id"
                hide-default-footer
                :items-per-page="-1"
                fixed-header
                dense
            >
                <template v-slot:item="{ item }">
                    <tr>
                        <td>{{item.task.responsibility_description.title}}</td>
                        <td>{{item.task.description}}</td>
                        <td>{{moment(item.start_time).local().format('LTS') }}</td>
                        <td>
                            <template v-if="item.end_time">{{moment(item.end_time).local().format('LTS') }}</template>
                            <template v-else></template>
                        </td>
                        <td>
                            <template v-if="item.diff">
                                {{moment(item.diff).hours() }} :
                                {{moment(item.diff).minutes() }} :
                                {{moment(item.diff).seconds() }}
                            </template>
                            <template v-else></template>
                        </td>
                    </tr>
                </template>
            </v-data-table>
        </v-col>
        <v-col cols="12">
            <v-data-table
                :headers="taskHeaders"
                :items="localTasks"
                item-key="id"
                hide-default-footer
                :items-per-page="-1"
                fixed-header
                dense
            >
                <template v-slot:item="{ item }">
                    <tr>
                        <td>{{item.responsibility_description.title}}</td>
                        <td>{{item.description}}</td>
                        <td>{{durObj(item.planned_time)}}</td>
                        <td>{{durObj(item.spent_time)}}</td>
                        <td :class="item.ratio < 100 ? 'green--text' : 'red--text'">{{item.ratio}}%</td>
                    </tr>
                </template>
            </v-data-table>
        </v-col>
    </v-row>
</template>
<script>
export default {
    props: {
        timesets: {
            required: true
        },
        tasks: {
            required: true
        }
    },
    data() {
        return {
            localTimesets: this.timesets,
            localTasks: this.tasks,
            timesetHeaders: [
                { text: "ДЗ", value: "task.responsibility_description.title" },
                { text: "Описание задачи", value: "task.description" },
                { text: "Начало", value: "start_time" },
                { text: "Конец", value: "end_time" },
                { text: "Всего", value: "diff" }
            ],
            taskHeaders: [
                { text: "ДЗ", value: "description" },
                { text: "Описание задачи", value: "responsibility_description.title" },
                { text: "Данно", value: "planned_time" },
                { text: "Потрачено", value: "spent_time" },
                { text: "Потрачено %", value: "ratio" }
            ]
        };
    },
    methods: {
        getTimeSetsDiff(timeSet) {
            if (timeSet.start_time && timeSet.end_time) {
                let from = this.moment(timeSet.start_time);
                let to = this.moment(timeSet.end_time);

                return to.diff(from);
            } else {
                return "";
            }
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
        }
    },
    created() {
        this.localTimesets.forEach(timeSet => {
            timeSet.diff = this.getTimeSetsDiff(timeSet);
        });

        let sumOfDiffTime = 0;
        let from = 0;
        let to = 0;
        this.localTasks.forEach(task => {
            task.time_sets.forEach(time_set => {
                // Make last start time as a moment object
                from = this.moment(time_set.start_time);
                // if end time exists get it if not get current time
                to = time_set.end_time
                    ? this.moment(time_set.end_time)
                    : this.moment();
                // Add diff time to sum
                sumOfDiffTime = sumOfDiffTime + to.diff(from);
            });
            task.spent_time = sumOfDiffTime;
            task.ratio = Math.round(task.spent_time / (task.planned_time / 100));
            sumOfDiffTime = 0;
        });
    }
};
</script>