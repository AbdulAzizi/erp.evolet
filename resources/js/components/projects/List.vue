<template>
    <v-expansion-panels multiple>
        <v-expansion-panel v-for="(item,key) in projects" :key="key">
            <v-expansion-panel-header class="transparent" ripple>{{key}}</v-expansion-panel-header>
            <v-expansion-panel-content>
                <template v-for="(subItem,i) in item">
                    <v-btn
                        :key="i"
                        class="mr-3 primary"
                        :href="`/products?pc_id=${subItem.pc_id}&country_id=${subItem.country_id}&project_id=${subItem.id}`"
                    >
                        <span v-if="key == subItem.pc.name">{{subItem.country.name}}</span>
                        <span v-else>{{subItem.pc.name}}</span>
                    </v-btn>
                </template>
            </v-expansion-panel-content>
        </v-expansion-panel>
    </v-expansion-panels>
</template>

<script>
export default {
    props: ["projects"]
};

</script>

<style>
</style>
