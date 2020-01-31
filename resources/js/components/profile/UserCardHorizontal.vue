<template>
    <v-card :href=" link ? appPath('users/' + user.id) : null" :hover="link">
        <v-row class="pa-0 ma-0">
            <v-col cols="3" class="pa-0">
                <v-img style="border-radius:4px 0 0 4px" :src="photo(user.img)"></v-img>
            </v-col>
            <v-col cols="9" class="py-2">
                <h4 class="primary--text">{{ user.name }} {{ user.surname }}</h4>
                <span style="font-size:12px">{{ user.position_level.name }}</span>
                <br />
                <span style="font-size:12px">{{user.division.name}}</span>
                <br />
                <span
                    style="font-size: 12px;"
                    class="grey--text"
                    v-for="(position, key) in user.positions"
                    :key="'position-'+key"
                >
                    {{position.label}}
                    <span v-if="key != user.positions.length-1">&#183;</span>
                </span>
            </v-col>
        </v-row>
    </v-card>
</template>


<script>
export default {
    props: {
        user: {
            required: true
        },
        link: {
            required: false,
            default: false
        }
    },
    data() {
        return {};
    }
};
</script>
