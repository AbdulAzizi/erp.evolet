<template>
  <v-menu offset-y left :max-width="maxWidth" :max-height="maxHeight">
    <template v-slot:activator="{ on:menu }">
      <v-tooltip bottom>
        <template v-slot:activator="{ on:tooltip }">
          <v-btn icon v-on="{ ...tooltip, ...menu }" @click="notify">
            <v-badge left overlap v-model="items.length">
              <template v-slot:badge>
                <span v-if="check">{{ item.length}}</span>
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
      <div v-if="items.length == 0" class="text-center">
        <v-layout py-2 px-3>
          <v-flex xs12>
            <h6 class="caption">{{ tooltip }} отсутствуют</h6>
          </v-flex>
        </v-layout>
      </div>
      <div v-for="(item, key) in items" :key="'item-'+key">
        <v-layout py-2 px-3>
          <v-flex xs1>
            <v-avatar size="40">
              <img :src="photo(item.data.avatar)" />
            </v-avatar>
          </v-flex>
          <v-flex xs11>
            <h6 class="ml-4 caption" v-html="item.data.title"></h6>
            <h6 class="ml-4 caption grey--text">{{ moment(item.created_at).fromNow() }}</h6>
          </v-flex>
        </v-layout>
        <v-divider></v-divider>
      </div>
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
      item: this.items.filter(el => el.read_at == null),
      check: false
    };
  },
  created() {
    this.check = this.item.length > 0;
  },
  methods: {
    notify() {
      axios
        .post("/api/notifications", {
          id: this.user.id
        })
        .then(res => {
          this.check = false;
        })
        .catch(err => err.message);
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
