<template>
    <div>
        <v-form>
            <v-card>
                <v-card-text>
                    <v-row>
                        <v-col>
                            <h2>Основное</h2>
                        <v-card outlined class="mt-3 mb-3" width="400">
                        <v-card-text>
                            <form-field :field="{
                                name: 'birthday',
                                label: 'Дата рождения',
                                type: 'date',
                                rules: ['required'],
                                value: birthday
                            }"
                            v-model="birthday">
                            </form-field>
                            <form-field :field="{
                                name: 'phone',
                                label: 'Телефон',
                                type: 'string',
                                rules: ['required'],
                                value: phone
                            }"
                            v-model="phone">
                            </form-field>
                            <form-field :field="{
                                name: 'gender',
                                label: 'Пол',
                                type: 'select',
                                rules: ['required'],
                                value: gender,
                                items: ['Мужской', 'Женский']
                            }"
                            v-model="gender">
                            </form-field>
                            <form-field :field="{
                                name: 'military_status',
                                label: 'Военная объязанность',
                                type: 'select',
                                rules: ['required'],
                                value: 'Объязан',
                                items: ['Объязан', 'Не объязан'],
                            }"
                            v-model="military_status">
                            </form-field>
                        </v-card-text>
                        </v-card>
                        <v-card v-for="(education, index) in user.resume.educations" v-bind:key="index" outlined width="400">
                            <v-card-text>
                                <form-field :field="{
                                name: 'place',
                                label: 'Учреждение',
                                type: 'string',
                                rules: ['required'],
                                value: education.name
                                }"
                                v-model="degree.place">
                                </form-field>
                            </v-card-text>
                        </v-card>
                        </v-col>
                        <v-col>
                        </v-col>
                    </v-row>
                </v-card-text>
            </v-card>
        </v-form>
    </div>
</template>

<script>
export default {
    props: ['user', 'resume'],

    data(){
        return {
            birthday: this.user.resume.birthday,
            phone: this.user.resume.phone,
            gender: this.user.resume.male_female,
            military_status: this.user.resume.military_status,

            degree: {
                place: null
            }
        }
    }

}
</script>
