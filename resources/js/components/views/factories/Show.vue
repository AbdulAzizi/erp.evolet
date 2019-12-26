<template>
    <v-container fluid>
        <v-row>
            <v-col cols="9">
                <factories-product-table :items="item.products"/>
            </v-col>
            <v-col cols="3">
                <factories-card :item="item" />
            </v-col>
        </v-row>
    </v-container>
</template>
<script>
export default {
    props: {
        item: {}
    },
    created(){
        console.log(this.item.products[0].fields);
        
    }
};
</script>
<style>
</style>