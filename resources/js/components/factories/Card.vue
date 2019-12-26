<template>
    <v-card>
        <v-img height="250" contain :src="photo(item.logo)"></v-img>
        <v-card-title>{{item.name}}</v-card-title>
        <v-card-text>
            <div>{{item.about}}</div>
        </v-card-text>
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
    </v-card>
</template>
<script>
export default {
    props: {
        item: {}
    }
};
</script>