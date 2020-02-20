<template>
  <div>
    <v-card flat>
      <v-card-text>
        <v-chip
          class="ma-1"
          color="primary"
          :close="isAllowedToChange"
          @click:close="deleteTag(tag.id)"
          v-for="(tag, index) in localDivision.tags"
          :key="index"
        >{{tag.name}}</v-chip>
        <v-chip v-if="localDivision.tags.length == 0" outlined>
            На данный момент тегов нет
        </v-chip>
        <v-btn x-small depressed fab color="primary" @click="dialog = true" v-if="isAllowedToChange">
          <v-icon small>mdi-plus</v-icon>
        </v-btn>
      </v-card-text>
    </v-card>
    <v-dialog v-model="dialog" width="600" persistent>
      <v-card>
        <v-toolbar dense flat dark color="primary">
          <v-toolbar-title>Добавить тег</v-toolbar-title>
        </v-toolbar>
        <v-card-text>
          <v-form ref="form" class="mt-5">
            <form-field
              :field="{
                    name: 'name',
                    label: 'Тег',
                    type: 'string',
                    rules: ['required']
                }"
              v-model="name"
            ></form-field>
          </v-form>
        </v-card-text>
        <v-card-actions>
          <v-spacer />
          <v-btn text color="primary" @click="cancel()">отмена</v-btn>
          <v-btn color="primary" @click="submit()">добавить</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <delete-record
      :visible="deleteDialog"
      @close="deleteDialog = false"
      :route="`/api/tags/${tagId}`"
    ></delete-record>
  </div>
</template>
<script>
export default {
  props: {
    division: {
      required: true
    }
  },
  data() {
    return {
      dialog: false,
      localDivision: this.division,
      name: null,
      deleteDialog: false,
      tagId: null
    };
  },
  computed: {
      isAllowedToChange(){
         return this.auth.position_level.name == 'Руководитель'
      }
  },
  methods: {
    submit() {
      const form = this.$refs.form;
      if (form.validate()) {
        axios
          .post(`/api/tags/create`, {
            divisionId: this.localDivision.id,
            name: this.name
          })
          .then(res => {
            this.localDivision.tags.push(res.data);
            this.cancel();
          })
          .catch(err => err.message);
      }
    },
    deleteTag(tagId) {
      this.deleteDialog = true;
      this.tagId = tagId;
    },
    cancel() {
      const form = this.$refs.form;
      this.dialog = false;
      form.reset();
    }
  }
};
</script>