<template>
    <v-card hover :href="appPath + 'users/' + user.id">
        <v-list three-line>
            <v-list-tile avatar>
                <v-list-tile-avatar>
                    <img :src="photo(user.img)">
                </v-list-tile-avatar>

                <v-list-tile-content>
                    <v-list-tile-title>{{`${user.name} ${user.surname}`}}</v-list-tile-title>
                    <v-list-tile-sub-title>
                        {{user.position.name}}
                        <br>
                        <span
                            v-for="(responsibility,key) in user.responsibilities"
                            :key="'responsibility-'+key"
                        >
                            {{responsibility.name}}
                            <span v-if="key != user.responsibilities.length-1">|</span>
                        </span>
                    </v-list-tile-sub-title>
                </v-list-tile-content>
            </v-list-tile>
        </v-list>
    </v-card>
</template>

<script>
export default {
    props: ["user"]
};
</script>