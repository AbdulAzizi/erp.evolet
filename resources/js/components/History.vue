<template>
    <v-timeline dense flat >
        <v-timeline-item v-for="(historyItem, i) in reversedHistory" :key="i">
            <!-- <template v-slot:opposite>
                <span class="caption mb-0">{{getDate(historyItem.created_at)}}</span>
            </template> -->
            <template v-slot:icon>
                <a :href="`/users/${historyItem.user.id}`">
                    <v-avatar size="33" color="primary">
                        <img :src="thumb(historyItem.user.img)" :alt="historyItem.user.name" />
                    </v-avatar>
                </a>
            </template>
            <v-card>
                <v-card-text v-html="historyItem.description+'<br>'+getDate(historyItem.created_at)"></v-card-text>
            </v-card>
        </v-timeline-item>
    </v-timeline>
</template>

<script>
export default {
    props: {
        history: Array
    },
    methods: {
        getDate(date) {
            return this.moment(date).local().format("Do MMMM YYYY, HH:mm");
        }
    },
    computed: {
        // sortedHistory() {
        //     return this.history.sort((first, second) => {
        //         return (
        //             new Date(first.created_at) - new Date(second.created_at)
        //         );
        //     });
        // },
        reversedHistory(){
            return this.history.reverse();
        }
    }
};
</script>