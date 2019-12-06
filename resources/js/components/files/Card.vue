<template>
  <div>
       
    <v-card @click="dialog=true">
      <v-toolbar color="white" flat>
        <v-toolbar-title>{{item.name}}</v-toolbar-title>

        <v-spacer></v-spacer>
        <h4>{{item.fields.length}}</h4>
      </v-toolbar>
    </v-card>
    <v-dialog v-model="dialog" width="600">
      <v-card>
        <v-toolbar dark dense flat color="primary">
          <v-toolbar-title>{{item.name}}</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-btn text small fab>
            <v-icon small>mdi-pencil</v-icon>
          </v-btn>
          <v-btn text small fab>
            <v-icon small>mdi-delete</v-icon>
          </v-btn>
        </v-toolbar>
        <v-list dense class="py-0">
          <template v-for="(field, index) in item.fields">
            <v-hover v-slot:default="{hover}" :key="'hover' + index">
              <v-list-item :key="'item' + index">
                {{field.label}}
                <v-spacer></v-spacer>
                <v-list-item-action v-if="hover" class="ma-0">
                  <v-btn icon>
                    <v-icon small>mdi-delete</v-icon>
                  </v-btn>
                </v-list-item-action>
              </v-list-item>
            </v-hover>
            <v-divider :key="'divider' + index"></v-divider>
          </template>
          <v-btn small fab text color="primary">
            <v-icon small>mdi-plus</v-icon>
          </v-btn>
        </v-list>
      </v-card>
    </v-dialog>
  </div>
</template>
<script>
export default {
  props: ["item"],
  data() {
    return {
      dialog: false,
    };
  },
  methods: {
   
    
  },
};
</script>