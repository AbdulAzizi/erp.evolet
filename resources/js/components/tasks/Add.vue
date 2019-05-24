<template>

    <v-dialog
    v-model="dialog"
    max-width="700"
    lazy
    >
        <template v-slot:activator="{ on }">
            <v-fab-transition>
                <v-btn
                v-on="on"
                dark
                fab
                fixed
                bottom
                right
                small
                color="primary"
                >
                    <v-icon>add</v-icon>
                </v-btn>
            </v-fab-transition>
        </template>

        <v-card>
            <v-toolbar flat color="primary" dark>
                <v-toolbar-title class="font-weight-regular">Новая задача</v-toolbar-title>
            </v-toolbar>

            <v-form action="" method="post">
                <v-container>
                    <v-layout row wrap>
                        <v-flex xs12>
                            <v-text-field
                                prepend-icon="content_paste"
                                label="Название"
                            ></v-text-field>
                        </v-flex>
                        <v-flex xs12>
                            <v-textarea
                                prepend-icon="description"
                                rows="3"
                                label="Описание"
                            ></v-textarea>
                        </v-flex>

                        <!-- <v-flex xs12 md6>
                            <user-selector :employees="employees" label="Ответственный" icon="person"></user-selector>
                        </v-flex> -->
                        <!-- <v-flex xs12 md6>
                            <user-selector :employees="employees" label="Наблюдатели"  icon="remove_red_eye"></user-selector>
                        </v-flex> -->
                        <!-- <v-flex xs12 md6>    
                            <v-subheader class="px-0 caption">Приоритет</v-subheader>
                            
                            <v-toolbar dense flat color="white" class="priority">
                                <v-btn-toggle 
                                v-model="task.priority" 
                                class="grey lighten-3"
                                >
                                    <v-btn 
                                    v-for="(priority,key) in priorities"
                                    :key="'priority'+key"  
                                    :value="key"
                                    flat
                                    >
                                        {{priority.label}}
                                    </v-btn>
                                </v-btn-toggle>
                            </v-toolbar>
                        </v-flex> -->
                        <!-- <v-flex xs12 md6>
                            <v-select
                            prepend-icon="flag"
                            :items="priorities"
                            item-text="label"
                            item-value="label"
                            label="Приоритет"
                            >
                                <template v-slot:item="{ item, index }">
                                    <v-icon class="mr-2" :color="item.color">flag</v-icon>
                                    <span>{{ item.label }}</span>
                                </template>

                                <template v-slot:selection="{ item, index }">
                                    <v-icon class="mr-2" :color="item.color">flag</v-icon>
                                    <span>{{ item.label }}</span>
                                </template>
                            
                            </v-select>
                        </v-flex>
                        <v-flex xs12 md6>
                            <v-combobox
                            prepend-icon="local_offer"
                            v-model="selectedTags"

                            :hide-no-data="!tagsSearch"
                            :items="tags"
                            :search-input.sync="tagsSearch"
                            hide-selected
                            label="Таги"
                            multiple
                            small-chips
                            >
                                <template v-slot:selection="{ item, parent, selected }">
                                    <v-chip
                                    color="primary"
                                    :selected="selected"
                                    dark
                                    small
                                    >
                                        <span class="pr-1">
                                            {{ item }}
                                        </span>
                                        <v-icon
                                        small
                                        @click="parent.selectItem(item)"
                                        >
                                            close
                                        </v-icon>
                                    </v-chip>
                                </template>
                            </v-combobox>
                        </v-flex>
                        <v-flex xs12 md6>
                            <v-switch
                                prepend-icon="timelapse"
                                v-model="estimateTaskSwitch"
                                color="primary"
                                label="Время на задачу"
                            ></v-switch>
                        </v-flex>
                        <v-flex xs12 md2>
                            <v-text-field v-if="estimateTaskSwitch"
                                label="Дни"
                            ></v-text-field>
                        </v-flex>
                        <v-flex xs12 md2>
                            <v-text-field v-if="estimateTaskSwitch"
                                label="Часы"
                            ></v-text-field>
                        </v-flex>
                        <v-flex xs12 md2>
                            <v-text-field v-if="estimateTaskSwitch"
                                label="Минуты"
                            ></v-text-field>
                        </v-flex>
                        <v-flex xs12 md6>
                            <v-switch
                                prepend-icon="event_available"
                                v-model="deadlineSwitch"
                                color="primary"
                                label="Дедлайн"
                            ></v-switch>
                        </v-flex>
                        <v-flex xs12 md6>
                            <v-menu
                            v-if="deadlineSwitch"
                            v-model="deadlineMenu"
                            :close-on-content-click="false"
                            :nudge-right="40"
                            lazy
                            transition="scale-transition"
                            offset-y
                            full-width
                            min-width="290px"
                            >
                                <template v-slot:activator="{ on }">
                                    <v-text-field
                                        v-model="deadline"
                                        label="Дедлайн"
                                        prepend-icon="event"
                                        readonly
                                        v-on="on"
                                    ></v-text-field>
                                </template>
                                <v-date-picker color="primary" v-model="deadline" @input="deadlineMenu = false"></v-date-picker>
                            </v-menu>
                        </v-flex>
                        <v-flex xs12 md6>
                            <v-switch
                                prepend-icon="repeat"
                                v-model="reapeatSwitch"
                                color="primary"
                                label="Повторение"
                            ></v-switch>

                            <v-dialog
                            v-model="reapeatSwitch"
                            width="500"
                            >

                            <v-card>
                                <v-card-title
                                class="headline grey lighten-2"
                                primary-title
                                >
                                Privacy Policy
                                </v-card-title>

                                <v-card-text>
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                                </v-card-text>

                                <v-divider></v-divider>

                                <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn
                                    color="primary"
                                    flat
                                    @click="dialog = false"
                                >
                                    I accept
                                </v-btn>
                                </v-card-actions>
                            </v-card>
                            </v-dialog>
                        </v-flex> -->
                    </v-layout>
                </v-container>
            </v-form>

            <v-card-actions>
                <v-flex>
                    <v-dialog
                    v-model="assigneeDialog"
                    width="500"
                    >
                        <template v-slot:activator="{ on:dialog }">
                            <v-tooltip top>
                                <template v-slot:activator="{ on:tooltip }">
                                    <v-btn v-on="{ ...tooltip, ...dialog }" flat round min-width="0" style="min-width:0" class="ma-0 grey--text px-2 text--darken-1">
                                        <v-icon :color="assignees.length ? 'primary' : '' " >person</v-icon>

                                        <v-avatar size="30" v-for="(assignee, key) in assignees" :key="'assignee-'+key">
                                            <img :src="'../img/'+assignee.img+'.jpg'">
                                        </v-avatar>
                                    </v-btn>
                                </template>
                                <span>Исполнители</span>
                            </v-tooltip>
                        </template>
                        <v-card>
                            <v-card-text>
                                <user-selector :employees="employees" name="assignees" label="Исполнители" icon="person"></user-selector>
                            </v-card-text>
                        </v-card>
                    </v-dialog>

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
                                            <img :src="'../img/'+watcher.img+'.jpg'">
                                        </v-avatar>
                                    </v-btn>
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

                    <v-dialog
                    v-model="prioritiesDialog"
                    width="500"
                    >
                        <template v-slot:activator="{ on:dialog }">
                            <v-tooltip top>
                                <template v-slot:activator="{ on:tooltip }">
                                    <v-btn v-on="{ ...tooltip, ...dialog }" flat round min-width="0" style="min-width:0" class="ma-0 grey--text px-2 text--darken-1">
                                        <v-icon :color="selectedPriority === null ? '' : priorities[selectedPriority].color ">flag</v-icon>
                                    </v-btn>
                                </template>
                                <span>Приоритет</span>
                            </v-tooltip>
                        </template>
                        <v-card>
                            <v-card-text>
                                <v-select
                                v-model="selectedPriority"
                                :items="priorities"
                                item-text="label"
                                item-value="id"
                                label="Приоритет"
                                >
                                    <template v-slot:item="{ item, index }">
                                        <v-icon :color="item.color" class="mr-2">flag</v-icon>
                                        <span>{{ item.label }}</span>
                                    </template>

                                    <template v-slot:selection="{ item, index }">
                                        <v-icon class="mr-2" :color="item.color">flag</v-icon>
                                        <span>{{ item.label }}</span>
                                    </template>
                                
                                </v-select>
                            </v-card-text>
                        </v-card>
                    </v-dialog>

                    <v-dialog
                    v-model="tagsDialog"
                    width="500"
                    >
                        <template v-slot:activator="{ on:dialog }">
                            <v-tooltip top>
                                <template v-slot:activator="{ on:tooltip }">
                                    <v-btn v-on="{ ...tooltip, ...dialog }" flat round min-width="0" style="min-width:0" class="ma-0 grey--text px-2 text--darken-1">
                                        <v-icon :color="selectedTags.length ? 'primary' : '' ">local_offer</v-icon>
                                    </v-btn>
                                </template>
                                <span>Таги</span>
                            </v-tooltip>
                        </template>
                        <v-card>
                            <v-card-text>
                                <v-combobox
                                prepend-icon="local_offer"
                                v-model="selectedTags"

                                :hide-no-data="!tagsSearch"
                                :items="tags"
                                :search-input.sync="tagsSearch"
                                hide-selected
                                label="Таги"
                                multiple
                                small-chips
                                >
                                    <template v-slot:selection="{ item, parent, selected }">
                                        <v-chip
                                        color="primary"
                                        :selected="selected"
                                        dark
                                        small
                                        >
                                            <span class="pr-1">
                                                {{ item }}
                                            </span>
                                            <v-icon
                                            small
                                            @click="parent.selectItem(item)"
                                            >
                                                close
                                            </v-icon>
                                        </v-chip>
                                    </template>
                                </v-combobox>
                            </v-card-text>
                        </v-card>
                    </v-dialog>

                    <v-dialog
                    v-model="estimateTaskDialog"
                    width="300"
                    >
                        <template v-slot:activator="{ on:dialog }">
                            <v-tooltip top>
                                <template v-slot:activator="{ on:tooltip }">
                                    <v-btn v-on="{ ...tooltip, ...dialog }" flat round min-width="0" style="min-width:0" class="ma-0 grey--text px-2 text--darken-1">
                                        <v-icon>timelapse</v-icon>
                                    </v-btn>
                                </template>
                                <span>Таги</span>
                            </v-tooltip>
                        </template>

                        <v-card>
                            <v-card-text>
                                <v-container grid-list-xl class="pa-0">
                                    <v-layout row wrap>
                                        <v-flex xs12 md4>
                                            <v-text-field
                                                label="Дни"
                                            ></v-text-field>
                                        </v-flex>
                                        <v-flex xs12 md4>
                                            <v-text-field
                                                label="Часы"
                                            ></v-text-field>
                                        </v-flex>
                                        <v-flex xs12 md4>
                                            <v-text-field
                                                label="Минуты"
                                            ></v-text-field>
                                        </v-flex>
                                    </v-layout>
                                </v-container>
                            </v-card-text>
                        </v-card>
                    </v-dialog>

                    <v-btn flat round min-width="0" style="min-width:0" class="ma-0 grey--text px-2 text--darken-1">
                        <v-icon>event_available</v-icon>
                    </v-btn>
                    <v-btn flat round min-width="0" style="min-width:0" class="ma-0 grey--text px-2 text--darken-1">
                        <v-icon>repeat</v-icon>
                    </v-btn>
                </v-flex>

                <v-spacer></v-spacer>
                
                <v-btn
                color="primary"
                flat
                @click="dialog = false"
                >
                    Добавить
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
    
