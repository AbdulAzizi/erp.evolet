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
            <v-form action="/tasks" method="post">

                <input type="hidden" name="_token" :value="csrf_token">

                <v-toolbar flat color="primary" dark>
                    <v-toolbar-title class="font-weight-regular">Новая задача</v-toolbar-title>
                </v-toolbar>

            
                <v-container>
                    <v-layout row wrap>
                        <v-flex xs12>
                            <v-text-field
                                prepend-icon="content_paste"
                                label="Название"
                                name="title"
                            ></v-text-field>
                        </v-flex>
                        <v-flex xs12>
                            <v-textarea
                                prepend-icon="description"
                                rows="3"
                                label="Описание (необязательное)"
                                name="description"
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
                                                <img :src="'../img/'+assignee.user.img+'.jpg'">
                                            </v-avatar>
                                        </v-btn>

                                        <input type="hidden" name="assignees" :value="JSON.stringify(pluck(assignees, 'id'))">
                                    
                                    </template>
                                    <span>Исполнители</span>
                                </v-tooltip>
                            </template>
                            <v-card>
                                <v-card-text>
                                    <user-selector 
                                    :employees="employees"
                                    name="assignees" 
                                    label="Исполнители" 
                                    icon="person"
                                    hint="У каждого исполнителя будет своя отдельная задача"
                                    ></user-selector>
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
                                                <img :src="'../img/'+watcher.user.img+'.jpg'">
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
                                        <input type="hidden" name="priority" :value="selectedPriority">
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
                                            <span>
                                                <span v-for="(selectedTag, key) in selectedTags" :key="'selectedTag-'+key"> {{ selectedTag }} </span>
                                            </span>
                                        </v-btn>

                                        <input type="hidden" name="tags" :value="JSON.stringify(selectedTags)">
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
                                            <v-icon :color="(estimateDays || estimateHours || estimateMinutes) ? 'primary' : '' ">timelapse</v-icon>
                                            <span class="text-lowercase">
                                                <span v-if="estimateDays">{{ estimateDays }}д</span>
                                                <span v-if="estimateHours"> {{ estimateHours }}ч</span>
                                                <span v-if="estimateMinutes"> {{ estimateMinutes }}м</span>
                                            </span>
                                        </v-btn>

                                        <input type="hidden" name="estimatedTaskTime" :value="estimateTime">
                                    </template>
                                    <span>Время на задачу</span>
                                </v-tooltip>
                            </template>

                            <v-card>
                                <v-card-text>
                                    <v-container grid-list-xl class="pa-0">
                                        <v-layout row wrap>
                                            <v-flex xs12 md4>
                                                <v-text-field
                                                    type="number"
                                                    v-model="estimateDays"
                                                    label="Дни"
                                                    min="1"
                                                ></v-text-field>
                                            </v-flex>
                                            <v-flex xs12 md4>
                                                <v-text-field
                                                    type="number"
                                                    v-model="estimateHours"
                                                    label="Часы"
                                                    min="1"
                                                ></v-text-field>
                                            </v-flex>
                                            <v-flex xs12 md4>
                                                <v-text-field
                                                    type="number"
                                                    v-model="estimateMinutes"
                                                    label="Минуты"
                                                    min="1"
                                                ></v-text-field>
                                            </v-flex>
                                        </v-layout>
                                    </v-container>
                                </v-card-text>
                            </v-card>
                        </v-dialog>

                        <v-dialog
                        v-model="deadlineDialog"
                        ref="deadlineDialog"
                        width="290px"
                        lazy
                        >
                            <template v-slot:activator="{ on:dialog }">
                                <v-tooltip top>
                                    <template v-slot:activator="{ on:tooltip }">
                                        <v-btn v-on="{ ...tooltip, ...dialog }" flat round min-width="0" style="min-width:0" class="ma-0 grey--text px-2 text--darken-1">
                                            <v-icon :color="deadline ? 'primary' : '' ">event</v-icon>
                                            {{ deadline }}
                                        </v-btn>

                                        <input type="hidden" name="deadline" v-model="deadline">

                                    </template>
                                    <span>Дедлайн</span>
                                </v-tooltip>
                            </template>

                            <v-date-picker v-model="deadline" scrollable color="primary">
                                <v-spacer></v-spacer>
                                <v-btn flat color="primary" @click="deadlineDialog = false">Отмена</v-btn>
                                <v-btn flat color="primary" @click="$refs.deadlineDialog.save(deadline)">Выбрать</v-btn>
                            </v-date-picker>
                            
                        </v-dialog>

                        <v-dialog
                        v-model="reapeatTaskDialog"
                        width="600"
                        lazy
                        >
                            <template v-slot:activator="{ on:dialog }">
                                <v-tooltip top>
                                    <template v-slot:activator="{ on:tooltip }">
                                        <v-btn v-on="{ ...tooltip, ...dialog }" flat round min-width="0" style="min-width:0" class="ma-0 grey--text px-2 text--darken-1">
                                            <v-icon>repeat</v-icon>
                                        </v-btn>
                                    </template>
                                    <span>Повторение</span>
                                </v-tooltip>
                            </template>

                            <v-card class="grey lighten-3">
                                <v-container grid-list-md>
                                    <v-layout row  >
                                        <v-flex xs3>
                                            <v-subheader class="justify-end">каждый</v-subheader>
                                        </v-flex>
                                        <v-flex xs2>
                                            <v-text-field
                                            type="number"
                                            v-model="intervalNumber"
                                            min="1"
                                            single-line
                                            solo
                                            class="text-xs-center"
                                            ></v-text-field>
                                        </v-flex>
                                        <v-flex xs7>
                                            <v-select
                                            v-model="selectedInterval"
                                            :items="timeIntervals[ selectedIntervals ]"
                                            item-value="index"
                                            item-text="name"
                                            return-object
                                            solo
                                            ></v-select>
                                        </v-flex>
                                    </v-layout>
                                    <v-layout row>
                                        <v-flex xs3>
                                            <v-subheader class="justify-end">в</v-subheader>
                                        </v-flex>
                                        <v-flex xs9>
                                            <v-dialog
                                            ref="dialog"
                                            v-model="reapeatTaskTimeDialog"
                                            :return-value.sync="time"
                                            persistent
                                            lazy
                                            full-width
                                            width="290px"
                                            >
                                                <template v-slot:activator="{ on }">
                                                    <v-text-field
                                                    v-model="time"
                                                    label="Выберите время"
                                                    readonly
                                                    single-line
                                                    prepend-inner-icon="access_time"
                                                    solo
                                                    v-on="on"
                                                    ></v-text-field>
                                                </template>
                                                <v-time-picker
                                                    v-if="reapeatTaskTimeDialog"
                                                    v-model="time"
                                                    color="primary"
                                                    full-width
                                                >
                                                    <v-spacer></v-spacer>
                                                    <v-btn flat color="primary" @click="reapeatTaskTimeDialog = false">Cancel</v-btn>
                                                    <v-btn flat color="primary" @click="$refs.dialog.save(time)">OK</v-btn>
                                                </v-time-picker>
                                            </v-dialog>
                                        </v-flex>
                                    </v-layout>
                                    <v-layout row>
                                        <v-flex xs3>
                                            <v-subheader class="justify-end">заканчивается</v-subheader>
                                        </v-flex>
                                        <v-flex v-bind="endTimeMenuSizes[endTime.index]">
                                            <v-select
                                            v-model="endTime"
                                            :items="endTimeMenu"
                                            item-value="index"
                                            item-text="label"
                                            return-object
                                            solo
                                            ></v-select>
                                        </v-flex>

                                        <v-flex v-if="endTime.index == 1" xs3>
                                            <v-text-field
                                            type="number"
                                            v-model="endsAfterTimes"
                                            min="1"
                                            single-line
                                            solo
                                            ></v-text-field>
                                        </v-flex>
                                        <v-flex v-if="endTime.index == 1" xs3>
                                            <v-subheader>раза</v-subheader>
                                        </v-flex>

                                        <v-flex v-if="endTime.index == 2" xs5>
                                            <v-dialog
                                            ref="dialog"
                                            v-model="endsOnDateDialog"
                                            :return-value.sync="endsOnDate"
                                            persistent
                                            lazy
                                            full-width
                                            width="290px"
                                            >
                                                <template v-slot:activator="{ on }">
                                                    <v-text-field
                                                        v-model="endsOnDate"
                                                        label="Выберите день"
                                                        prepend-inner-icon="event"
                                                        readonly
                                                        solo
                                                        v-on="on"
                                                    ></v-text-field>
                                                </template>
                                                <v-date-picker v-model="endsOnDate" scrollable>
                                                    <v-spacer></v-spacer>
                                                    <v-btn flat color="primary" @click="modal = false">Cancel</v-btn>
                                                    <v-btn flat color="primary" @click="$refs.dialog.save(endsOnDate)">OK</v-btn>
                                                </v-date-picker>
                                            </v-dialog>
                                        </v-flex>

                                    </v-layout>
                                </v-container>
                            </v-card>

                        </v-dialog>
                    </v-flex>

                    <v-spacer></v-spacer>
                    
                    <v-btn
                    color="primary"
                    flat
                    type="submit"
                    >
                        Добавить
                    </v-btn>
                </v-card-actions>
            </v-form>
        </v-card>
    </v-dialog>
    
