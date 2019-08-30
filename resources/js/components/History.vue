<template>
    <v-timeline dense>
        <v-timeline-item v-for="(historyItem, i) in sortedHistory" :key="i">
            <!-- <template v-slot:opposite>
                <span class="caption mb-0">{{getDate(historyItem.happened_at)}}</span>
            </template> -->
            <template v-slot:icon>
                <a :href="`/users/${historyItem.user.id}`">
                    <v-avatar size="33" color="primary">
                        <img :src="photo(historyItem.user.img)" :alt="historyItem.user.name" />
                    </v-avatar>
                </a>
            </template>
            <v-card class="elevation-2">
                <v-card-text v-html="historyItem.description+'<br>'+getDate(historyItem.happened_at)"></v-card-text>
            </v-card>
        </v-timeline-item>
    </v-timeline>
</template>

<script>
export default {
    props: {
        history: Array
    },
    data() {
        return {};
    },
    methods: {
        getDate(date) {
            return this.moment(date).local().format("Do MMMM YYYY, HH:mm");
        }
    },
    computed: {
        sortedHistory() {
            return this.history.sort((first, second) => {
                return (
                    new Date(first.happened_at) - new Date(second.happened_at)
                );
            });
        }
    }
};
</script>