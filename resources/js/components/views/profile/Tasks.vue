<template>
    <v-row>
        <v-col cols="12" class="pt-0">
            <v-data-table
                :headers="timesetHeaders"
                :items="localTimesets"
                item-key="id"
                hide-default-footer
                :items-per-page="-1"
                fixed-header
                dense
            >
                <template v-slot:top>
                    <v-card-title
                        class="py-1 subtitle-1"
                    >История потраченного времени за этот период</v-card-title>
                </template>

                <template v-slot:item="{ item }">
                    <tr>
                        <td>{{item.task.responsibility_description.title}}</td>
                        <td>{{item.task.description}}</td>
                        <td>{{moment(item.start_time).local().format('LTS') }}</td>
                        <td>
                            <template
                                v-if="item.end_time"
                            >{{moment(item.end_time).local().format('LTS') }}</template>
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
        <v-col cols="12" class="pt-0">
            <v-data-table
                :headers="taskHeaders"
                :items="localTasks"
                item-key="id"
                hide-default-footer
                :items-per-page="-1"
                fixed-header
                dense
            >
                <template v-slot:top>
                    <v-card-title class="py-1 subtitle-1">Завершенные задачи за этот период</v-card-title>
                </template>
                <template v-slot:item="{ item }">
                    <tr>
                        <td>{{item.responsibility_description.title}}</td>
                        <td>{{item.description}}</td>
                        <td>{{item.deadline}}</td>
                        <td>{{item.ended_time}}</td>
                        <td
                            :class="item.deadline > item.time_sets[item.time_sets.length-1].end_time ? 'green--text' : 'red--text'"
                        >{{ item.deadline_endtime }}</td>
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
                { text: "ДЗ", value: "responsibility_description.title" },
                {
                    text: "Описание задачи",
                    value: "description"
                },
                { text: "Дедлайн", value: "deadline" },
                { text: "Завершено", value: "ended_time" },
                { text: "Учпеваймось", value: "deadline_endtime" },
                { text: "Дано", value: "planned_time" },
                { text: "Потрачено", value: "spent_time" },
                { text: "Потрачено %", value: "ratio" }
            ]
        };
    },
    methods: {
        prepareTasks() {
            let totalSpentTime = 0;
            let from = 0;
            let to = 0;

            this.localTasks.forEach(tsk => {
                // loop through
                tsk.time_sets.forEach(time_set => {
                    // Make last start time as a moment object
                    from = this.moment(time_set.start_time);
                    // if end time exists get it if not get current time
                    to = time_set.end_time
                        ? this.moment(time_set.end_time)
                        : this.moment();
                    // Add diff time to sum
                    totalSpentTime = totalSpentTime + to.diff(from);
                });
                // set total spent time
                tsk.spent_time = totalSpentTime;
                // set ratio
                tsk.ratio = Math.round(
                    tsk.spent_time / (tsk.planned_time / 100)
                );
                // set total spent time to 0
                totalSpentTime = 0;
                // set end time
                tsk.ended_time =
                    tsk.time_sets[tsk.time_sets.length - 1].end_time;
                // subtruct deadline form endtime
                tsk.deadline_endtime = this.getDiff(
                    tsk.time_sets[tsk.time_sets.length - 1].end_time,
                    tsk.deadline
                );
            });
        },
        getTimeSetsDiff(timeSet) {
            if (timeSet.start_time && timeSet.end_time) {
                let from = this.moment(timeSet.start_time);
                let to = this.moment(timeSet.end_time);

                return to.diff(from);
            } else {
                return "";
            }
        },
        getDiff(time1, time2) {
            let t1 = this.moment(time1);
            let t2 = this.moment(time2);
            let diffInMill = this.moment.duration(t2.diff(t1));
            return `${diffInMill.days()}д ${diffInMill.hours()}ч ${diffInMill.minutes()}м`;
        }
    },
    created() {
        this.localTimesets.forEach(timeSet => {
            timeSet.diff = this.getTimeSetsDiff(timeSet);
        });
        this.prepareTasks();
    }
};
</script>