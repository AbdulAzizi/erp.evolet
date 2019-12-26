<template>
    <v-card :href="appPath('factories/'+item.id)">
        <v-list-item three-line>
            <v-list-item-avatar horizontal tile size="80" min-width="120" color="white">
                <v-img contain :src="photo(item.logo)"></v-img>
            </v-list-item-avatar>
            <v-list-item-content>
                <v-list-item-title class="title">{{item.name}}</v-list-item-title>
                <v-list-item-subtitle>{{item.about}}</v-list-item-subtitle>
            </v-list-item-content>
        </v-list-item>

        <!-- <v-img height="150" :src="photo(item.logo)"></v-img> -->
        <v-row class="ma-0">
            <v-col cols="6" class="pa-0">
                <v-list dense>
                    <v-list-item>
                        <v-list-item-icon>
                            <v-icon>mdi-flag</v-icon>
                        </v-list-item-icon>
                        <v-list-item-content>
                            <v-list-item-title v-text="item.country.name"></v-list-item-title>
                            <v-list-item-subtitle>Country</v-list-item-subtitle>
                        </v-list-item-content>
                    </v-list-item>
                    <v-list-item>
                        <v-list-item-icon>
                            <v-icon>mdi-earth</v-icon>
                        </v-list-item-icon>
                        <v-list-item-content>
                            <v-list-item-title v-text="item.website"></v-list-item-title>
                            <v-list-item-subtitle>Website</v-list-item-subtitle>
                        </v-list-item-content>
                    </v-list-item>
                </v-list>
            </v-col>
            <v-col cols="6" class="pa-0">
                <v-list dense>
                    <v-list-item>
                        <v-list-item-icon>
                            <v-icon>mdi-map-clock-outline</v-icon>
                        </v-list-item-icon>
                        <v-list-item-content>
                            <v-list-item-title v-text="item.stability_zone"></v-list-item-title>
                            <v-list-item-subtitle>Stability zone</v-list-item-subtitle>
                        </v-list-item-content>
                    </v-list-item>
                    <v-list-item>
                        <v-list-item-icon>
                            <v-icon>mdi-book-variant</v-icon>
                        </v-list-item-icon>
                        <v-list-item-content>
                            <v-list-item-title v-text="item.product_class"></v-list-item-title>
                            <v-list-item-subtitle>Product class</v-list-item-subtitle>
                        </v-list-item-content>
                    </v-list-item>
                </v-list>
            </v-col>
        </v-row>
    </v-card>
</template>
<script>
export default {
    props:{
        item:{}
    }
};
</script>
<style >
</style>