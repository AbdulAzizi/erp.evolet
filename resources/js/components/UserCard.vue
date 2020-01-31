<template>
    <v-card hover :href="appPath('users/' + user.id)">
        <v-list three-line>
            <v-list-item>
                <v-list-item-avatar>
                    <img :src="photo(user.img)">
                </v-list-item-avatar>

                <v-list-item-content>
                    <v-list-item-title>{{`${user.name} ${user.surname}`}}</v-list-item-title>
                    <v-list-item-subtitle>
                        {{user.position_level.name}}
                        <br>
                        <span
                            v-for="(position,key) in user.positions"
                            :key="'position-'+key"
                        >
                            {{position.label}}
                            <span v-if="key != user.positions.length-1">|</span>
                        </span>
                    </v-list-item-subtitle>
                </v-list-item-content>
            </v-list-item>
        </v-list>
    </v-card>
</template>

<script>
export default {
    props: ["user"]
};
</script>