<template>
    <v-card>
        <v-layout>
            <v-flex class="pa-5">
                <history :history="item.history"/>
            </v-flex>
        </v-layout>
    </v-card>
</template>

<script>
export default {
    props: ["item"],
    data() {
        return {};
    }
};
</script>
