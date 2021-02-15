<template>
    <v-container fluid class="py-0">
        <v-row>
            <v-col class="pa-1">
                <v-card>
                    <v-card-title>
                        Уволенные сотрудники
                    </v-card-title>
                </v-card>
            </v-col>
        </v-row>
        <v-row justify="start">
            <v-col
                v-for="(user, i) in users"
                :key="i"
                cols="12"
                sm="6"
                md="4"
                lg="4"
                xl="3"
                class="pa-1 d-flex align-content-stretch"
            >
                <user-card-horizontal :user="user" :link="true" />
            </v-col>
        </v-row>
    </v-container>
</template>
<script>
export default {
    props: {
        users: {
            required: true
        }
    }
};
</script>