</template>

<script>
export default {
    props:['employees'],
    data(){
        return {
            watchersDialog:false,
            watchers:[],

            assigneeDialog:false,
            assignees:[],
            
            reapeatSwitch:false,

            estimateTaskDialog:false,

            deadlineMenu:false,
            deadline:null,
            deadlineSwitch:false,
            deadlineDialog:false,

            tagsDialog:false,
            tags:['Таг1','Таг2','Таг3'],
            selectedTags:[],
            tagsSearch:null,

            task:{
                title:null,
                priority:1
            },
            dialog:false,

            selectedPriority:null,
            prioritiesDialog:false,
            priorities:[
				{ id:0, label:"Низкий", color:"light-blue lighten-4" },
				{ id:1, label:"Средний", color:"amber lighten-1" },
				{ id:2, label:"Высокий", color:"red" }
			]
        }
    },
    created(){
        Event.listen('assignees',(data)=>{
            this.assignees = data;
        });

        Event.listen('watchers',(data)=>{
            this.watchers = data;
        });
    },
    // watch:{
    //     selectedPriority(value){
    //         console.log(value);
    //     }
    // }
}
</script>

<style>
.priority .v-toolbar__content{
    padding-left: 0;
}
.priority .v-btn--active{
    background-color: #b8cf41;
}
.priority .v-btn--active .v-btn__content{
    color: white;
}
</style>
