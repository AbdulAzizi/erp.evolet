<template>
    <v-data-table
        :headers="headers"
        :items="projects"
        class="elevation-1"
        item-key="id"
        hide-actions
    >
        <template v-slot:items="props">
            <tr>
                <td>{{ props.item.pc.name }}</td>
                <td>{{ props.item.country.name }}</td>
                <td>
                    <avatar :user="props.item.no"/>
                </td>
                <td>
                    <avatar :user="props.item.pc_representative"/>
                </td>
                <td>
                    <avatar :user="props.item.manager"/>
                </td>
            </tr>
        </template>
    </v-data-table>
</template>

<script>
export default {
    props: ["projects"],
    data() {
        return {
            headers: [
                { text: "Промо Компания", value: "pc_id" },
                { text: "Страна", value: "country_id" },
                { text: "НО", value: "no_id" },
                { text: "ПК", value: "pc_representative_id" },
                { text: "Куратор", value: "manager_id" }
            ]
        };
    },
    created() {
        console.log(this.projects);
    }
};
</script>

<style>
</style>
