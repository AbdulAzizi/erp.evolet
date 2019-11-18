<template>
    <div>
        <!-- <v-dialog v-model="taskDialog" max-width="1000">
            <task :item="selectedTask" :users="users"></task>
        </v-dialog>-->

        <v-data-table
            :headers="headers"
            :items="localTasks"
            class="elevation-1 tasks-table"
            item-key="id"
            hide-default-footer
            @click:row="displayTask"
            :items-per-page="-1"
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
            <template v-slot:item.tags="{ item }">
                <v-chip class="primary black--text mr-1" x-small v-for="(tag,index) in item.tags" :key="'tag-'+index">
                    {{tag.name}}
                </v-chip>
            </template>
        </v-data-table>
    </div>
</template>

<script>
export default {
    props: {
        tasks: Array,
        users: Array
    },
    data() {
        return {
            localTasks: this.tasks,
            headers: [
                { text: "Задача", value: "title" },
                { text: "Приоритет", value: "priority" },
                { text: "Время на задачу", value: "planned_time" },
                { text: "Дедлайн", value: "deadline" },
                { text: "От", value: "from", sort: false },
                { text: "CreatedAt", value: "created_at" },
                { text: "Статус", value: "status" },
                { text: "Таги", value: "tags" },
            ],
            selectedTask: null,
            taskDialog: false
        };
    },
    methods: {
        displayTask(task) {
            window.location.href = 'tasks/'+task.id;

            // this.selectedTask = task;
            // this.taskDialog = true;
        },
        duration() {}
    },
    created() {
        Event.listen('taskStatusChanged', data => {

            this.localTasks.forEach( ( task, index) => {

                if (task.id == data.id){
                    this.localTasks[index] = data;
                }
                
            });            
            
        });
    },
    watch:{
        tasks(val){
            this.localTasks = val;
        }
    }
};
</script>

<style>
.tasks-table {
    cursor: pointer;
}
</style>
