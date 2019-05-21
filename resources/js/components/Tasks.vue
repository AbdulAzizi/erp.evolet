<template>
    <div>
        
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

            <v-spacer></v-spacer>

            <v-btn icon>
                <v-icon>view_list</v-icon>
            </v-btn>

            <v-tab-item
            v-for="(taskCategory,key) in alltasks"
            :key="'tabItem'+key"
            class="grey lighten-4 pt-2"
            >
                <!-- <v-sheet tile class="grey lighten-4 pb-2 px-2" v-for="(task,key) in taskCategory.data" :key="'task'+key">
                    <task :task="task"></task>
                </v-sheet> -->
                
                <!-- table was here -->

            </v-tab-item>
            
        </v-tabs>

        

        

    </div>           
</template>

<script>
export default {
    
    
    
}
</script>

<style>

</style>
