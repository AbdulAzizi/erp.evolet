<template>
  <div class="participants">
    <v-dialog v-model="editing" v-if="edit" max-width="600" eager>
      <template v-slot:activator="{ on }">
        <v-btn
          v-on="on"
          icon
          class="elevation-2 white"
          style="z-index: 99; margin-right:-5px;"
          width="40"
          height="40"
          @click="editMethod()"
        >
          <v-icon>mdi-pencil</v-icon>
        </v-btn>
      </template>
      <v-card>
        <v-form ref="form">
          <v-toolbar color="primary" dark dense flat>Изменить участников</v-toolbar>
          <v-card-text class="px-6 py-5">
            <form-field
              :field="{
                    users: allUsers,
                    type: 'users',
                    label: 'Постановщик',
                    rules: ['required'],
                    multiple: false,
                    dense: false,
                    returnObject: true,
                    value: authorModel,
                    disabled: !isTaskAuthor
                }"
              ref="author"
              v-model="authorModel"
            />
            <form-field
              :field="{
                users: allUsers,
                type: 'users',
                label: 'Наблюдатели',
                multiple: true,
                dense: false,
                value: watchersModel,
                returnObject: true,
                removeable:isTaskAuthor
            }"
              v-model="watchersModel"
            />
          </v-card-text>
          <v-card-actions>
            <v-spacer />
            <v-btn text color="primary" @click="editing = false">отмена</v-btn>
            <v-btn depressed color="primary" @click="submit()">Изменить</v-btn>
          </v-card-actions>
        </v-form>
      </v-card>
    </v-dialog>
    <v-tooltip bottom v-for="(item, index) in participants" :key="'avatar-' + index">
      <template v-slot:activator="{ on }">
        <a
          v-on="on"
          :href="appPath('users/' + item.id)"
          class="avatar-item"
          :style="
                        index == 0 && !edit
                            ? 'margin-left:0'
                            : 'margin-left: -20px'
                    "
        >
          <v-avatar
            color="white"
            :size="40"
            class="elevation-2"
            :style="{ 'z-index': participants.length - index }"
          >
            <img :src="thumb(item.img)" alt="avatar" />
          </v-avatar>
        </a>
      </template>
      <span>
        <span v-if="item.img">{{ item.fullname }}</span>
        <span v-else>Система</span>
        <span>({{ item.role }})</span>
      </span>
    </v-tooltip>
  </div>
</template>
<script>
export default {
  props: {
    task: {
      required: true
    },
    edit: {
      required: false,
      default: false
    }
  },
  data() {
    return {
      id: this.task.id,
      editing: false,
      localTask: { ...this.task },
      authorModel: { ...this.task.from },
      watchersModel: [...this.task.watchers],
      allUsers: []
    };
  },
  methods: {
    submit() {
      let valid = this.$refs.form.validate();

      if (!valid) return;

      if (this.authorModel.id != this.localTask.from.id) {
        axios
          .put(this.appPath(`api/tasks/${this.id}/author`), {
            author_id: this.authorModel.id
          })
          .then(resp => {
            this.localTask.from = resp.data;
            Event.fire("notify", ["Автор задачи успешно изменён"]);
          });
      }

      if (this.watchersModel.length != this.localTask.watchers) {
        axios
          .put(this.appPath(`api/tasks/${this.id}/watchers`), {
            watchers: this.watchersModel.map(u => u.id)
          })
          .then(resp => {
            this.localTask.watchers = resp.data;
            Event.fire("notify", ["Наблюдатели задачи успешно изменены"]);
          });
      }

      this.editing = false;
    }
  },
  computed: {
    participants() {
      let participants = this.localTask.watchers.map(watcher => {
        watcher["role"] = "Наблюдатель";
        return watcher;
      });

      this.localTask.responsible["role"] = "Исполнитель";
      this.localTask.from["role"] = "Постановщик";

      participants.push(this.localTask.responsible, this.localTask.from);

      return participants;
    },
    isTaskAuthor() {
      return this.auth.id === this.task.from_id;
    }
  },
  watch: {
    editing() {
      if (this.allUsers.length != 0) return;
      axios.get(this.appPath("api/users")).then(resp => {
        this.allUsers = resp.data;
      });
    }
  }
};
</script>
<style>
.participants .avatar-item {
  display: inline-block;
  -webkit-transition: all 0.3s; /* Safari prior 6.1 */
  transition: all 0.3s;
}

.participants .avatar-item:hover .v-avatar {
  z-index: 99999 !important;
}
.participants .avatar-item:hover {
  margin-left: -10px !important;
  margin-right: 10px;
}
.participants .avatar-item:first-child:hover {
  margin-left: 0px;
  margin-right: 0px;
}
</style>