<template>
    <v-card
        :href="link ? appPath('users/' + user.id) : null"
        :hover="link"
        class="flex-grow-1"
    >
        <v-menu offset-y left v-if="editable">
            <template v-slot:activator="{ on }">
                <v-btn
                    @click.prevent
                    v-on="on"
                    icon
                    style="position:absolute;right:0px;top:0px;"
                >
                    <v-icon color="grey lighten-1">mdi-dots-vertical</v-icon>
                </v-btn>
            </template>
            <v-list dense class="py-0">
                <v-list-item @click="confirmDeleteDialogModel = true">
                    <v-list-item-title>Удалить</v-list-item-title>
                </v-list-item>
                <v-list-item @click="confirmFireDialogModel = true">
                    <v-list-item-title>Уволить</v-list-item-title>
                </v-list-item>
            </v-list>
        </v-menu>
        <v-row class="pa-0 ma-0" style="height:100%;">
            <v-col cols="3" class="pa-0">
                <v-img
                    class="mr-2"
                    style="border-radius:4px 0 0 4px; height:100%;"
                    :src="photo(user.img)"
                ></v-img>
            </v-col>
            <v-col cols="9" class="pa-0">
                <div class="primary--text">
                    {{ user.name }} {{ user.surname }}
                </div>
                <div style="font-size:12px">{{ user.position_level.name }}</div>
                <div v-if="user.division" style="font-size:12px">
                    {{ user.division.name }}
                </div>
                <div>
                    <span
                        style="font-size: 12px;"
                        class="grey--text"
                        v-for="(position, key) in user.positions"
                        :key="'position-' + key"
                    >
                        {{ position.label }}
                        <span v-if="key != user.positions.length - 1"
                            >&#183;</span
                        >
                    </span>
                </div>
            </v-col>
        </v-row>
        <v-dialog v-model="confirmDeleteDialogModel" max-width="800">
            <v-card>
                <v-card-title
                    >Вы действительно хотите удалить {{ user.name }}
                    {{ user.surname }} ?</v-card-title
                >
                <v-card-actions class="px-4">
                    <v-btn text @click="confirmDeleteDialogModel = false"
                        >Отмена</v-btn
                    >
                    <v-spacer />
                    <v-btn text color="red" @click="$refs.deleteUser.submit()"
                        >Удалить</v-btn
                    >
                </v-card-actions>
            </v-card>
            <form
                ref="deleteUser"
                :action="appPath(`api/users/${user.id}`)"
                method="POST"
                style="display: none;"
            >
                <input type="hidden" name="_token" :value="csrf" />
                <input type="hidden" name="_method" value="delete" />
            </form>
        </v-dialog>

        <v-dialog v-model="confirmFireDialogModel" max-width="800">
            <v-card>
                <v-card-title
                    >Вы действительно хотите уволить {{ user.name }}
                    {{ user.surname }} ?</v-card-title
                >
                <v-card-actions class="px-4">
                    <v-btn text @click="confirmFireDialogModel = false"
                        >Отмена</v-btn
                    >
                    <v-spacer />
                    <v-btn text color="red" @click="$refs.fireUser.submit()"
                        >Уволить</v-btn
                    >
                </v-card-actions>
            </v-card>
            <form
                ref="fireUser"
                :action="appPath(`users/${user.id}/fire`)"
                method="POST"
                v-show="false"
            >
                <input type="hidden" name="_token" :value="csrf" />
            </form>
        </v-dialog>
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
        return {
            confirmFireDialogModel: false,
            confirmDeleteDialogModel: false
        };
    }
};
</script>
