<template>
    <!-- <div>
        <v-sheet height="600">
            <v-calendar
                ref="calendar"
                v-model="value"
                :weekdays="weekday"
                :type="type"
                :events="items"
                :event-overlap-mode="mode"
                :event-overlap-threshold="30"
                :event-color="getEventColor"
                @change="getEvents"
                :first-interval="50"
                :interval-minutes="10"
                :interval-count="56"
                :interval-height="100"
                :interval-style="intervalStyle"
            ></v-calendar>
        </v-sheet>
    </div>-->
    <v-card>
        <timeline :items="localItems" :groups="localUsers" :options="options"></timeline>
    </v-card>
</template>
<script>
export default {
    props: {
        timesets: {
            required: true
        },
        users: {
            required: true
        }
    },
    data() {
        return {
            localUsers: [],
            localItems: [],
            groups: [
                {
                    id: 0,
                    content: "Group 1",
                    fklmlksdmfls: "sdfdsfsdfsd"
                },
                {
                    id: 1,
                    content: "Group 1"
                },
                {
                    id: 2,
                    content: "Group 1"
                },
                {
                    id: 3,
                    content: "Group 1"
                },
                {
                    id: 4,
                    content: "Group 1"
                }
            ],
            items: [
                {
                    id: 0,
                    group: 1,
                    start: new Date(),
                    content: "Item 1"
                }
            ],
            options: {
                editable: false,
                template: function(item, element, data) {
                    return `<span></span>`;
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
            ]
        };
    },
    created() {
        console.log(this.rand(1, 10));
        this.prepareData();
    },
    methods: {
        prepareData() {
            this.localUsers = this.users.map(user => {
                return {
                    id: user.id,
                    content: user.fullname,
                    color: this.colors[this.rand(0, this.colors.length - 1)]
                };
            });

            this.localItems = this.timesets.map(timeset => {
                return {
                    id: timeset.id,
                    group: timeset.task.responsible_id,
                    start: timeset.start_time,
                    end: timeset.end_time,
                    content: timeset.start_time + " " + timeset.end_time,
                    className: this.localUsers.filter(user => {
                        return user.id == timeset.task.responsible_id;
                    })[0].color
                };
            });
        },
        rand(min, max) {
            return Math.floor(Math.random() * (max - min + 1)) + min;
        }
    }
};
// export default {
//     props: {
//         timesets: {
//             required: true
//         }
//     },
//     data: () => ({
//         type: "day",
//         types: ["month", "week", "day", "4day"],
//         mode: "stack",
//         modes: ["stack", "column"],
//         weekday: [0, 1, 2, 3, 4, 5, 6],
//         weekdays: [
//             { text: "Sun - Sat", value: [0, 1, 2, 3, 4, 5, 6] },
//             { text: "Mon - Sun", value: [1, 2, 3, 4, 5, 6, 0] },
//             { text: "Mon - Fri", value: [1, 2, 3, 4, 5] },
//             { text: "Mon, Wed, Fri", value: [1, 3, 5] }
//         ],
//         value: "",
//         events: [],
//         colors: [
//             "blue",
//             "indigo",
//             "deep-purple",
//             "cyan",
//             "green",
//             "orange",
//             "grey darken-1"
//         ],
//         names: [
//             "Meeting",
//             "Holiday",
//             "PTO",
//             "Travel",
//             "Event",
//             "Birthday",
//             "Conference",
//             "Party"
//         ]
//     }),
//     methods: {
//         getEvents({ start, end }) {
//             const events = [];

//             const min = new Date(`${start.date}T00:00:00`);
//             const max = new Date(`${end.date}T23:59:59`);
//             const days = (max.getTime() - min.getTime()) / 86400000;
//             const eventCount = this.rnd(days, days + 20);

//             for (let i = 0; i < eventCount; i++) {
//                 const allDay = this.rnd(0, 3) === 0;
//                 const firstTimestamp = this.rnd(min.getTime(), max.getTime());
//                 const first = new Date(
//                     firstTimestamp - (firstTimestamp % 900000)
//                 );
//                 const secondTimestamp = this.rnd(2, allDay ? 288 : 8) * 900000;
//                 const second = new Date(first.getTime() + secondTimestamp);

//                 events.push({
//                     name: this.names[this.rnd(0, this.names.length - 1)],
//                     start: this.formatDate(first, !allDay),
//                     end: this.formatDate(second, !allDay),
//                     color: this.colors[this.rnd(0, this.colors.length - 1)]
//                 });
//             }
//             this.events = events;
//         },
//         getEventColor(event) {
//             return event.color;
//         },
//         rnd(a, b) {
//             return Math.floor((b - a + 1) * Math.random()) + a;
//         },
//         formatDate(a, withTime) {
//             return withTime
//                 ? `${a.getFullYear()}-${a.getMonth() +
//                       1}-${a.getDate()} ${a.getHours()}:${a.getMinutes()}`
//                 : `${a.getFullYear()}-${a.getMonth() + 1}-${a.getDate()}`;
//         }
//     },
//     created() {
//         console.log(this.items);
//     },
//     watch: {
//         events(val) {
//             console.log(val);
//         }
//     },
//     computed: {
//         items() {
//             let items = [];
//             this.timesets["11"].forEach(timeset => {
//                 items.push({
//                     name: "timeset.id",
//                     start: timeset.start_time.slice(0, -3),
//                     end: timeset.end_time.slice(0, -3),
//                     color: 'red'
//                 });
//             });
//             return items;
//         }
//     }
// };
</script>
<style>
</style>