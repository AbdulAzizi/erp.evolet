<template>
    <v-dialog
    v-model="watchersDialog"
    width="500"
    >
        <template v-slot:activator="{ on:dialog }">
            <v-tooltip top>
                <template v-slot:activator="{ on:tooltip }">
                    
                    <v-btn v-on="{ ...tooltip, ...dialog }" flat round min-width="0" style="min-width:0" class="ma-0 grey--text px-2 text--darken-1">
                        <v-icon :color="watchers.length ? 'primary' : '' " >remove_red_eye</v-icon>

                        <v-avatar size="30" v-for="(watcher, key) in watchers" :key="'watcher-'+key">
                            <img :src="photo(watcher.user.img)">
                        </v-avatar>
                    </v-btn>

                    <input type="hidden" name="watchers" :value="JSON.stringify(pluck(watchers, 'id'))">

                </template>
                <span>Наблюдатели</span>
            </v-tooltip>
        </template>
        <v-card>
            <v-card-text>
                <user-selector :employees="employees" name="watchers" label="Наблюдатели" icon="remove_red_eye"></user-selector>
            </v-card-text>
        </v-card>
    </v-dialog>

</template>

<script>
export default {
    props:['employees'],
    data(){
        return{
            watchersDialog:false,
            watchers:[],
        }
    },
    created(){
        Event.listen('watchers',(data)=>{
            this.watchers = data;
        });
    }
}
</script>

<style>

</style>
