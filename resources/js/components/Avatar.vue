<template>
    <a :href="'/users/'+user.id">
        <v-avatar size="40">
            <v-tooltip top>
                <template v-slot:activator="{ on:tooltip }">
                    <img v-on="{ ...tooltip }" :src="photo(user.img) " alt="avatar">
                </template>
                <span>{{ user.name }} {{ user.surname }}</span>
            </v-tooltip>
        </v-avatar>
    </a>
</template>

<script>
export default {
    props: {
        user: {
            required: true,
            type: Object
        }
    }
};
</script>

<style>
</style>
