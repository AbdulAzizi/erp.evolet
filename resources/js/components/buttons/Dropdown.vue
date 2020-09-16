<template>
  <v-menu
    offset-y
    left
    :max-width="maxWidth"
    :max-height="maxHeight"
    :close-on-content-click="false"
  >
    <template v-slot:activator="{ on: menu }">
      <v-tooltip bottom>
        <template v-slot:activator="{ on: tooltip }">
          <v-btn icon v-on="{ ...tooltip, ...menu }" @click.once="loadNotifications()">
            <v-badge left overlap :value="unreadNotifications" :content="unreadNotifications">
              <v-icon color="grey darken-1">{{ icon }}</v-icon>
            </v-badge>
          </v-btn>
        </template>
        <span>{{ tooltip }}</span>
      </v-tooltip>
    </template>

    <v-card id="notifications-menu">
      <v-infinite-scroll
        @bottom="loadNotifications()"
        style="max-height: 400px; overflow-y: scroll;"
      >
        <div v-if="localItems.length == 0" class="text-center">
          <v-layout py-2 px-3>
            <v-flex xs12>
              <h6 class="caption">{{ tooltip }} отсутствуют</h6>
            </v-flex>
          </v-layout>
        </div>
        <div v-for="(item, key) in localItems" :key="'item-' + key">
          <v-layout py-2 px-3 :class="item.read_at == null ? 'grey lighten-3' : 'white'">
            <v-flex xs1>
              <v-avatar size="40">
                <img :src="thumb(item.data.avatar)" />
              </v-avatar>
            </v-flex>
            <v-flex xs11>
              <div class="ml-4 caption">
                <span v-html="item.data.title"></span>
                <span v-html="item.data.link" @click="markAsRead(item, false)"></span>
                <span v-if="item.data.deadline">
                  {{
                  moment(item.data.deadline)
                  .local()
                  .format("lll")
                  }}
                </span>
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
          <v-btn text color="primary" v-if="muted == 0" @click="muteSound()" :loading="loading">
            <v-icon>mdi-volume-high</v-icon>
          </v-btn>
          <v-btn text color="primary" v-if="muted == 1" @click="muteSound()" :loading="loading">
            <v-icon>mdi-volume-off</v-icon>
          </v-btn>
        </v-toolbar>
      </v-infinite-scroll>
    </v-card>
  </v-menu>
</template>

<script>
import InfiniteScroll from "v-infinite-scroll";
import "v-infinite-scroll/dist/v-infinite-scroll.css";
export default {
  props: {
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
  components: {
    "v-infinite-scroll": InfiniteScroll
  },
  data() {
    return {
      localItems: [],
      check: false,
      sound: new Audio("/audio/q.mp3"),
      muted: 0,
      page: 1,
      unreadNotifications: this.user.unread_notifications_count,
      allNotificationsLoaded: false,
      loading: false
    };
  },
  created() {
    Echo.private("App.User." + this.auth.id).notification(notification => {
      this.localItems.unshift(notification.notification);

      Event.fire("notify", ["У вас новое уведомление!"]);
      this.unreadNotifications ++;

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
        .post("/api/mark-notifications", {
          user_id: this.user.id,
          notification: item,
          toggle: toggle
        })
        .then(res => {
          this.localItems.forEach(element => {
            if (element.id == item.id) {
              if (toggle) {
                this.toggledNotifications(element, res.data.read_at);
              } else {
                this.unreadNotifications--;
                element.read_at = res.data.read_at;
              }
            }
          });
        })
        .catch(err => err.message);
    },
    toggledNotifications(notification, read_at) {
      if (notification.read_at !== null) {
        notification.read_at = null;
        this.unreadNotifications++;
      } else {
        notification.read_at = read_at;
        this.unreadNotifications--;
      }
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
          this.unreadNotifications = 0;
        })
        .catch(err => err.messages);
    },
    loadNotifications() {
      this.loading = true;
      if (!this.allNotificationsLoaded) {
        axios
          .post("/api/notifications", {
            page: this.page
          })
          .then(res => {
            this.page++;
            this.localItems.push(...res.data);
            res.data.length == 0
              ? (this.allNotificationsLoaded = true)
              : this.allNotificationsLoaded;
            this.loading = false;
          });
      }
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
