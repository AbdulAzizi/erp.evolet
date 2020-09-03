<template>
  <v-row class="ma-0" justify="space-between" align="center">
    <span class="text-h6">All Entries</span>
    <v-dialog max-width="700">
      <template v-slot:activator="{ on, attrs }">
        <v-btn v-on="on" color="green" small dark depressed>
          <v-icon left>mdi-file-excel-box</v-icon>Import Excel
        </v-btn>
      </template>
      <v-card>
        <v-form method="POST" :action="appPath(`hr/entries`)" enctype="multipart/form-data">
          <input type="hidden" name="_token" :value="csrf_token" />
          <v-card-title></v-card-title>
          <v-card-text class="pb-0">
            <v-file-input filled rounded label="File input" name="file"></v-file-input>
          </v-card-text>
          <v-card-actions class="pa-6 pt-0 pb-3">
            <v-spacer />
            <v-btn text>Cancel</v-btn>
            <v-btn text color="primary" type="submit">Submit</v-btn>
          </v-card-actions>
        </v-form>
      </v-card>
    </v-dialog>
  </v-row>
</template>
<script>
export default {
  data() {
    return {
      csrf_token: window.Laravel.csrf_token
    };
  }
};
</script>