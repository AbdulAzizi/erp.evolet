<template>
    <div>
        <v-hover v-slot:default="{hover}">
            <v-card @click="dialog=true" :elevation="hover ? 10 : 3">
                <v-toolbar color="white" flat>
                    <v-toolbar-title>File name</v-toolbar-title>

                    <v-spacer></v-spacer>

                    <v-btn icon v-if="hover">
                        <v-icon>mdi-pencil</v-icon>
                    </v-btn>

                    <v-btn icon v-if="hover">
                        <v-icon>mdi-delete</v-icon>
                    </v-btn>
                </v-toolbar>
            </v-card>
        </v-hover>
        <v-dialog v-model="dialog" width="800px">
            <v-card>
                <v-card-title>File dialog with all its fields</v-card-title>
            </v-card>
        </v-dialog>
    </div>
</template>
<script>
export default {
    props: {
        item: {}
    },
    data() {
        return {
            dialog: false
        };
    }
};
</script>