<template>
  <div>
    <v-subheader v-if="task.tags.length || edit">Теги</v-subheader>
    <v-chip-group column>
      <v-chip
        v-for="(tag, index) in task.tags"
        :key="'tag-' + index"
        small
        :close="edit && (isHisHead || isTaskAuthor)"
        @click:close="(deleteDialog = true), (tagToDeleteId = tag.id)"
      >{{ tag.name }}</v-chip>
      <v-chip color="primary" small @click="dialog = true" v-if="edit">
        <v-icon left small>mdi-plus</v-icon>Добавить
      </v-chip>
    </v-chip-group>
    <v-dialog v-model="dialog" width="600">
      <v-card>
        <v-toolbar dense flat dark color="primary">
          <v-toolbar-title>Добавить теги</v-toolbar-title>
        </v-toolbar>
        <v-card-text>
          <v-form ref="tagForm" class="mt-5">
            <form-field
              :field="{
                                type:
                                    auth.position_level.name == 'Руководитель'
                                        ? 'combobox'
                                        : 'autocomplete',
                                name: 'tags',
                                label: 'Выберите тег',
                                items: selectableTags,
                                icon: 'mdi-tag',
                                multiple: true,
                                hint:
                                    auth.position_level.name == 'Руководитель'
                                        ? 'Enter для создания нового тега'
                                        : '',
                                returnObject: true,
                                rules: ['required']
                            }"
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
      event="tagDeleted"
      :route="`/api/tasks/${task.id}/tags/${tagToDeleteId}`"
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
      tagToDeleteId: null,
      selectableTags: [],
      divisionTags: []
    };
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
            Event.fire("taskTagsUpdated", res.data);
            Event.fire("notify", ["Теги успешно обновлены"]);
            this.dialog = false;
          })
          .catch(err => console.error(err));
      }
    },
    filterTagsForSelection() {
      this.selectableTags = this.divisionTags.filter(divTag => {
        let result = this.task.tags.filter(tskTag => tskTag.id == divTag.id);

        if (result.length == 0) {
          return divTag;
        }
      });
    }
  },
  created() {},
  watch: {
    dialog(val) {
      if (!val) {
        this.$refs.tagForm.reset();
        return;
      } else {
        axios.get(`/api/divisions/${this.auth.division_id}/tags`).then(res => {
          this.divisionTags = res.data;
          this.filterTagsForSelection();
        });
      }
    }
  },
  computed: {
    isHisHead() {
      return (
        this.auth.position_level.name == "Руководитель" &&
        this.task.responsible.division_id == this.auth.division_id
      );
    },
    isTaskAuthor() {
      return this.auth.id === this.task.from_id;
    }
  }
};
</script>
