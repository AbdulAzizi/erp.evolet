<template>
    <dynamic-form
        :fields="fields"
        title="Новый проект"
        activatorEventName="addProject"
        actionUrl="/projects"
        method="post"
        :fieldsPerRows="[2,1,1,1]"
    ></dynamic-form>
</template>

<script>
export default {
    props:['users','pcs','countries'],
    data(){
        return{
            fields: [
                {
                    type: "select",
                    name: "country",
                    label: "Государство",
                    rules: ["required"],
                    items: this.countries,
                    icon: 'mdi-earth',
                },
                {
                    type: "select",
                    name: "pc",
                    label: "Промо компания",
                    rules: ["required"],
                    items: this.pcs,
                    icon: 'mdi-shield-home-outline',
                },
                {
                    type: "users",
                    name: "kurator_pc",
                    label: "Куратор Портфел ПК стран",
                    rules: ["required"],
                    icon: 'mdi-account-tie',
                    users: this.users
                },
                {
                    type: "users",
                    name: "no",
                    label: "НО",
                    rules: ["required"],
                    icon: 'mdi-account-tie',
                    users: this.users
                },
                {
                    type: "users",
                    name: "pc_representative",
                    label: "ПК",
                    rules: ["required"],
                    icon: 'mdi-account-tie',
                    users: this.users
                }
            ]
        }
    }
};
</script>

<style>
</style>
