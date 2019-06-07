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
            <form action="/tasks" method="post" ref="form">

                <input type="hidden" name="_token" :value="csrf_token">

                <v-toolbar flat color="primary" dark>
                    <v-toolbar-title class="font-weight-regular">Новая задача</v-toolbar-title>
                </v-toolbar>

            
                <v-container>
                    <v-layout row wrap>
                        <v-flex xs12 v-if="!Array.isArray(errors)">
                            <h1 class="subheading red--text text--darken-1 ml-1">Форма заполнена не верно</h1>
                        </v-flex>
                        <v-flex xs12>
                            <v-text-field
                                prepend-icon="content_paste"
                                label="Название"
                                name="title"
                                ref="title"
                                v-model="title"
                                :rules="[rules.required]"
                                required
                            ></v-text-field>
                        </v-flex>
                        <v-flex xs12>
                            <v-textarea
                                prepend-icon="description"
                                rows="3"
                                label="Описание"
                                name="description"
                            ></v-textarea>
                        </v-flex>
                        <v-flex xs12>
                            <input type="hidden" name="assignees" :value="JSON.stringify(pluck(assignees, 'id'))">

                            <v-autocomplete
                            v-model="assignees"
                            :items="employees"
                            :search-input.sync="searchText"
                            item-text="user.name"
                            return-object
                            no-data-text="Данные отсутствуют"
                            hide-selected
                            chips
                            multiple
                            color="primary"
                            label="Исполнители"
                            prepend-icon="person"
                            ref="assignees"
                            :rules="[rules.notEmptyArray]"
                            required
                            hint="У каждого исполнителя будет своя отдельная задача"
                            persistent-hint
                            >
                                <template
                                slot="selection"
                                slot-scope="data"
                                >
                                    <v-chip
                                    :selected="data.selected"
                                    color="primary"
                                    textColor="white"
                                    close
                                    @input="remove(data.item.user)"
                                    >
                                        <v-avatar>
                                            <img :src="photo(data.item.user.img)">
                                        </v-avatar>
                                        {{ data.item.user.name }}
                                    </v-chip>
                                </template>

                                <template
                                slot="item"
                                slot-scope="data"
                                >
                                    <template>
                                        <v-list-tile-avatar>
                                            <img v-if="data.item.user.img" :src="photo(data.item.user.img)">
                                        </v-list-tile-avatar>
                                        <v-list-tile-content>
                                            <v-list-tile-title>{{data.item.user.name}} {{data.item.user.surname}}</v-list-tile-title>
                                            <v-list-tile-sub-title>{{data.item.responsibility.name}} - {{data.item.division.abbreviation}}</v-list-tile-sub-title>
                                        </v-list-tile-content>
                                    </template>
                                </template>
                            </v-autocomplete>
                        </v-flex>
                        <v-flex xs-12>
                            <v-dialog
                            v-model="deadlineDialog"
                            ref="deadlineDialog"
                            width="290px"
                            lazy
                            >
                                <template v-slot:activator="{ on:dialog }">
                                    <v-tooltip top>
                                        <template v-slot:activator="{ on:tooltip }">

                                            <v-text-field
                                                v-on="{ ...dialog }"
                                                prepend-icon="event"
                                                label="Дедлайн"
                                                name="deadline"
                                                ref="deadline"
                                                v-model="deadline"
                                                :rules="[rules.required]"
                                                required
                                            ></v-text-field>

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
                        </v-flex>
                        <v-flex xs-12>
                                        
                                        <input type="hidden" name="estimatedTaskTime" :value="estimateTime">

                                        <v-container grid-list-xl class="pa-0">
                                            <v-layout row wrap>
                                                <v-flex xs12 class="mb-0 pb-0">
                                                    <h1 class="subheading grey--text text--darken-1 ml-1">Время на задачу</h1>
                                                </v-flex>
                                                <v-flex xs12 md4 class="pt-0">
                                                    <v-text-field
                                                        prepend-icon="timelapse"
                                                        type="number"
                                                        v-model="estimateDays"
                                                        ref="estimateDays"
                                                        label="Дни"
                                                        :rules="[rules.taskTimeRule]"
                                                        min="1"
                                                    ></v-text-field>
                                                </v-flex>
                                                <v-flex xs12 md4 class="pt-0">
                                                    <v-text-field
                                                        type="number"
                                                        v-model="estimateHours"
                                                        ref="estimateHours"
                                                        :rules="[rules.taskTimeRule]"
                                                        label="Часы"
                                                        min="1"
                                                    ></v-text-field>
                                                </v-flex>
                                                <v-flex xs12 md4 class="pt-0">
                                                    <v-text-field
                                                        type="number"
                                                        v-model="estimateMinutes"
                                                        ref="estimateMinutes"
                                                        :rules="[rules.taskTimeRule]"
                                                        label="Минуты"
                                                        min="1"
                                                    ></v-text-field>
                                                </v-flex>
                                            </v-layout>
                                        </v-container>
                        </v-flex>
                    </v-layout>
                </v-container>
            

                <v-card-actions>
                    <v-flex>

                        <tasks-watchers :employees="employees"></tasks-watchers>
                        
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
                    
                    type="submit"
                    @click.prevent="submit"
                    >
                        Добавить
                    </v-btn>
                </v-card-actions>
            </form>
        </v-card>
    </v-dialog>
    
</template>

<script>
export default {
    props:['employees','errors'],
    data(){
        return {
            searchText:null,

            rules:{
                required: value => !!value || 'Обязательное поле',
                notEmptyArray: value => value.length != 0 || 'Обязательное поле',
                taskTimeRule: () => !!this.estimateTime || 'Обязательное поле'
            },
            formHasErrors:false,
            csrf_token: window.Laravel.csrf_token,

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

            title:null,

            dialog: Array.isArray(this.errors) ? false : true,

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
        estimateTime(value){
            console.log( !!this.estimateTime);
            
            this.$refs['estimateDays'].validate(true);
            this.$refs['estimateHours'].validate(true);
            this.$refs['estimateMinutes'].validate(true);
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
        
        toMilliseconds(days, hours, minutes){
            console.log( (days*86400000)+(hours*3600000)+(minutes*60000));
            
            return (days*86400000)+(hours*3600000)+(minutes*60000)
        },
        submit () {
            this.formHasErrors = false
            console.log("formHasErrors = false");

            Object.keys(this.form).forEach(f => {

                if(Array.isArray(this.form[f])){
                    if ( this.form[f].length == 0 ){
                        this.formHasErrors = true
                        console.log("formHasErrors = true");
                    }
                }
                else if ( !this.form[f] ){
                    this.formHasErrors = true
                    console.log("formHasErrors = true");
                }
                
                this.$refs[f].validate(true)
            })

            if( this.form['estimateDays'] || this.form['estimateHours'] || this.form['estimateMinutes'])
                this.formHasErrors = false
            

            if(!this.formHasErrors)
                this.$refs['form'].submit()
        },

        remove (item) {
            for( var i = 0; i < this.assignees.length; i++){ 
                if ( this.assignees[i].user === item) {
                    this.assignees.splice(i, 1); 
                }
            }
        }
    },
    computed:{
        estimatedTaskTime(){
            return {
                days:this.estimateDays,
                hours:this.estimateHours,
                minutes:this.estimateMinutes
            }
        },
        form(){
            return {
                title:this.title,
                assignees:this.assignees,
                deadline:this.deadline,
                estimateDays:this.estimateDays,
                estimateHours:this.estimateHours,
                estimateMinutes:this.estimateMinutes,
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
