<template>
    <v-container fluid>
        <v-row>
            <v-col md="3" v-for="(file,index) in items" :key="'file-'+index">
                <file-card :item="file" />
            </v-col>
        </v-row>
        <file-add />
    </v-container>
</template>
<script>
export default {
    props: {
        items: {}
    }
};
</script>