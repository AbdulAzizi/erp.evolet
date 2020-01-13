<template>
  <v-menu
    offset-y
    left
    :max-width="maxWidth"
    :max-height="maxHeight"
    :close-on-content-click="false"
  >
    <template v-slot:activator="{ on:menu }">
      <v-tooltip bottom>
        <template v-slot:activator="{ on:tooltip }">
          <v-btn icon v-on="{ ...tooltip, ...menu }">
            <v-badge left overlap v-model="localItems.length">
              <template v-slot:badge v-if="unreadItems() > 0">
                <span>{{ unreadItems()}}</span>
              </template>
              <v-icon color="grey darken-1">{{ icon }}</v-icon>
            </v-badge>
          </v-btn>
        </template>
        <span>{{tooltip}}</span>
      </v-tooltip>
    </template>
    <!-- <v-list three-lines dense class="dropdown-btn-list">
                <v-list-item v-if="items.length == 0">
                    <v-list-item-content>
                        <v-list-item-title>{{ tooltip }} отсутствуют</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>

                <template v-for="(item, key) in items">
                    <v-list-item :key="'item-'+key" href="#">
                        <v-list-item-avatar class="mt-2">
                            <img :src="item.data.avatar" />
                        </v-list-item-avatar>

                        <v-list-item-content>
                            <v-list-item-title v-html="item.data.title"></v-list-item-title>
                            <v-list-item-subtitle>{{ moment(item.created_at).fromNow() }}</v-list-item-subtitle>
                        </v-list-item-content>
                    </v-list-item>
                    <v-divider :key="'item-divider-'+key" v-if="key !== items.length-1"></v-divider>
                </template>
    </v-list>-->

    <v-card>
      <div v-if="localItems.length == 0" class="text-center">
        <v-layout py-2 px-3>
          <v-flex xs12>
            <h6 class="caption">{{ tooltip }} отсутствуют</h6>
          </v-flex>
        </v-layout>
      </div>
      <div v-for="(item, key) in localItems" :key="'item-'+key">
        <v-layout py-2 px-3 :class="item.read_at == null ? 'grey lighten-3' : 'white'">
          <v-flex xs1>
            <v-avatar size="40">
              <img :src="photo(item.data.avatar)" />
            </v-avatar>
          </v-flex>
          <v-flex xs11>
            <div class="ml-4 caption">
              <span v-html="item.data.title"></span>
              <span v-html="item.data.link" @click="markAsRead(item, false)"></span>
            </div>
            <h6 class="ml-4 caption grey--text">{{ moment(item.created_at).fromNow() }}</h6>
          </v-flex>
          <v-flex>
              <v-tooltip bottom>
                <template v-slot:activator="{ on }">
                  <v-btn icon x-small class="float-right" @click="markAsRead(item, true)" v-on="on">
                      <v-icon x-small v-if="item.read_at">mdi-checkbox-blank-circle-outline</v-icon>
                      <v-icon x-small v-else>mdi-checkbox-blank-circle</v-icon>
                  </v-btn>
                </template>
                <span v-if="item.read_at">Отметить как непрочитанное</span>
                <span v-else>Отметить как прочитанное</span>
              </v-tooltip>
          </v-flex>
        </v-layout>
        <v-divider></v-divider>
      </div>
      <v-toolbar dense style="position: sticky; bottom:0;">
        <v-btn
          text
          small
          color="primary"
          @click="markAllNotificationsAsRead()"
        >отметить все как прочитанное</v-btn>
        <v-spacer></v-spacer>
        <v-btn text color="primary" v-if="muted == 0" @click="muteSound()">
          <v-icon>mdi-volume-high</v-icon>
        </v-btn>
        <v-btn text color="primary" v-if="muted == 1" @click="muteSound()">
          <v-icon>mdi-volume-off</v-icon>
        </v-btn>
      </v-toolbar>
    </v-card>
  </v-menu>
</template>

<script>
export default {
  props: {
    items: {
      required: true
    },
    tooltip: {
      required: true
    },
    icon: {
      required: true
    },
    maxWidth: {
      required: false
    },
    maxHeight: {
      required: false,
      default: 400
    },
    user: {
      required: true
    }
  },
  data() {
    return {
      localItems: this.items,
      check: false,
      sound: new Audio("/audio/q.mp3"),
      muted: 0
    };
  },
  created() {
    Echo.private("App.User." + this.auth.id).notification(notification => {
      this.localItems.unshift(notification.notification);

      Event.fire("notify", ["У вас новое уведомление!"]);

      this.sound.muted = false;
      let promise = this.muted == 1 ? this.sound.pause() : this.sound.play();

      if (promise !== undefined) {
        promise.then(_ => {}).catch(error => error);
      }
    });
  },
  mounted() {
    if (localStorage.muted) {
      this.muted = localStorage.muted;
    }
  },
  methods: {
    markAsRead(item, toggle) {
      axios
        .post("/api/notifications", {
          user_id: this.user.id,
          notification: item,
          toggle: toggle
        })
        .then(res => {
          this.localItems.forEach(element => {
            if (element.id == item.id) {
              if(toggle){
                element.read_at !== null ? element.read_at = null : element.read_at = res.data.read_at;
              } else {
                element.read_at = res.data.read_at;
              }
            }
          });
        })
        .catch(err => err.message);
    },
    muteSound() {
      let notificationText =
        this.muted == 1
          ? ["Звук уведомлений включен"]
          : ["Звук уведомлений отключен"];
      this.muted = this.muted == 1 ? 0 : 1;
      localStorage.muted = this.muted;
      Event.fire("notify", notificationText);
    },
    markAllNotificationsAsRead() {
      axios
        .post(`/api/mark/all/notifications/${this.user.id}`)
        .then(res => {
          this.localItems.forEach(item => {
            item.read_at = this.moment()
              .local()
              .format();
          });
        })
        .catch(err => err.messages);
    },
    unreadItems() {
      return this.localItems.filter(elem => elem.read_at == null).length;
    }
  }
};
</script>

<style>
.dropdown-btn-list .v-list__tile__title {
  white-space: unset;
}
.dropdown-btn-list .v-list__tile,
.dropdown-btn-list .v-list__tile__title {
  height: 100% !important;
}
.dropdown-btn-list .v-list__tile__avatar {
  align-self: baseline;
}
.dropdown-btn-list .v-list__tile {
  padding-top: 10px;
  padding-bottom: 10px;
}
</style>
