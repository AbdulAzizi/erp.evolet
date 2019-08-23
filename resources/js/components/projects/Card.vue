<template>
    <v-row>
        <v-col v-for="(countries, pcName) in projects" :key="pcName">
            <v-card>
                <v-card-title>{{pcName}}</v-card-title>
                <v-list rounded>
                    <v-list-item-group color="primary">
                        <v-list-item 
                        v-for="(project, i) in countries" 
                        :key="i" 
                        :href="`/products?pc_id=${project.pc_id}&country_id=${project.country_id}&project_id=${project.id}`"
                        >
                            <v-list-item-content>
                                <v-list-item-title>{{ project.country.name }}</v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                    </v-list-item-group>
                </v-list>
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
