<template>
    <div>
        <v-row>
            <v-col cols="12">
                <h2 class="grey--text text--darken-3">Привет {{user.name}}</h2>
                <p class="grey--text">Добро пожаловать в HR консоль</p>
            </v-col>
            <v-col cols="3">
                <v-card class="pa-3">
                    <v-card-title>
                        <v-icon large color="primary">
                            mdi-account-circle
                        </v-icon>
                    </v-card-title>
                    <v-card-text>
                        <span class="subtitle-1 bold">Количество сотрудников</span>
                        <span class="float-right subtitle-1 font-weight-black">{{amountOfUsers}}</span>
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col cols="3">
                <v-card class="pa-3">
                    <v-card-title>
                        <v-icon large color="primary">
                            mdi-calendar
                        </v-icon>
                    </v-card-title>
                    <v-card-text>
                        <span class="subtitle-1 bold">Сотрудники в отпуске</span>
                        <span class="float-right subtitle-1 font-weight-black">12</span>
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col cols="3">
                <v-card class="pa-3" hover href="/human-resources/resumes">
                    <v-card-title>
                        <v-icon large color="primary">
                            mdi-file-multiple
                        </v-icon>
                    </v-card-title>
                    <v-card-text>
                        <span class="subtitle-1 bold">Количество резюме</span>
                        <span class="float-right subtitle-1 font-weight-black">{{amountOfResumes}}</span>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </div>
</template>
<script>
export default {
    props: ['user', 'amount-of-users', 'amount-of-resumes'],
    data(){
        return{

        }
    }
}
</script>
