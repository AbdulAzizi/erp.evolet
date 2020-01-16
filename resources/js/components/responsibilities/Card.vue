<template>
    <div>
        <v-card>
            <v-toolbar color="primary" dark dense flat>
                <v-toolbar-title>{{responsibility.name}}</v-toolbar-title>
                <v-spacer />
                <edit-add-actions :responsibility="responsibility" />
            </v-toolbar>
            <div v-if="responsibility.descriptions && responsibility.descriptions.length">
                <template v-for="(description, subIndex) in responsibility.descriptions">
                    <p :key="'list-item-'+subIndex" class="ma-4">
                        {{ description.text }}
                        <span class="float-right mr-3 grey--text">
                            <v-icon small>mdi-timer-sand-full</v-icon>
                            {{ durObj(description.planned_time) }}
                        </span>
                        <span class="float-right mr-3 grey--text">
                            <v-icon small>mdi-seal</v-icon>
                            {{ description.level }}
                        </span>
                    </p>
                    <v-divider :key="'divider-'+subIndex"></v-divider>
                </template>
            </div>
            <v-card-text v-else class="text-center">
                <span class="mt-2">Инструкций нет</span>
            </v-card-text>
        </v-card>
    </div>
</template>
<script>
export default {
    props: {
        responsibility: {}
    }
};
</script>