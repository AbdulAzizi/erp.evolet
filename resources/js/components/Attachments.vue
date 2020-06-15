<template>
  <div>
    <v-row>
      <v-col cols="10">
          <span class="mb-0 font-weight-medium grey--text text--darken-2" v-if="localFiles.length">Прикрепленные файлы</span>
          <span class="mb-0 font-weight-medium grey--text text--darken-2" v-if="!localFiles.length && !deleteBtn">Нет прикрепленных файлов</span>
      </v-col>
      <v-col cols="2" class="pb-0">
        <form
          :action="`${taskId}/downloadAttachments`"
          v-if="!deleteBtn && !edit && localFiles.length"
        >
          <v-tooltip right>
            <template v-slot:activator="{ on }">
              <v-btn icon absolute right type="submit" v-on="on">
                <v-icon>mdi-download</v-icon>
              </v-btn>
            </template>
            <span>Скачать все файлы</span>
          </v-tooltip>
        </form>
        <v-btn icon absolute right v-if="edit" class="px-0">
          <label class="inputLabel" @click="$refs.inputFiles.value = '', localError = null">
            <input
              type="file"
              multiple
              name="attachments"
              style="display: none"
              ref="inputFiles"
              :accept="availableFileFormats"
              @change="addFile()"
            />
            <v-icon>mdi-file-upload-outline</v-icon>
          </label>
        </v-btn>
      </v-col>
      <v-col cols="12" v-if="localError || error" class="pb-0">
        <v-alert class="mb-0" type="error">{{localError || error}}</v-alert>
      </v-col>
    </v-row>
    <v-row>
      <v-col cols="4" v-for="(file, index) in localFiles" :key="index">
        <v-card elevation="4">
          <v-list-item>
            <v-img
              class="mr-2 previewImage"
              max-height="36"
              max-width="36"
              v-if="isImage(file.name)"
              :src="deleteBtn ? url(file) : photo(file.name)"
              @click="previewImage(file)"
            ></v-img>
            <v-icon
              v-else
              large
              class="mr-2"
              :color="fileIcon(file.name).color"
              v-html="fileIcon(file.name).icon"
            ></v-icon>
            <v-list-item-content>
              <v-list-item-title>{{ file.name }}</v-list-item-title>
              <v-list-item-subtitle :class="(file.size / 1000) >= 20000 ? 'red--text' : 'grey--text'"
              >{{ (file.size / 1000).toFixed(2) }} kb</v-list-item-subtitle>
            </v-list-item-content>
            <v-list-item-action>
              <v-btn
                outlined
                icon
                small
                @click="deleteFile(file.id, index, file.size)"
                v-if="deleteBtn || edit"
              >
                <v-icon small>mdi-close</v-icon>
              </v-btn>
              <a
                :href="isImage(file.name) ? photo(file.name) : filePath(file.name)"
                download
                v-else
              >
                <v-tooltip bottom>
                  <template v-slot:activator="{ on }">
                    <v-btn small outlined icon v-on="on">
                      <v-icon small>mdi-arrow-down</v-icon>
                    </v-btn>
                  </template>
                  <span>Скачать файл</span>
                </v-tooltip>
              </a>
            </v-list-item-action>
          </v-list-item>
        </v-card>
      </v-col>
      <template v-for="(item, index) in selectedFiles">
        <v-col cols="4" class="loader" :key="index" v-if="loading && (error ? error : localError)">
          <v-sheet class="pt-0" color="grey lighten-4">
            <v-skeleton-loader
              height="61"
              ref="skeleton"
              elevation="4"
              type="list-item-avatar-two-line"
              class="mx-auto"
            ></v-skeleton-loader>
          </v-sheet>
        </v-col>
      </template>
      <v-dialog v-model="previewImageDialog" width="500">
        <v-card>
          <v-img :src="deleteBtn ? image : photo(image)" max-width="500" max-height="500" contain></v-img>
        </v-card>
      </v-dialog>
    </v-row>
  </div>
</template>
<script>
export default {
  props: {
    files: {
      required: true,
      type: Array
    },
    deleteBtn: {
      required: false,
      type: Boolean
    },
    taskId: {
      required: false,
      type: Number
    },
    edit: {
      required: false,
      type: Boolean
    },
    error: {
      required: false
    }
  },
  data() {
    return {
      localFiles: this.files,
      selectedFiles: [],
      previewImageDialog: false,
      image: null,
      selectedFiles: [],
      loading: false,
      localError: null
    };
  },
  methods: {
    fileIcon(name) {
      let file = {};
      let excel = [".xls", ".xlsx"];
      let word = [".doc", "docx"];
      let pdf = [".pdf"];
      let powerPoint = [".ppt", ".pptx"];

      switch (true) {
        case pdf.some(val => name.includes(val)):
          file.icon = "mdi-file-pdf";
          file.color = "red";
          break;
        case excel.some(val => name.includes(val)):
          file.icon = "mdi-file-excel";
          file.color = "green";
          break;
        case word.some(val => name.includes(val)):
          file.icon = "mdi-file-word";
          file.color = "blue";
          break;
        case powerPoint.some(val => name.includes(val)):
          file.icon = "mdi-file-powerpoint";
          file.color = "orange";
          break;
        default:
          file.icon = "mdi-file";
          file.color = "primary";
          break;
      }
      return file;
    },
    url(file) {
      let url = URL.createObjectURL(file);
      return url;
    },
    previewImage(file) {
      if (this.isImage(file.name)) {
        this.previewImageDialog = true;
        this.image = this.deleteBtn ? URL.createObjectURL(file) : file.name;
      }
    },
    isImage(name) {
      let imageFormats = [".png", ".img", ".image", ".svg", ".jpeg", ".jpg"];
      return imageFormats.some(val => name.toLowerCase().includes(val));
    },
    deleteFile(id, index, size) {
      if (this.edit) {
        axios
          .delete(`/api/attachments/${id}`)
          .then(res => Event.fire("countFiles", this.localFiles.length));
      }
      if((size / 1000) >= 20000){
        this.localError = null;
        Event.fire("fileError", false)
      }
      this.localFiles.splice(index, 1);
    },
    addFile() {
      this.loading = true;
      let files = this.$refs.inputFiles.files;
      this.selectedFiles.push(...files);

      let formData = new FormData();

      this.selectedFiles.forEach((file, index) => {
        formData.append("attachments[" + index + "]", file);
      });

      formData.append("model", "App\\Task");
      formData.append("taskId", this.taskId);

      this.selectedFiles.forEach(file => {
        if (file.size / 1000 >= 20000) {
          this.localError = "Размер файла не должен превышать 20 мб";
          this.selectedFiles.length = 0;
        }
      });

      if (!this.localError) {
        axios
          .post(`/api/attachments`, formData, {
            headers: {
              "Content-Type": "multipart/form-data"
            }
          })
          .then(res => {
            this.localFiles.push(...res.data);
            Event.fire("countFiles", this.localFiles.length);
            this.selectedFiles.length = 0;
            this.loading = false;
          });
      }
    }
  },
  watch: {
    files(val) {
      val.some(item => {
        if((item.size / 1000) >= 20000) {
          this.localError = "Размер файла не должен превышать 20 мб";
          Event.fire("fileError", true)
        }
      })
    }
  }
};
</script>
<style>
.previewImage {
  cursor: pointer;
}
.inputLabel {
  cursor: pointer;
  width: 100%;
  display: flex;
  justify-content: center;
}
.loader .v-skeleton-loader__avatar,
.loader .v-skeleton-loader__sentences {
  margin-top: -5% !important;
}
</style>
