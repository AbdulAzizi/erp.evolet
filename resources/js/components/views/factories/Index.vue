<template>
    <div>
        <v-container fluid>
            <v-row>
                <v-col cols="4" v-for="(item, index) in items" :key="'factory-'+index">
                    <factories-vertical-card :item="item"/>
                </v-col>
            </v-row>
        </v-container>

        <v-btn :href="appPath('factories/create')" right bottom fixed fab dark color="primary">
            <v-icon>mdi-plus</v-icon>
        </v-btn>
    </div>
</template>
<script>
export default {
    props: {
        items: {}
    }
};
</script>
<style>
</style>