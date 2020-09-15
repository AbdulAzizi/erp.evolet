<template>
    <v-card color="secondary profile-view mb-3" dark>
        <v-container grid-list-xl text-xs-center class="pt-5 pb-0">
            <v-dialog v-model="changeAvatarDialog" width="600" persistent>
                <v-card class="pb-5">
                    <v-toolbar dense flat dark color="primary">
                        <v-toolbar-title>Изменить аватар</v-toolbar-title>
                    </v-toolbar>
                    <v-card-text>
                        <v-form
                            class="mt-5"
                            ref="form"
                            enctype="multipart/form-data"
                            method="POST"
                            :action="`/users/change/avatar/${user.id}`"
                        >
                            <input type="hidden" name="_token" :value="token" />
                            <v-file-input
                                v-model="file"
                                prepend-icon="mdi-camera"
                                rounded
                                filled
                                label="Выберите фото"
                                :rules="rules"
                                name="avatar"
                            ></v-file-input>
                            <v-btn
                                color="primary"
                                class="float-right"
                                type="submit"
                                :disabled="disabled"
                            >изменить</v-btn>
                            <v-btn
                                text
                                color="primary"
                                class="float-right"
                                @click="cancelAvatarChange()"
                            >отмена</v-btn>
                        </v-form>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer />
                    </v-card-actions>
                </v-card>
            </v-dialog>
            <v-layout row wrap class="py-0 ma-0">
                <v-flex xs12 md6>
                    <v-layout row wrap>
                        <v-flex xs4>
                            <v-img
                                :src="photo(user.img)"
                                alt="avatar"
                                style="border-radius: 100%; border: solid 6px #b8cf41;"
                                class="elevation-14"
                            >
                                <div
                                    class="upload-avatar"
                                    @click="changeAvatarDialog = true"
                                    v-if="localEditable"
                                >
                                    <p class="text-center mb-0">
                                        <v-icon>mdi-camera</v-icon>
                                    </p>
                                    <p class="text-center">изменить</p>
                                </div>
                            </v-img>
                        </v-flex>
                        <v-flex xs8>
                            <div class="text-sm-left">
                                <v-text-field
                                    v-model="fullName"
                                    v-if="editFullName"
                                    filled
                                    label="Имя фамилия"
                                    solo
                                    flat
                                    dense
                                    hide-details
                                    autofocus
                                    :append-icon="isFullName ? 'mdi-check' : null"
                                    append-outer-icon="mdi-close"
                                    @click:append="editUser()"
                                    @click:append-outer="cancelFullNameEdit()"
                                ></v-text-field>
                                <v-hover v-slot:default="{ hover }">
                                    <v-row v-if="!editFullName">
                                        <v-col cols="9" class="pb-0">
                                            <h2
                                                class="primary--text display-1 font-weight-regular"
                                            >{{user.name}} {{user.surname}}</h2>
                                        </v-col>
                                        <v-col cols="3" class="pt-5 pb-0" v-if="hover">
                                            <v-btn
                                                small
                                                text
                                                icon
                                                v-if="localEditable && !editFullName"
                                                @click="displayFullNameField()"
                                            >
                                                <v-icon small>mdi-pencil</v-icon>
                                            </v-btn>
                                        </v-col>
                                    </v-row>
                                </v-hover>
                                <v-text-field
                                    v-model="email"
                                    v-if="editEmail"
                                    filled
                                    solo
                                    flat
                                    dense
                                    hide-details
                                    autofocus
                                    :append-icon="email ? 'mdi-check' : null"
                                    append-outer-icon="mdi-close"
                                    @click:append="editUser()"
                                    @click:append-outer="cancelEmailEdit()"
                                ></v-text-field>
                                <v-hover v-slot:default="{ hover }">
                                    <v-row v-if="!editEmail">
                                        <v-col cols="9" class="py-0">
                                            <h3 class="font-weight-regular">{{user.email}}</h3>
                                        </v-col>
                                        <v-col cols="3" class="pa-0" v-if="hover">
                                            <v-btn
                                                small
                                                text
                                                icon
                                                v-if="localEditable && !editEmail"
                                                @click="displayEmailField()"
                                                class="pa-0"
                                            >
                                                <v-icon small>mdi-pencil</v-icon>
                                            </v-btn>
                                        </v-col>
                                    </v-row>
                                </v-hover>
                                <span
                                    v-for="(position,key) in user.positions"
                                    :key="'position-'+key"
                                    class="headline font-weight-regular text-sm-left pt-0"
                                >
                                    {{position.label}}
                                    <template
                                        v-if="key != user.positions.length-1"
                                    >|</template>
                                </span>
                                <!-- <h4
                                    class="headline font-weight-regular text-sm-left"
                                >{{user.position.label}}</h4>-->
                            </div>
                        </v-flex>
                    </v-layout>
                </v-flex>
                <v-flex xs12 md6>
                    <v-layout row wrap>
                        <v-flex xs4>
                            <v-card min-height="170" class="blue-grey darken-3" flat></v-card>
                        </v-flex>
                        <v-flex xs4>
                            <v-card min-height="170" class="blue-grey darken-3" flat></v-card>
                        </v-flex>
                        <v-flex xs4>
                            <v-card min-height="170" class="blue-grey darken-3" flat></v-card>
                        </v-flex>
                    </v-layout>
                </v-flex>
                <v-flex xs12 class="pb-0">
                    <v-bottom-navigation background-color="transparent" dark class="elevation-0">
                        <v-btn
                            v-for="(link, index) in links"
                            v-bind:key="index"
                            :class="link.path == url
                                ? 'v-btn--active'
                                : ''
                                "
                            :href="link.path"
                        >
                            <span>{{link.title}}</span>
                            <v-icon color="primary">{{link.icon}}</v-icon>
                        </v-btn>
                    </v-bottom-navigation>
                </v-flex>
            </v-layout>
        </v-container>
    </v-card>