</template>

<script>
export default {
    props:['employees'],
    data(){
        return {
            csrf_token: window.Laravel.csrf_token,

            watchersDialog:false,
            watchers:[],

            assigneeDialog:false,
            assignees:[],
            
            reapeatSwitch:false,

            estimateTaskDialog:false,
            estimateDays:null,
            estimateHours:null,
            estimateMinutes:null,
            estimateTime:null,

            deadlineDialog:false,
            deadline:null,

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
            ],

            reapeatTaskDialog:false,
            reapeatTaskTimeDialog:false,
            
            intervalNumber:1,

            selectedInterval: { index:0, name: 'день' },
            selectedIntervals: 0,
            
            time:null,
            timeIntervals:[
                [ 
                    { index:0, name: 'день' }, 
                    { index:1, name: 'неделю' }, 
                    { index:2, name: 'месяц'},
                    { index:3, name: 'год'} 
                ],
                [ 
                    { index:0, name: 'дня' }, 
                    { index:1, name: 'недели' }, 
                    { index:2, name: 'месяца'},
                    { index:3, name: 'года'} 
                ] ,
                [ 
                    { index:0, name: 'дней' }, 
                    { index:1, name: 'недель' }, 
                    { index:2, name: 'месяцев'},
                    { index:3, name: 'лет'} 
                ]
            ],
            endTime:{ index:0, label:"никогда" },
            endTimeMenu:[
                { index:0, label:"никогда" },
                { index:1, label:"после" },
                { index:2, label:"в день" },
            ],
            endTimeMenuSizes:[
                { xs9:true },
                { xs3:true },
                { xs4:true },
            ],
            endsAfterTimes:1,

            endsOnDateDialog:false,
            endsOnDate:null
        }
    },
    created(){
        Event.listen('assignees',(data)=>{
            this.assignees = data;
        });

        Event.listen('watchers',(data)=>{
            this.watchers = data;
        });

        this.selectedIntervals = 0;
    },
    watch:{
        intervalNumber(value){
            let reminder = (value < 20) ? value : ((value-10) % 10);
            // console.log(reminder);
            if( reminder == 1 ){
                this.selectedIntervals = 0;
            }
            else if( reminder >= 2 && reminder <= 4 ){
                this.selectedIntervals = 1;
            }
            else{
                this.selectedIntervals = 2;
            }
            // console.log( this.selectedInterval.name );
        },
        endTime(value){
            console.log(value);
        },
        estimateDays(value){
            this.estimateTime = this.toMilliseconds(value, this.estimateHours, this.estimateMinutes)
        },
        estimateHours(value){
            this.estimateTime = this.toMilliseconds(this.estimateDays, value, this.estimateMinutes)
        },
        estimateMinutes(value){
            this.estimateTime = this.toMilliseconds(this.estimateDays, this.estimateHours, value)
        }
    },
    methods: {
        pluck: function (array, key) {
            return array.map(item => item[key]);
        },
        toMilliseconds(days, hours, minutes){
            console.log( (days*86400000)+(hours*3600000)+(minutes*60000));
            
            return (days*86400000)+(hours*3600000)+(minutes*60000)
        }
    },
    computed:{
        estimatedTaskTime(){
            return {
                days:this.estimateDays,
                hours:this.estimateHours,
                minutes:this.estimateMinutes
            }
        }
    }
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
