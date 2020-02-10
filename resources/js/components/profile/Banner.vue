<template>
  <v-card color="secondary profile-view mb-3" dark>
    <v-container grid-list-xl text-xs-center class="pt-5 pb-0">
      <v-dialog v-model="changeAvatarDialog" width="600" persistent>
        <v-card class="pb-5">
          <v-toolbar dense flat dark color="primary">
            <v-toolbar-title>
              Изменить аватар
            </v-toolbar-title>
          </v-toolbar>
          <v-card-text>
            <v-form class="mt-5" ref="form" enctype="multipart/form-data" method="POST" :action="`/users/change/avatar/${user.id}`">
              <input type="hidden" name="_token" :value="token">
              <v-file-input
              prepend-icon="mdi-camera"
              rounded
              filled
              label="Выберите фото"
              name="avatar">
              </v-file-input>
              <v-btn color="primary" class="float-right" type="submit">изменить</v-btn>
              <v-btn text color="primary" class="float-right" @click="cancelAvatarChange()">отмена</v-btn>
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
              <div class="upload-avatar" @click="changeAvatarDialog = true" v-if="user.id == authuser.id">
                <p class="text-center mb-0"><v-icon>mdi-camera</v-icon></p>
                <p class="text-center">изменить</p>
              </div>
              </v-img>
            </v-flex>
            <v-flex xs8>
              <div class="text-sm-left">
                <h2
                  class="primary--text display-1 font-weight-regular"
                >{{user.name}} {{user.surname}}</h2>
                <span
                  v-for="(position,key) in user.positions"
                  :key="'position-'+key"
                  class="headline font-weight-regular text-sm-left"
                >
                  {{position.label}}
                  <template v-if="key != user.positions.length-1">|</template>
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
    authuser: {
      required: false
    }
  },
  data() {
    return {
      tabs: null,
      changeAvatarDialog: false,
      file: null,
      token: window.Laravel.csrf_token,
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
        { title: "Задачи", path: `/users/${this.user.id}`, icon: "mdi-phone" },
        {
          title: "ДИ",
          path: `/users/${this.user.id}/position`,
          icon: "mdi-ballot-outline"
        },
        {
          title: "Резюме",
          path: `/users/${this.user.id}/cv`,
          icon: "mdi-account-card-details"
        }
      ],

      url: window.location.pathname
    };
  },
  methods: {
    cancelAvatarChange(){
      const form = this.$refs.form;
      this.changeAvatarDialog = false;
      form.reset();
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
.profile-view .v-btn--active{
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
  transition: .2s ease-in;
}
</style>
