<template>
    <v-container fluid class="profile-view">
        <v-card color="secondary" dark>
            <v-container grid-list-xl text-xs-center class="pt-5 pb-4">
                <v-layout row wrap>
                    <v-flex xs12 md6>
                        <v-layout row wrap>
                            <v-flex xs4>
                                <v-img
                                    :src="photo(user.img)"
                                    alt="avatar"
                                    style="border-radius: 100%; border: solid 6px #b8cf41;"
                                    class="elevation-14"
                                />
                            </v-flex>
                            <v-flex xs8>
                                <div class="text-sm-left">
                                    <h2
                                        class="primary--text display-1 font-weight-regular "
                                    >{{user.name}} {{user.surname}}</h2>
                                    <span
                                        v-for="(responsibility,key) in user.responsibilities"
                                        :key="'responsibility-'+key"
                                        class="headline font-weight-regular text-sm-left"
                                    >
                                        {{responsibility.name}}
                                        <template
                                            v-if="key != user.responsibilities.length-1"
                                        >|</template>
                                    </span>
                                    <!-- <h4
                                        class="headline font-weight-regular text-sm-left"
                                    >{{user.responsibility.name}}</h4>-->
                                </div>
                            </v-flex>
                        </v-layout>
                    </v-flex>
                    <v-flex xs12 md6>
                        <v-layout row wrap>
                            <v-flex xs4>
                                <v-card min-height="170" class="blue-grey darken-3" flat></v-card>
                            </v-flex>
                            <v-flex xs4>
                                <v-card min-height="170" class="blue-grey darken-3" flat></v-card>
                            </v-flex>
                            <v-flex xs4>
                                <v-card min-height="170" class="blue-grey darken-3" flat></v-card>
                            </v-flex>
                        </v-layout>
                    </v-flex>
                </v-layout>
            </v-container>

            <v-tabs v-model="tabs" centered color="transparent" icons-and-text>
                <v-tabs-slider color="primary"></v-tabs-slider>

                <v-tab class href="#mobile-tabs-5-1">
                    Задачи
                    <v-icon color="primary">ballot</v-icon>
                </v-tab>

                <v-tab class href="#mobile-tabs-5-2">
                    Текст
                    <v-icon color="primary">phone</v-icon>
                </v-tab>

                <v-tab class href="#mobile-tabs-5-3">
                    Текст
                    <v-icon color="primary">favorite</v-icon>
                </v-tab>

                <v-tab class href="#mobile-tabs-5-4">
                    Текст
                    <v-icon color="primary">account_box</v-icon>
                </v-tab>
                <v-tab class href="#mobile-tabs-5-5">
                    Текст
                    <v-icon color="primary">phone</v-icon>
                </v-tab>

                <v-tab class href="#mobile-tabs-5-6">
                    Текст
                    <v-icon color="primary">favorite</v-icon>
                </v-tab>

                <v-tab class href="#mobile-tabs-5-7">
                    Текст
                    <v-icon color="primary">account_box</v-icon>
                </v-tab>
            </v-tabs>
        </v-card>

        <v-tabs-items v-model="tabs">
            <v-tab-item value="mobile-tabs-5-1">
                <div>
                    <v-container grid-list-lg fluid pa-0 pt-3>
                        <v-layout row wrap>
                            <v-flex xs6 md3>
                                <stats-card
                                    color="blue"
                                    icon="rate_review"
                                    title="Новые задачи"
                                    value="45"
                                ></stats-card>
                            </v-flex>
                            <v-flex xs6 md3>
                                <stats-card
                                    color="orange"
                                    icon="update"
                                    title="В процессе"
                                    value="12"
                                ></stats-card>
                            </v-flex>
                            <v-flex xs6 md3>
                                <stats-card color="green" icon="done" title="Завершенно" value="30"></stats-card>
                            </v-flex>
                            <v-flex xs6 md3>
                                <stats-card
                                    color="red"
                                    icon="priority_high"
                                    title="Просрочено"
                                    value="4"
                                ></stats-card>
                            </v-flex>
                            <v-flex xs6 md3>
                                <stats-card
                                    color="indigo "
                                    icon="redo"
                                    title="Делегировано"
                                    value="11"
                                ></stats-card>
                            </v-flex>
                            <v-flex xs6 md3>
                                <stats-card
                                    color="pink"
                                    icon="rotate_90_degrees_ccw"
                                    title="Перенесено"
                                    value="5"
                                ></stats-card>
                            </v-flex>
                            <v-flex xs6 md3>
                                <stats-card
                                    color="purple"
                                    icon="event_note"
                                    title="Всего задач"
                                    value="97"
                                ></stats-card>
                            </v-flex>
                            <v-flex xs6 md3>
                                <stats-card
                                    color="primary"
                                    icon="watch_later"
                                    title="Время на задачи"
                                    value="7д 12ч 34м"
                                ></stats-card>
                            </v-flex>
                        </v-layout>
                        <v-layout row wrap>
                            <v-flex xs3>
                                <card color="primary" title="Задачи" text="По преоритетам">
                                    <v-list dense>
                                        <template v-for="(priority,key) in priorities">
                                            <v-list-tile href="#" :key="'list-tile-'+key">
                                                <v-list-tile-action>
                                                    <v-icon :color="priority.color">flag</v-icon>
                                                </v-list-tile-action>
                                                <v-list-tile-content>
                                                    <v-list-tile-title>{{priority.title}}</v-list-tile-title>
                                                </v-list-tile-content>
                                                <v-list-tile-action>
                                                    <v-list-tile-action-text>{{priority.count}}</v-list-tile-action-text>
                                                </v-list-tile-action>
                                            </v-list-tile>
                                            <v-divider v-if="key < priorities.length-1" :key="key"></v-divider>
                                        </template>
                                    </v-list>
                                </card>
                            </v-flex>
                        </v-layout>
                    </v-container>
                </div>
            </v-tab-item>
            <v-tab-item value="mobile-tabs-5-2" class="elevation-1">
                <v-card flat>
                    <v-card-text>Lorem ipsum dolor sit amet consectetur adipisicing elit. Cumque odit magni, perferendis aut veritatis dicta aliquam cupiditate quia quae deleniti maxime quo repudiandae consequuntur, consectetur magnam quam iste voluptatibus officiis?</v-card-text>
                </v-card>
            </v-tab-item>
            <v-tab-item value="mobile-tabs-5-3" class="elevation-1">
                <v-card flat>
                    <v-card-text>Lorem ipsum dolor sit amet consectetur adipisicing elit. Cumque odit magni, perferendis aut veritatis dicta aliquam cupiditate quia quae deleniti maxime quo repudiandae consequuntur, consectetur magnam quam iste voluptatibus officiis?</v-card-text>
                </v-card>
            </v-tab-item>
            <v-tab-item value="mobile-tabs-5-4" class="elevation-1">
                <v-card flat>
                    <v-card-text>Lorem ipsum dolor sit amet consectetur adipisicing elit. Cumque odit magni, perferendis aut veritatis dicta aliquam cupiditate quia quae deleniti maxime quo repudiandae consequuntur, consectetur magnam quam iste voluptatibus officiis?</v-card-text>
                </v-card>
            </v-tab-item>
            <v-tab-item value="mobile-tabs-5-5">
                <v-card>
                    <v-card-text>Lorem ipsum dolor sit amet consectetur adipisicing elit. Cumque odit magni, perferendis aut veritatis dicta aliquam cupiditate quia quae deleniti maxime quo repudiandae consequuntur, consectetur magnam quam iste voluptatibus officiis?</v-card-text>
                </v-card>
            </v-tab-item>
            <v-tab-item value="mobile-tabs-5-6">
                <v-card>
                    <v-card-text>Lorem ipsum dolor sit amet consectetur adipisicing elit. Cumque odit magni, perferendis aut veritatis dicta aliquam cupiditate quia quae deleniti maxime quo repudiandae consequuntur, consectetur magnam quam iste voluptatibus officiis?</v-card-text>
                </v-card>
            </v-tab-item>
            <v-tab-item value="mobile-tabs-5-7">
                <v-card>
                    <v-card-text>Lorem ipsum dolor sit amet consectetur adipisicing elit. Cumque odit magni, perferendis aut veritatis dicta aliquam cupiditate quia quae deleniti maxime quo repudiandae consequuntur, consectetur magnam quam iste voluptatibus officiis?</v-card-text>
                </v-card>
            </v-tab-item>
        </v-tabs-items>
    </v-container>
</template>

<script>
export default {
    props: {
        user: {
            required: true
        }
    },
    data() {
        return {
            tabs: null,
            priorities: [
                { color: "red", title: "Высокий", count: 24 },
                { color: "yellow", title: "Средний", count: 30 },
                { color: "blue", title: "Низкий", count: 11 }
            ],
            statuses: [
                { title: "Новый", count: 45 },
                { title: "В процессе", count: 12 },
                { title: "Завершенно", count: 30 },
                { title: "Просрочено", count: 4 },
                { title: "Делегировано", count: 11 },
                { title: "Перенесено", count: 5 }
            ]
        };
    }
};
</script>
<style>
.profile-view .v-toolbar__extension {
    height: auto !important;
}
.profile-view .v-sheet--offset {
    top: -24px;
    position: relative;
}
</style>
