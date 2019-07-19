<template>
    <v-card hover :href="appPath + 'users/' + user.id">
        <v-list three-line>
            <v-list-item>
                <v-list-item-avatar>
                    <img :src="photo(user.img)">
                </v-list-item-avatar>

                <v-list-item-content>
                    <v-list-item-title>{{`${user.name} ${user.surname}`}}</v-list-item-title>
                    <v-list-item-subtitle>
                        {{user.position.name}}
                        <br>
                        <span
                            v-for="(responsibility,key) in user.responsibilities"
                            :key="'responsibility-'+key"
                        >
                            {{responsibility.name}}
                            <span v-if="key != user.responsibilities.length-1">|</span>
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