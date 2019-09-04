<template>
  <v-card class="pb-1">
    <v-toolbar dark flat dense color="primary">
      <v-toolbar-title>
        <h4>{{title}}</h4>
      </v-toolbar-title>
      <v-spacer></v-spacer>
      <slot></slot>
    </v-toolbar>
    <v-list two-line class="pt-1">
      <v-list-item v-if="resume.length < 1">
        <v-list-item-content>
          <v-list-item-title>
            <h4 class="mx-3 grey--text body-2 text-center">Нет данных</h4>
          </v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <template v-for="(item, index) in resume">
        <v-dialog v-model="dialog" max-width="400" :key="index">
          <v-card class="pa-3 text-center">
            <v-card-text class="subtitle-1">Вы действительно хотите удалить данные?</v-card-text>
            <v-card-actions>
              <v-btn color="primary" text @click="dialog = false">Нет</v-btn>
              <v-spacer></v-spacer>
              <v-btn
                color="red lighten-2"
                outlined
                @click="deleteItem(item.id, deleteUrl + item.id, resume)"
              >Да</v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
        <v-hover v-slot:default="{ hover }" :key="'hover-' + index">
          <v-list-item :key="'item-' + index">
            <v-list-item-avatar>
              <v-icon>{{main_icon}}</v-icon>
            </v-list-item-avatar>
            <v-list-item-content>
              <v-list-item-title>
                {{item[firstMainLine]}}
                <span
                  class="grey--text text--darken-1"
                >{{item[firstSecondaryLine] ? ' &#183; ' + item[firstSecondaryLine] : ''}}</span>
              </v-list-item-title>
              <v-list-item-subtitle>
                <span v-for="(secondLineItem, index) in secondLineItems" :key="index">
                  {{item[secondLineItem]}}
                  <span v-if="secondLineItems.length > index + 1">&#183;</span>
                </span>
              </v-list-item-subtitle>
            </v-list-item-content>
            <v-list-item-action v-if="hover && check">
              <v-btn
              icon 
              small 
              class="grey lighten-3" 
              @click="dialog = true">
                <v-icon small dark>mdi-delete</v-icon>
              </v-btn>
            </v-list-item-action>
          </v-list-item>
        </v-hover>
        <v-divider :key="'divider-' + index" v-if="resume.length > index + 1 "></v-divider>
      </template>
    </v-list>
    <v-progress-linear
      :active="loading"
      :indeterminate="loading"
      absolute
      bottom
      color="deep-purple accent-4"
    ></v-progress-linear>
  </v-card>
</template>

<script>
export default {
  props: [
    "user",
    "returnDataEvent",
    "main_icon",
    "resume",
    "title",
    "deleteUrl",
    "type",
    "firstMainLine",
    "firstSecondaryLine",
    "secondLineItems",
    "check"
  ],

  data() {
    return {
      loading: false,
      dialog: false
    };
  },
  methods: {
    datesPeriod(end, start) {
      let a = this.moment(end);
      let b = this.moment(start);
      return a.diff(b, "years");
    },

    deleteItem(id, url, model) {
      this.dialog = false;
      this.loading = true;

      axios
        .delete(url, {})
        .then(res => {
          model.forEach((item, key) => {
            if (item.id === id) model.splice(key, 1);
          });
          this.loading = false;
        })
        .catch(err => console.log(err.message));
    }
  }
};
</script>
