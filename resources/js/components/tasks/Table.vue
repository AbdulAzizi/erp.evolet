<template>
    <div>
        <v-dialog v-model="taskDialog" max-width="700">
            <task :item="selectedTask"></task>
        </v-dialog>

        <v-data-table
            :headers="headers"
            :items="tasks"
            class="elevation-1"
            item-key="id"
            hide-default-footer
            @click:row="displayTask"
        >
            <template v-slot:item.priority="{ item }">
                <priority :id="item.priority" icon></priority>
            </template>

            <template v-slot:item.planned_time="{ item }">
                <span
                    v-if="durObj(item.planned_time).days()"
                >{{ durObj(item.planned_time).days() }}д</span>
                <span
                    v-if="durObj(item.planned_time).hours()"
                >{{ durObj(item.planned_time).hours() }}ч</span>
                <span
                    v-if="durObj(item.planned_time).minutes()"
                >{{ durObj(item.planned_time).minutes() }}м</span>
            </template>

            <template v-slot:item.deadline="{ item }">{{ moment(item.deadline).fromNow() }}</template>

            <template v-slot:item.from="{ item }">
                <avatar :user="item.from" />
            </template>

            <template v-slot:item.created_at="{ item }">{{ moment(item.created_at).fromNow() }}</template>

            <template v-slot:item.status="{ item }">{{ item.status.name }}</template>
        </v-data-table>
    </div>
</template>

<script>
export default {
    props: ["tasks"],
    data() {
        return {
            headers: [
                { text: "Задача", value: "title" },
                // { text: 'Статус', value: 'status' },
                { text: "Приоритет", value: "priority" },
                { text: "Время на задачу", value: "planned_time" },
                { text: "Дедлайн", value: "deadline" },
                { text: "От", value: "from", sort: false },
                { text: "CreatedAt", value: "created_at" },
                { text: "Статус", value: "status" }
            ],
            selectedTask:null,
            taskDialog:false
        };
    },
    methods: {
        displayTask(task) {
            console.log(task);
            this.selectedTask = task;
            this.taskDialog = true;
        },
        duration() {}
    },
    created() {
    }
};
</script>

<style>
</style>
