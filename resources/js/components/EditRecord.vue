<template>
  <v-dialog v-model="dialog" width="600">
    <v-card>
      <v-toolbar dense flat dark color="primary">
        <v-toolbar-title>{{title ? title : 'Изменить запись'}}</v-toolbar-title>
      </v-toolbar>
      <v-card-text class="pt-5 pb-0">
        <v-form ref="form">
          <form-field
            v-for="(field, index) in fields"
            :key="index"
            :field="field"
            v-model="field.value"
          />
        </v-form>
      </v-card-text>
      <v-card-actions>
        <v-spacer />
        <v-btn text color="primary" @click.stop="dialog = false">отмена</v-btn>
        <v-btn color="primary" @click="editRecord()">Изменить</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>
<script>
export default {
  props: {
    visible: {
      required: true
    },
    route: {
      required: true
    },
    fields: {
      required: true,
      type: Array
    },
    title: {
      required: false
    }
  },
  data() {
    return {
      localDivision: null
    };
  },
  methods: {
    editRecord() {
      const form = this.$refs.form;
      if (form.validate()) {
        axios
          .post(this.route, {
            data: this.fields
          })
          .then(res => {
            this.$emit("edit", res.data);
          })
          .catch(err => err.message);
      }
    }
  },
  computed: {
    dialog: {
      get() {
        return this.visible;
      },
      set(value) {
        if (!value) {
          this.$emit("close");
        }
      }
    }
  }
};
</script>