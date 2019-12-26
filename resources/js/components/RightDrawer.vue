<template>
<div>
    <v-btn right fab text fixed color="primary" @click="drawer = true">
      <v-icon>
        mdi-chevron-left
      </v-icon>
    </v-btn>
    <v-navigation-drawer 
        v-model="drawer"
        temporary
        right
        absolute
        class="elevation-3 right-drawer"
        width="150"
    >
    <v-list>
        <v-list-item v-for="(item, index) in items" :key="index" :href="item.url"> 
           {{item.title}}
        </v-list-item>
    </v-list>
    </v-navigation-drawer>
</div>
</template>
<script>
export default {
    props: {
        users: {
            required: false
        }
    },
    data() {
        return {
            items: [
                { title: "Формы", url: "/forms" },
            ],
            drawer: false
        };
    }
};
</script>
<style>
    .right-drawer{
        z-index: 4
    }
</style>