<template>
  <v-row justify="end">
    <v-speed-dial bottom right fixed :open-on-hover="hover">
      <template v-slot:activator>
        <v-btn v-model="fab" color="primary" dark fab>
          <v-icon>mdi-plus</v-icon>
        </v-btn>
      </template>

      <v-tooltip left>
        <template v-slot:activator="{ on }">
          <v-btn fab dark small color="primary" v-on="on" @click="showCategoryForm">
            <v-icon>mdi-close</v-icon>
          </v-btn>
        </template>
        <span>Удалить</span>
      </v-tooltip>

      <v-tooltip left>
        <template v-slot:activator="{ on }">
          <v-btn fab dark small color="primary" v-on="on" @click="showCategoryForm">
            <v-icon>mdi-restore</v-icon>
          </v-btn>
        </template>
        <span>Изменить</span>
      </v-tooltip>

      <v-tooltip left>
        <template v-slot:activator="{ on }">
          <v-btn fab dark small color="primary" v-on="on" @click="showDescriptionForm">
            <v-icon>mdi-pencil</v-icon>
          </v-btn>
        </template>
        <span>Добави описание</span>
      </v-tooltip>

      <v-tooltip left>
        <template v-slot:activator="{ on }">
          <v-btn fab dark small color="primary" v-on="on" @click="showCategoryForm">
            <v-icon>mdi-plus</v-icon>
          </v-btn>
        </template>
        <span>Добавить категорию</span>
      </v-tooltip>
    </v-speed-dial>

    <!-- Add Category Responsibility -->
    <dynamic-form
      title="Добавить должностные обязоности"
      v-show="true"
      dialog
      method="post"
      action-url="/users/{id}/responsibility"
      activatorEventName="showCategoryForm"
      :fields="[
          {
            type: 'string',
            name: 'name',
            label: 'Категория',
            rules: ['required']
          },
          {
            type: 'text',
            name: 'text',
            label: 'Описание'
          }
        ]"
    />

    <!-- Add Description ro Responsibility -->
    <dynamic-form
      title="Добавить должностные обязоности"
      v-show="true"
      dialog
      method="post"
      action-url="/users/{id}/create-job-description"
      activatorEventName="showUpdateForm"
      :fields="[
          {
            type: 'select',
            name: 'responsibility_id',
            label: 'Полномочия',
            items:  this.division.responsibilities,
            rules: ['required']
          },
          {
            type: 'text',
            name: 'text',
            label: 'Описание',
            rules: ['required']
          }
        ]"
    />

    <!-- Update Description and Responsibility -->
    <dynamic-form
      title="Добавить должностные обязоности"
      v-show="true"
      dialog
      method="post"
      action-url="/users/{id}/create-job-description"
      activatorEventName="showDescriptionForm"
      :fields="[
          {
            type: 'select',
            name: 'responsibility_id',
            label: 'Полномочия',
            items:  this.division.responsibilities,
            rules: ['required']
          },
          {
            type: 'text',
            name: 'text',
            label: 'Описание',
            rules: ['required']
          }
        ]"
    />

    <!-- Delete Desciption or Responsibility -->
    <dynamic-form
      title="Добавить должностные обязоности"
      v-show="true"
      dialog
      method="post"
      action-url="/users/{id}/create-job-description"
      activatorEventName="showRemoveForm"
      :fields="[
          {
            type: 'select',
            name: 'responsibility_id',
            label: 'Полномочия',
            items:  this.division.responsibilities,
            rules: ['required']
          },
          {
            type: 'text',
            name: 'text',
            label: 'Описание',
            rules: ['required']
          }
        ]"
    />
  </v-row>
</template>


<script>
export default {
  props: ["division", "user", "responsibilities"],
  data() {
    return {
      direction: "top",
      fab: false,
      fling: false,
      hover: true,
      tabs: null,
      top: false,
      right: true,
      bottom: true,
      left: false,
      transition: "slide-y-reverse-transition"
    };
  },
  methods: {
    showDescriptionForm() {
      Event.fire("showDescriptionForm");
    },
    showCategoryForm() {
      Event.fire("showCategoryForm");
    },
    showUpdateForm() {
      Event.fire("showUpdateForm");
    },
    showRemoveForm() {
      Event.fire("showRemoveForm");
    }
  }
};
</script>