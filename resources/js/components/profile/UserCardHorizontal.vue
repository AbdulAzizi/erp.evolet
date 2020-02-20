<template>
    <v-card :href=" link ? appPath('users/' + user.id) : null" :hover="link">
        <v-menu offset-y left v-if="editable">
            <template v-slot:activator="{ on }">
                <v-btn @click.prevent v-on="on" icon style="position:absolute;right:0px;top:0px;">
                    <v-icon color="grey lighten-1">mdi-dots-vertical</v-icon>
                </v-btn>
            </template>
            <v-list dense class="py-0">
                <v-list-item @click="$refs.deleteUser.submit()">
                    <v-list-item-title>Удалить</v-list-item-title>
                    <form
                        ref="deleteUser"
                        :action="appPath(`api/users/${user.id}`)"
                        method="POST"
                        style="display: none;"
                    >
                        <input type="hidden" name="_token" :value="csrf" />
                        <input type="hidden" name="_method" value="delete" />
                    </form>
                </v-list-item>
            </v-list>
        </v-menu>
        <v-row class="pa-0 ma-0">
            <v-img
                max-width="85"
                class="mr-2"
                style="border-radius:4px 0 0 4px"
                :src="photo(user.img)"
            ></v-img>
            <div cols="9" class="pa-0">
                <div class="primary--text">{{ user.name }} {{ user.surname }}</div>
                <div style="font-size:12px">{{ user.position_level.name }}</div>
                <div v-if="user.division" style="font-size:12px">{{user.division.name}}</div>
                <div>
                    <span
                        style="font-size: 12px;"
                        class="grey--text"
                        v-for="(position, key) in user.positions"
                        :key="'position-'+key"
                    >
                        {{position.label}}
                        <span v-if="key != user.positions.length-1">&#183;</span>
                    </span>
                </div>
            </div>
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
        },
        editable: {
            required: false,
            default: false
        }
    },
    data() {
        return {};
    }
};
</script>
