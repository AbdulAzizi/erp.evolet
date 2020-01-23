<template>
  <div>
    <v-dialog width="440" v-model="warningDialog">
      <v-card>
        <v-card-title class="headline">
          Вы действительно хотите удалить полномочие?
        </v-card-title>
        <v-card-actions>
          <v-spacer />
          <v-btn text color="red lightne-2" @click="warningDialog = !warningDialog">отмена</v-btn>
          <v-btn text color="primary" @click="deletePosition()">удалить</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-menu bottom left>
      <template v-slot:activator="{ on }">
        <v-btn v-on="on" dark icon small>
          <v-icon >mdi-dots-vertical</v-icon>
        </v-btn>
      </template>
      <v-list>
        <v-list-item @click="formDialog = !formDialog" dense>Добавить ДИ</v-list-item>
        <v-list-item dense @click="editPosition()">Изменить</v-list-item>
        <v-list-item dense @click="warningDialog = !warningDialog">Удалить</v-list-item>
      </v-list>
    </v-menu>
    <v-dialog v-model="formDialog" width="600" persistent>
      <v-card>
        <v-toolbar dense flat dark color="primary">
          <v-toolbar-title>Добавить должностные инструкции</v-toolbar-title>
        </v-toolbar>
        <v-card-text>
          <v-form ref="form" class="mt-3">
            <v-row>
              <template v-for="(description, index) in positionForms">
                <v-col cols="12" :key="'desc' + index">
                  <v-textarea
                    v-model="description.text"
                    :key="index"
                    :name="'description' + index"
                    label="Должностные инструкции"
                    rounded
                    filled
                  ></v-textarea>
                </v-col>
                 <v-col cols="12" :key="'weight' + index">
                  <v-slider
                    v-model="description.level"
                    rounded
                    filled
                    step="1"
                    :max="10"
                    ticks="always"
                    thumb-label="always"
                    :thumb-size="24"
                    :name="'weight' + index"
                    label="Уровень"
                  ></v-slider>
                </v-col>
                <v-col cols="4" :key="'day' + index">
                  <v-text-field
                  v-model="description.days"
                  label="Дни"
                  :name="'day' + index"
                  rounded
                  type="number"
                  filled>
                  </v-text-field>
                </v-col>
                 <v-col cols="4" :key="'hour' + index">
                  <v-text-field
                  v-model="description.hours"
                  label="Часы"
                  :name="'hour' + index"
                  type="number"
                  rounded
                  filled>
                  </v-text-field>
                </v-col>
                 <v-col cols="4" :key="'minute' + index">
                  <v-text-field
                  v-model="description.minutes"
                  label="Минуты"
                  :name="'minute' + index"
                  type="number"
                  rounded
                  filled>
                  </v-text-field>
                </v-col>
                 <v-divider :key="'divider' + index" v-if="index > positionForms.length" class="ma-2"/>
              </template>
              <v-btn block outlined rounded color="primary" @click="addPositionForm()">Добавить еще</v-btn>
            </v-row>
          </v-form>
        </v-card-text>
        <v-card-actions>
            <v-spacer />
            <v-btn text color="primary" @click="resetForm()">
                Отмена
            </v-btn>
            <v-btn :dark="!disabled"  color="primary" @click="submit()">
                Создать
            </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>
<script>
export default {
  props: ["position"],
  data() {
    return {
      formDialog: false,
      warningDialog: false,
      disabled: true,
      positionForms: [
        {
          text: null,
          level: null,
          days: null,
          hours: null,
          minutes: null
        }
      ]
    };
  },
  methods: {
    submit() {
        axios.post(`/api/add/single/position/${this.position.id}`, {
            descriptions: this.positionForms
        }).then(res => {
          Event.fire('descriptionAdded', res.data);
          this.resetForm();
        })
    },
    addPositionForm() {
      this.positionForms.push({
        text: null,
          level: null,
          days: null,
          hours: null,
          minutes: null
      });
      this.disabled = false;
    },
    editPosition(){
      Event.fire('editPosition', this.position.id);
    },
    displayDeleteWarning(){
      this.deleteWarningDialog = true;
    },
    deletePosition(){
      axios.delete(`/api/delete/position/${this.position.id}`).then(res => {
        Event.fire('deletePosition', this.position.id);
      }).catch(err => err.messages);
    },
    resetForm(){
      const form = this.$refs.form;
      form.reset();
      this.formDialog = false;
      this.positionForms.length = 1;
    }
  }
};
</script>