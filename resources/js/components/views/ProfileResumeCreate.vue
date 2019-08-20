<template>
    <div>
        <profile-banner :user="user"></profile-banner>
        <v-form ref="form" method="POST" :action="appPath+`users/${user.id}/cv`">
            <input type="hidden" name="_token" :value="csrf_token" />
            <input type="hidden" name="degrees" :value="JSON.stringify(degrees)" />
            <input type="hidden" name="jobs" :value="JSON.stringify(jobs)" />
            <input type="hidden" name="families" :value="JSON.stringify(families)" />
            <input type="hidden" name="languages" :value="JSON.stringify(languages)" />
            <input type="hidden" name="achievments" :value="JSON.stringify(achievments)" />
            <v-card>
                <v-container>
                    <v-layout wrap justify-space-between>
                        <v-flex xs2>
                            <form-field
                                :field="{
                                type: 'date',
                                name: 'birthday',
                                label: 'Дата рождения',
                                rules: ['required'],
                            }"
                            />
                        </v-flex>
                        <v-flex xs2>
                            <form-field
                                :field="{
                                type: 'select',
                                name: 'gender',
                                items: ['Мужской', 'Женский'],
                                label: 'Пол',
                                rules: ['required'],
                            }"
                            />
                        </v-flex>
                        <v-flex xs2>
                            <form-field
                                :field="{
                                type: 'string',
                                name: 'phone',
                                label: 'Телефон',
                                rules: ['required'],
                            }"
                            />
                        </v-flex>
                         <v-flex xs2>
                            <form-field
                                :field="{
                                type: 'select',
                                name: 'military_status',
                                label: 'Военная служба',
                                items: ['Обязан', 'Не обязан'],
                                rules: ['required'],
                            }"
                            />
                        </v-flex>


                        <v-flex xs5 class="mb-3">
                            <h2>Образование
                                <v-btn @click="dialog = true" color="primary" fab dark width="25" height="25" class="elevation-0">
                                    <v-icon>mdi-plus</v-icon>
                                </v-btn>
                            </h2>
                        <v-container v-for="(item, index) in degrees" v-bind:key="index" justify-space-between class="pa-0">
                            <v-flex>
                                <v-card class="mb-3" outlined>
                                    <v-card-text>
                                        <h4>Учреждение: {{item.institute}}</h4>
                                        <h4>Период обучения: {{item.start_at}} - {{item.end_at}} </h4>
                                        <h4>Степень: {{item.degree}} </h4>
                                        <h4>Специальность: {{item.specialty}} </h4>
                                    </v-card-text>
                                </v-card>
                            </v-flex>
                        </v-container>
                        </v-flex>
                        <template outlined>
                        <v-dialog
                        v-model="dialog"
                        width="800"
                        >
                        <v-card class="pa-3">
                            <v-card-title class="mb-3">
                                <h3>Добавить образование</h3>
                            </v-card-title>
                            <v-container>
                            <v-form ref="degree">
                                <v-layout wrap justify-space-between>

                                <v-flex xs3>

                                <form-field
                                :field="{
                                type: 'select',
                                items: ['Среднее', 'Бакалавр', 'Специалитет', 'Магистр'],
                                name: 'degree',
                                label: 'Степень',
                                rules: ['required']
                            }" v-model="degree.type"
                            />
                            </v-flex>
                            <v-flex xs4>
                                <form-field
                                :field="{
                                type: 'date',
                                name: 'start_at',
                                label: 'Начало обучения',
                                rules: ['required'],
                            }" v-model="degree.start_at"
                            />
                            </v-flex>
                             <v-flex xs4>
                                <form-field
                                :field="{
                                type: 'date',
                                name: 'end_at',
                                label: 'Конец обучения',
                                rules: ['required'],
                            }" v-model="degree.end_at"
                            />
                            </v-flex>
                             <v-flex xs12>
                                <form-field
                                :field="{
                                    type: 'string',
                                    name: 'institute',
                                    label: 'Название учебного заведения',
                                    rules: ['required'],
                                }"
                                v-model="degree.institute"
                                />
                            </v-flex>
                            <v-flex xs12>
                                <form-field
                                :field="{
                                    type: 'string',
                                    name: 'specialty',
                                    label: 'Специальность',
                                    rules: ['required'],
                                }"
                                v-model="degree.specialty"
                                />
                            </v-flex>
                            </v-layout>
                            <v-btn color="primary" @click="addDegree()">Сохранить</v-btn>
                            <v-btn color="red lighten-2" dark @click="dialog = false">Отмена</v-btn>
                            </v-form>
                            </v-container>
                        </v-card>
                        </v-dialog>
                    </template>
                    <v-flex xs5 class="mb-3">
                        <h2>Опыт работы
                        <v-btn @click="jobDialog = true" color="primary" fab dark width="25" height="25" class="elevation-0">
                            <v-icon>mdi-plus</v-icon>
                        </v-btn>
                        </h2>
                    <v-container v-for="(job, index) in jobs" v-bind:key="index" justify-space-between class="pa-0">
                        <v-flex>
                            <v-card outlined class="mb-3">
                                <v-card-text>
                                    <h4>Компания: {{job.name}} </h4>
                                    <h4>Позиция: {{job.position}}</h4>
                                    <h4>Местоположение: {{job.location}}</h4>
                                    <h4>Период: {{job.start_at}} - {{job.end_at}}</h4>
                                </v-card-text>
                            </v-card>
                        </v-flex>
                    </v-container>
                    </v-flex>
                    <template>
                        <v-dialog
                        v-model="jobDialog"
                        width="800
                        ">
                        <v-card class="pa-3">
                            <v-card-title class="mb-3">
                                <h3>Добавить место работы</h3>
                            </v-card-title>
                                <v-container>
                                    <v-form ref="jobs">
                                        <v-layout wrap justify-space-between>
                                            <v-flex xs12>
                                                <form-field
                                                :field="{
                                                    type: 'string',
                                                    name: 'jobName',
                                                    label: 'Название компании',
                                                    rules: ['required']
                                                }" v-model="job.name">
                                                </form-field>
                                            </v-flex>
                                            <v-flex xs12>
                                                <form-field
                                                :field="{
                                                    type: 'string',
                                                    name: 'jobPosition',
                                                    label: 'Позиция',
                                                    rules: ['required']
                                                }" v-model="job.position">
                                                </form-field>
                                            </v-flex>
                                            <v-flex xs5>
                                                <form-field
                                                :field="{
                                                    type: 'string',
                                                    name: 'jobLcation',
                                                    label: 'Местонахождение',
                                                    rules: ['required']
                                                }" v-model="job.location">
                                                </form-field>
                                            </v-flex>
                                            <v-flex xs3>
                                                <form-field
                                                :field="{
                                                    type: 'date',
                                                    name: 'start_at',
                                                    label: 'Начало работы',
                                                    rules: ['required']
                                                }" v-model="job.start_at">
                                                </form-field>
                                            </v-flex>
                                            <v-flex xs3>
                                                <form-field
                                                :field="{
                                                    type: 'date',
                                                    name: 'end_at',
                                                    label: 'Конец работы'
                                                }" v-model="job.end_at">
                                                </form-field>
                                            </v-flex>
                                        </v-layout>
                                    </v-form>
                                <v-btn color="primary" @click="addJob()">Сохранить</v-btn>
                                <v-btn color="red lighten-2" dark @click="jobDialog = false">Отмена</v-btn>
                                </v-container>
                        </v-card>
                        </v-dialog>
                    </template>
                    <v-flex xs5 class="mb-3">
                        <h2>Семейное положение
                             <v-btn @click="familyDialog = true" color="primary" fab dark width="25" height="25" class="elevation-0">
                                    <v-icon>mdi-plus</v-icon>
                            </v-btn>
                        </h2>
                    <v-container v-for="(family, index) in families" v-bind:key="index" justify-space-between class="pa-0 mb-3">
                        <v-flex>
                            <v-card outlined class="mb-3">
                                <v-card-text>
                                    <h4>Имя: {{family.name}}</h4>
                                    <h4>Степень родства: {{family.type}}</h4>
                                    <h4>День рождения: {{family.birthday}}</h4>
                                </v-card-text>
                            </v-card>
                        </v-flex>
                    </v-container>
                    </v-flex>
                    <template>
                        <v-dialog v-model="familyDialog" width="800">
                            <v-card>
                                <v-card-title>
                                    <h3>Семейное положение</h3>
                                </v-card-title>
                                <v-container>
                                        <v-form ref="family">
                                            <v-layout wrap justify-space-between>
                                            <v-flex xs5>
                                                <form-field
                                                :field="{
                                                    type: 'select',
                                                    name: 'familyType',
                                                    label: 'Родство',
                                                    items: ['Мать', 'Отец', 'Муж', 'Жена', 'Сын', 'Дочь', 'Брат', 'Сестра'],
                                                    rules: ['required']
                                                }" v-model="family.type">
                                                </form-field>
                                            </v-flex>
                                            <v-flex xs5>
                                                <form-field
                                                :field="{
                                                    type: 'date',
                                                    name: 'familyBirthday',
                                                    label: 'Дата рождения',
                                                    rules: ['required']
                                                }" v-model="family.birthday">
                                                </form-field>
                                            </v-flex>
                                            <v-flex xs12>
                                                <form-field
                                                :field="{
                                                    type: 'string',
                                                    name: 'familyName',
                                                    label: 'Имя',
                                                    rules: ['required']
                                                }" v-model="family.name">
                                                </form-field>
                                            </v-flex>
                                    </v-layout>
                                </v-form>
                                    <v-btn color="primary" @click="addFamily">Добавить</v-btn>
                                    <v-btn color="red lighten-2" dark @click="familyDialog = false">Отмена</v-btn>
                                </v-container>
                            </v-card>
                        </v-dialog>
                    </template>
                    <v-flex xs5 class="mb-3">
                        <h2>Знание Языков
                             <v-btn @click="languageDialog = true" color="primary" fab dark width="25" height="25" class="elevation-0">
                                    <v-icon>mdi-plus</v-icon>
                            </v-btn>
                        </h2>
                    <v-container v-for="(language, index) in languages" v-bind:key="index" class="pa-0 mb-3">
                        <v-flex>
                            <v-card outlined class="mb-3">
                                <v-card-text>
                                    <h4>Язык: {{language.name}}</h4>
                                    <h4>Уровень: {{language.level}}</h4>
                                </v-card-text>
                            </v-card>
                        </v-flex>
                    </v-container>
                    </v-flex>
                    <template>
                        <v-dialog
                        v-model="languageDialog"
                        width="800">
                        <v-card>
                            <v-card-title>
                                <h3>Добавить язык</h3>
                            </v-card-title>
                            <v-container>
                                <v-form ref="language">
                                    <v-layout wrap justify-space-between="">
                                        <v-flex xs5>
                                        <form-field
                                        :field="{
                                            type: 'select',
                                            label: 'Выберите язык',
                                            name: 'name',
                                            rules: ['required'],
                                            items: ['Английский', 'Немецкий', 'Русский', 'Французский']
                                        }" v-model="language.name">
                                        </form-field>
                                        </v-flex>
                                        <v-flex xs5>
                                        <form-field
                                        :field="{
                                            type: 'select',
                                            label: 'Выберите уровень',
                                            name: 'level',
                                            rules: ['required'],
                                            items: [
                                                    'Beginner', 'Pre-Intermidiate',
                                                    'Intermidiate',
                                                    'Upper-Intermidiate',
                                                    'Advanced'
                                                    ]
                                        }" v-model="language.level">
                                        </form-field>
                                        </v-flex>
                                    </v-layout>
                                </v-form>
                            <v-btn color="primary" @click="addLanguage">Добавить</v-btn>
                            <v-btn color="red lighten-2" dark @click="languageDialog = false">Отмена</v-btn>
                            </v-container>
                        </v-card>
                        </v-dialog>
                    </template>
                    <v-flex xs5 class="mb-3">
                        <h2>Достижения
                             <v-btn @click="achievmentDialog = true" color="primary" fab dark width="25" height="25" class="elevation-0">
                                    <v-icon>mdi-plus</v-icon>
                            </v-btn>
                        </h2>
                    <v-container v-for="(achievment, index) in achievments" v-bind:key="index" class="pa-0 mb-3">
                        <v-flex>
                            <v-card outlined class="mb-3">
                                <v-card-text>
                                    <h4>Тип: {{achievment.type}}</h4>
                                    <h4>Достижения: {{achievment.name}}</h4>
                                </v-card-text>
                            </v-card>
                        </v-flex>
                    </v-container>
                    </v-flex>
                    <template>
                        <v-dialog
                        v-model="achievmentDialog"
                        width="800">
                        <v-card>
                            <v-card-title>
                                <h3>Добавить достижение</h3>
                            </v-card-title>
                            <v-container>
                                <v-form ref="achievment">
                                    <v-layout wrap justify-space-between="">
                                        <v-flex xs5>
                                        <form-field
                                        :field="{
                                            type: 'select',
                                            label: 'Выберите тип',
                                            name: 'type',
                                            rules: ['required'],
                                            items: ['Спорт', 'Наука']
                                        }" v-model="achievment.type">
                                        </form-field>
                                        </v-flex>
                                        <v-flex xs12>
                                        <form-field
                                        :field="{
                                            type: 'text',
                                            label: 'Достижениеы',
                                            name: 'name',
                                            rules: ['required']
                                        }" v-model="achievment.name">
                                        </form-field>
                                        </v-flex>
                                    </v-layout>
                                </v-form>
                            <v-btn color="primary" @click="addAchievment">Добавить</v-btn>
                            <v-btn color="red lighten-2" dark @click="achievmentDialog = false">Отмена</v-btn>
                            </v-container>
                        </v-card>
                        </v-dialog>
                    </template>
                    </v-layout>
                    <v-btn type="submit" color="primary" class="mt-5">Submit</v-btn>
                </v-container>
            </v-card>
        </v-form>
    </div>
