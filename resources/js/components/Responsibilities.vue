<template>
    <div>
        <v-row>
            <v-col cols="6" v-for="(responsibility, index ) in responsibilities" :key="index">
                <responsibility-card :responsibility="responsibility" />
            </v-col>
        </v-row>
    </div>
</template>

<script>
export default {
    props: {
        responsibilities: {}
    }
};
</script>