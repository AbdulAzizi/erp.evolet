<template>
    <div class=" elevation-2"> 
        <v-tabs
        v-model="activeTab"
        color="primary"
        dark
        slider-color="white"
        >
            <v-tab
            v-for="(taskCategory,key) in alltasks"
            :key="'tab'+key "
            ripple
            >
                {{taskCategory.name}}
            </v-tab>
            
            <v-tab-item
                v-for="(taskCategory,key) in alltasks"
                :key="'tabItem'+key"
                class="grey lighten-4 pt-2"
            >
                <v-sheet tile class="grey lighten-4 pb-2 px-2" v-for="(task,key) in taskCategory.data" :key="'task'+key">
                    <v-sheet class="white px-2 py-1 grey--text text--darken-2">
                            {{task.title}}
                    </v-sheet>
                </v-sheet>
            </v-tab-item>
        </v-tabs>
    </div>
</template>

<script>
export default {
    props:['alltasks'],
    data(){
        return {
            activeTab:null
        }
    }
}
</script>

<style>

</style>
