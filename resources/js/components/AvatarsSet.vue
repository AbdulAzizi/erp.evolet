<template>
  <div>
    <v-tooltip bottom v-for="(item,index) in items" :key="'avatar-'+index">
      <template v-slot:activator="{ on }">
        <a
          v-on="on"
          :href="appPath('users/' + item.id)"
          class="avatar-item"
          :style="`margin-left: ${((size / 2) * -1)}px`"
        >
          <v-avatar
            color="white"
            :size="size"
            class="elevation-2"
            :style=" {'z-index': (items.length - index)} "
          >
            <img :src="photo(item.img)" alt="avatar" />
          </v-avatar>
        </a>
      </template>
      <span>
        <span v-if="item.img">{{fullname(item)}}</span>
        <span v-else>Система</span>
        <span v-if="itemHint">({{item[itemHint]}})</span>
      </span>
    </v-tooltip>
  </div>
</template>

<script>
export default {
  props: {
    items: {
      required: true
    },
    itemHint: {
      required: false
    },
    size: {
      required: false,
      default: 40
    }
  },
  methods: {
    fullname(item) {
      return item.name + " " + item.surname;
    }
  }
};
</script>

<style>
.avatar-item {
  display: inline-block;
  -webkit-transition: all 0.3s; /* Safari prior 6.1 */
  transition: all 0.3s;
}
.avatar-item:first-child {
  margin-left: 0px !important;
}

.avatar-item:hover .v-avatar {
  z-index: 99999 !important;
}
.avatar-item:hover {
  margin-left: -10px;
  margin-right: 10px;
}
.avatar-item:first-child:hover {
  margin-left: 0px;
  margin-right: 0px;
}
</style>
