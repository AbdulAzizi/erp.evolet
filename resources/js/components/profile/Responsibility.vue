<template>
  <div>
    <v-toolbar color="primary" dark dense flat>
      <v-toolbar-title class="headline">Должностные обязанности</v-toolbar-title>
    </v-toolbar>

    <v-card class="mx-auto">
      <v-list-item-group multiple>
        <template>
          <v-list-item
            v-for="(responsibility, key) in user.responsibilities"
            :key="'responsibility-'+key"
          >
            <v-list-item-content>
              <v-list-item-title class="my-2">
                <span class="font-weight-bold title">{{ responsibility.name }}</span>
              </v-list-item-title>

              <div
                class="mb-2"
                v-for="(description, index) in responsibility.descriptions"
                :key="index"
              >
                <ul>
                  <li>{{ description.text }}</li>
                </ul>
              </div>
            </v-list-item-content>
          </v-list-item>
        </template>
      </v-list-item-group>
    </v-card>
  </div>
</template>


<script>
export default {
  props: ["user"],

  data() {
    return {};
  }
};
</script>