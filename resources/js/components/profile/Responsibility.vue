<template>
  <v-row justify="center">
    <v-col md="6">
      <v-card>
        <v-toolbar color="primary" dark dense flat>
          <v-toolbar-title>Должностные обязанности</v-toolbar-title>
        </v-toolbar>

        <v-list class="py-0" subheader>
          <template v-for="(responsibility, index) in user.responsibilities">
            <v-subheader class="text-uppercase" :key="'subheader-'+index">{{ responsibility.name }}</v-subheader>
            <template v-for="(description, subIndex) in responsibility.descriptions">
              <v-list-item :key="'list-item-'+index+subIndex">
                <v-list-item-icon>
                  <v-icon color="primary">mdi-check</v-icon>
                </v-list-item-icon>

                <v-list-item-content>{{ description.text }}</v-list-item-content>
              </v-list-item>
              <v-divider :key="'divider-'+index+subIndex"></v-divider>
            </template>
            <v-list-item two-line :key="'noData-'+index" v-if="!responsibility.descriptions.length">
              <v-list-item-content>
                <v-list-item-title>{{ responsibility.name }} - не имеет должностных обязанностей</v-list-item-title>
              </v-list-item-content>
            </v-list-item>
          </template>
        </v-list>
      </v-card>
    </v-col>
  </v-row>
</template>

<script>
export default {
  props: ["user"],
  data() {
    return {};
  }
};
</script>