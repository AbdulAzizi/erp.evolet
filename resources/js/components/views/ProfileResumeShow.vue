<template>
    <div>
        <profile-banner :user="user" />
        <v-card v-for="(resume, index) in resume" v-bind:key="index">
            <v-card-title>
                <h2>Фамилия Имя Отчество</h2>
            </v-card-title>
            <v-card-text>
                <h4>{{resume.name}}</h4>
            </v-card-text>
        </v-card>
    </div>
</template>

<script>
export default {
    props: ['user', 'resume']
}
</script>