</template>

<script>

export default {
    props: ['user'],

    data(){
        return{

            degrees: [],

            jobs: [],

            families: [],

            languages: [],

            achievments: [],

            degree: {

                type: null,
                end_at: null,
                start_at: null,
                institute: null,
                specialty: null,

            },

            job: {

                name: null,
                position: null,
                location: null,
                start_at: null,
                end_at: null

            },

            family: {

                name: null,
                type: null,
                birthday: null

            },

            language: {

                name: null,
                level: null

            },

            achievment: {

                type: null,
                name: null

            },

            dialog: false,
            jobDialog: false,
            familyDialog: false,
            languageDialog: false,
            achievmentDialog: false,
            csrf_token: window.Laravel.csrf_token

        }
    },
    methods: {
        addDegree(){


            const valid = this.$refs.degree.validate();

            if(valid){

                this.degrees.push({

                    degree: this.degree.type,
                    end_at: this.degree.end_at,
                    start_at: this.degree.start_at,
                    institute: this.degree.institute,
                    specialty: this.degree.specialty

                });

                this.$refs.degree.reset();

                this.dialog = false;

            }
        },
        addJob(){


            const valid = this.$refs.jobs.validate();

            if(valid){

                this.jobs.push({

                    name: this.job.name,
                    position: this.job.position,
                    location: this.job.location,
                    start_at: this.job.start_at,
                    end_at: this.job.end_at

                });

                this.$refs.jobs.reset();

                this.jobDialog = false;
            }

        },
        addFamily(){

            const valid = this.$refs.family.validate();

            if(valid){

                this.families.push({

                    name: this.family.name,
                    type: this.family.type,
                    birthday: this.family.birthday

                });

                this.$refs.family.reset();

                this.familyDialog = false;

            }

        },
        addLanguage(){

            const valid = this.$refs.language.validate();

            if(valid){

                this.languages.push({

                    name: this.language.name,
                    level: this.language.level

                });

                this.$refs.language.reset();

                this.languageDialog = false;

            }

        },
        addAchievment(){

            const valid = this.$refs.achievment.validate();

            if(valid){

                this.achievments.push({

                    name: this.achievment.name,
                    type: this.achievment.type

                });

                this.$refs.achievment.reset();

                this.achievmentDialog = false;

            }
        }
    }
}
</script>
