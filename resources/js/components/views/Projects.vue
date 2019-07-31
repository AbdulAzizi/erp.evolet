<template>
    <v-data-table
        :headers="headers"
        :items="projects"
        class="elevation-1"
        hide-default-footer
        @click:row="goTo"
        items-per-page="100"
    >
        <!-- <template v-slot:item.no="{ item }">
            <avatar :user="item.no"/>
        </template>
        <template v-slot:item.pc_representative="{ item }">
            <avatar :user="item.pc_representative"/>
        </template>
        <template v-slot:item.manager="{ item }">
            <avatar :user="item.manager"/>
        </template>-->
    </v-data-table>
</template>

<script>
export default {
    props: ["projects"],
    data() {
        console.log(this.projects);

        return {
            headers: [
                { text: "Промо Компания", value: "pc.name" },
                { text: "Страна", value: "country.name" }
                // { text: "НО", value: "no" },
                // { text: "ПК", value: "pc_representative" },
                // { text: "Куратор", value: "manager" }
            ]
        };
    },
    methods: {
        goTo(item) {
            window.location.href = "/products?project_id=" + item.id;
        }
    }
};
</script>

<style>
</style>