</template>

<script>
export default {
    props: {
        user: {
            required: true
        },
        editable: {
            required: false,
            default: false
        }
    },
    data() {
        return {
            localEditable: this.editable,
            tabs: null,
            changeAvatarDialog: false,
            localUser: this.user,
            file: null,
            token: window.Laravel.csrf_token,
            disabled: true,
            file: null,
            fullName: `${this.user.name} ${this.user.surname}`,
            editFullName: false,
            editEmail: false,
            email: this.user.email,
            emailToSend: null,
            isFullName: true,
            rules: [
                v =>
                    !!v ||
                    "Объязательное поле. Формат файла должен быть .jpg, .jpeg или .png"
            ],
            allowedFiles: ["image/jpeg", "image/png"],
            priorities: [
                { color: "red", title: "Высокий", count: 24 },
                { color: "yellow", title: "Средний", count: 30 },
                { color: "blue", title: "Низкий", count: 11 }
            ],
            statuses: [
                { title: "Новый", count: 45 },
                { title: "В процессе", count: 12 },
                { title: "Завершенно", count: 30 },
                { title: "Просрочено", count: 4 },
                { title: "Делегировано", count: 11 },
                { title: "Перенесено", count: 5 }
            ],
            links: [
                {
                    title: "Задачи",
                    path: `/users/${this.user.id}`,
                    icon: "mdi-calendar-check"
                },
                {
                    title: "ДИ",
                    path: `/users/${this.user.id}/positions`,
                    icon: "mdi-ballot-outline"
                },
                {
                    title: "ТВК",
                    path: `/users/${this.user.id}/tasks`,
                    icon: "mdi-clock-fast"
                },
                {
                    title: "Резюме",
                    path: `/users/${this.user.id}/cv`,
                    icon: "mdi-account-card-details"
                },
                {
                    title: "ПЗ",
                    path: `/users/${this.user.id}/setTasks`,
                    icon: "mdi-calendar"
                },
                {
                    title: "Турникет",
                    path: `/users/${this.user.id}/entries`,
                    icon: "mdi-door-open"
                },
                {
                    title: "Оборудования",
                    path: `/users/${this.user.id}/facilities`,
                    icon: "mdi-desktop-classic"
                }
            ],
            url: window.location.pathname
        };
    },
    methods: {
        cancelAvatarChange() {
            const form = this.$refs.form;
            this.changeAvatarDialog = false;
            form.reset();
        },
        cancelFullNameEdit() {
            this.fullName = `${this.user.name} ${this.user.surname}`;
            this.editFullName = false;
        },
        cancelEmailEdit() {
            this.email = this.user.email;
            this.editEmail = false;
        },
        displayEmailField() {
            this.editEmail = true;
            this.cancelFullNameEdit();
        },
        displayFullNameField() {
            this.editFullName = true;
            this.cancelEmailEdit();
        },
        editUser() {
            axios
                .post('/api/users/edit', {
                    name: this.fullName.split(" ")[0],
                    surname: this.fullName.split(" ")[1],
                    email: this.emailToSend
                })
                .then(res => {
                    this.localUser.name = res.data.name;
                    this.localUser.surname = res.data.surname;
                    this.localUser.email = res.data.email;
                    this.editEmail = false;
                    this.editFullName = false;
                })
                .catch(err => err.message);
        }
    },
    watch: {
        file(value) {
            const type = value ? value.type : null;
            this.disabled = this.allowedFiles.includes(type) ? false : true;
        },
        email(newVal, oldVal) {
            if (newVal !== oldVal) {
                this.emailToSend = newVal;
            }
        },
        fullName(value) {
            const fullName = value.split(" ").length > 1;
            this.isFullName = fullName;
        }
    }
};
</script>
<style>
.profile-view .v-toolbar__extension {
    height: auto !important;
}
.profile-view .v-sheet--offset {
    top: -24px;
    position: relative;
}
.profile-view .v-btn--active {
    border-bottom: 2px #6897f5 solid;
}
.upload-avatar {
    opacity: 0;
    width: 100%;
    height: 100%;
    background: black;
    padding-top: 40%;
    cursor: pointer;
}
.upload-avatar:hover {
    opacity: 0.8;
    transition: 0.2s ease-in;
}
</style>
