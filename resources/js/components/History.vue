<template>
    <v-timeline>
        <v-timeline-item v-for="(historyItem, i) in sortedHistory" :key="i">
            <template v-slot:opposite>
                <span class="caption mb-0">{{getDate(historyItem.happened_at)}}</span>
            </template>
            <template v-slot:icon>
                <v-avatar size="33" color="primary">
                    <img :src="photo(historyItem.user.img)" :alt="historyItem.user.name" />
                </v-avatar>
            </template>
            <v-card class="elevation-2">
                <v-card-text>{{historyItem.description}}</v-card-text>
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
        console.log(this.history);
        return {};
    },
    methods: {
        getDate(date) {
            return this.moment(date).format("Do MMMM YYYY, hh:mm");
        }
    },
    computed:{
        sortedHistory(){
            return this.history.sort((first, second) => {
                return new Date(first.happened_at) - new Date(second.happened_at);
            });
        }
    }
};
</script>