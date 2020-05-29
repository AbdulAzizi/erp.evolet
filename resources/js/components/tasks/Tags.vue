<template>
  <div>
    <v-subheader v-if="edit && !task.tags.length">
      <v-btn icon @click="dialog = true">
        <v-icon>mdi-plus</v-icon>
      </v-btn>
      <h3>Теги</h3>
    </v-subheader>
    <v-subheader v-if="task.tags.length">
      <v-btn icon v-if="edit" @click="dialog = true">
        <v-icon>mdi-plus</v-icon>
      </v-btn>
      <h3>Теги</h3>
    </v-subheader>
    <div class="px-3" v-if="!edit">
      <v-chip
        class="mb-2 mr-2"
        color="grey lighten-4"
        text-color="grey darken-1"
        v-for="(tag, index) in task.tags"
        :key="'tag-'+index"
        small
      >
        <v-avatar style="margin-left:-8px" class="mr-0" left>
          <v-icon class="body-1">mdi-tag</v-icon>
        </v-avatar>
        {{ tag.name }}
      </v-chip>
    </div>
    <div class="px-3" v-else>
      <v-chip
        class="mb-2 mr-2"
        color="primary"
        v-for="(tag, index) in task.tags"
        :key="'tag-'+index"
      >
        <v-avatar left>
          <v-btn icon dark @click="deleteDialog = true, tagId = tag.id">
            <v-icon class="body-1">mdi-close</v-icon>
          </v-btn>
        </v-avatar>
        {{ tag.name }}
      </v-chip>
    </div>
    <v-dialog v-model="dialog" width="400">
      <v-card>
        <v-toolbar dense flat dark color="primary">
          <v-toolbar-title>Добавить теги</v-toolbar-title>
        </v-toolbar>
        <v-card-text>
          <v-form ref="tagForm" class="mt-5">
            <form-field
              :field="{
                type: 'combobox',
                name: 'tags',
                label: 'Выберите тег (Enter для создания нового)',
                items: tags,
                icon: 'mdi-tag',
                multiple: true,
                returnObject: true,
                hideDetails: true,
                rules:['required']
                }"
              v-model="selectedTags"
              v-if="auth.position_level.name == 'Руководитель'"
            />
            <form-field
              :field="{
                type: 'autocomplete',
                name: 'tags',
                label: 'Выберите тег',
                items: tags,
                icon: 'mdi-tag',
                multiple: true,
                returnObject: true,
                rules: ['required']
                }"
              v-else
              v-model="selectedTags"
            />
          </v-form>
        </v-card-text>
        <v-card-actions>
          <v-spacer />
          <v-btn text color="primary" @click="dialog = false">Отмена</v-btn>
          <v-btn color="primary" @click="submit()" :disabled="!selectedTags.length > 0">Добавить</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <delete-record
      :visible="deleteDialog"
      @close="deleteDialog = false"
      :event="event"
      :route="`/api/tasks/${task.id}/tags/${tagId}`"
    ></delete-record>
  </div>
</template>
<script>
export default {
  props: {
    task: {
      required: false
    },
    edit: {
      required: false,
      default: false
    }
  },
  data() {
    return {
      dialog: false,
      deleteDialog: false,
      selectedTags: [],
      tagId: null,
      event: "tagDeleted"
    };
  },
   computed: {
     tags() {
      return this.loadDivisionTags();
    }
  },
  methods: {
    submit() {
      const tagForm = this.$refs.tagForm;
      if (tagForm.validate()) {
        axios
          .put(`/api/tasks/${this.task.id}/tags`, {
            tags: this.selectedTags
          })
          .then(res => {
            Event.fire('taskTagsUpdated', res.data);
            Event.fire('notify', ['Теги успешно обновлены']);
            this.dialog = false;
          })
          .catch(err => console.error(err));
      }
    }
  },
  created() {
    
  },
  watch: {
    dialog(val) {
      const tagForm = this.$refs.tagForm;
      if (!val) {
        tagForm.reset();
      }
    }
  }
};
</script>