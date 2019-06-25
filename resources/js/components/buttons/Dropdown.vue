<template>
    <v-menu offset-y left :max-width="maxWidth" :max-height="maxHeight">
            <template v-slot:activator="{ on:menu }">
                <v-tooltip bottom>
                    <template v-slot:activator="{ on:tooltip }">
                        <v-btn icon v-on="{ ...tooltip, ...menu }">
                            <v-badge left overlap v-model="items.length">
                                <template v-slot:badge>
                                    <span>{{ items.length}}</span>
                                </template>

                                <v-icon color="grey darken-1">{{ icon }}</v-icon>
                            </v-badge>
                        </v-btn>
                    </template>

                    <span>{{tooltip}}</span>
                </v-tooltip>
            </template>

            <v-list three-lines dense class="dropdown-btn-list">
                <template v-for="(item, key) in items">
                    <v-list-tile :key="'item-'+key" avatar href="#">
                        <v-list-tile-avatar class="mt-2">
                            <img :src="item.data.avatar">
                        </v-list-tile-avatar>

                        <v-list-tile-content>
                            <!-- <v-list-tile-title>{{ item.data.title }}</v-list-tile-title> -->
                            <v-list-tile-title v-html="item.data.title"></v-list-tile-title>
                            <v-list-tile-sub-title>{{ moment(item.created_at).fromNow() }}</v-list-tile-sub-title>
                        </v-list-tile-content>
                    </v-list-tile>
                    <v-divider
                    :key="'item-divider-'+key"
                    v-if="key !== items.length-1"
                    ></v-divider>
                </template>
            </v-list>
        </v-menu>
</template>

<script>
export default {
    props:{
        items:{
            required:true
        },
        tooltip:{
            required:true
        },
        icon:{
            required:true
        },
        maxWidth:{
            required:false,
        },
        maxHeight:{
            required:false,
            default:400
        }
    }
}
</script>

<style>
    .dropdown-btn-list .v-list__tile__title{
        white-space: unset;
    }
    .dropdown-btn-list .v-list__tile, .dropdown-btn-list .v-list__tile__title{
        height: 100% !important;
    }
    .dropdown-btn-list .v-list__tile__avatar{
        align-self: baseline;
    }
    .dropdown-btn-list .v-list__tile{
        padding-top: 10px;
        padding-bottom: 10px;
    }
</style>
