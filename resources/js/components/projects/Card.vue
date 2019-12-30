<template>
    <v-row>
        <v-col v-for="(countries, pcName) in projects" :key="pcName">
            <v-card>
                <template v-for="(project, i) in countries">
                <v-list :key="'list' + i" rounded>
                    <v-card-title v-if="countries.length - 1 >= i + (countries.length - 1)"><a :href="`/products?pc_id=${project.pc_id}`">{{ pcName }}</a></v-card-title>
                    <v-list-item-group color="primary">
                        <v-list-item 
                
                        :key="i" 
                        :href="`/products?pc_id=${project.pc_id}&country_id=${project.country_id}&project_id=${project.id}`"
                        >
                                
                            <v-list-item-content>
                                <v-list-item-title>{{ project.country.name }}</v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                    </v-list-item-group>
                </v-list>
                </template>
            </v-card>
        </v-col>
    </v-row>
</template>

<script>
export default{
    props:['projects']
}
</script>

<style>
</style>
