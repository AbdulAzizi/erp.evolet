<template>
    <card v-bind="$attrs" class="v-card--material-stats" v-on="$listeners">
        <v-card slot="offset" :style="colorfulShadow(color)" :color="color" class="pa-4" dark>
            <v-icon size="40" :color="iconColor">{{ icon }}</v-icon>
        </v-card>
        <div class="text-xs-right">
            <p class="category grey--text font-weight-light" v-text="title"/>
            <h3 class="title display-1 font-weight-light">
                {{value}}
                <small v-if="smallValue">{{ smallValue }}</small>
            </h3>
        </div>

        <template slot="actions" v-if="subIcon || subText">
            <v-icon :color="subIconColor" size="20" class="mr-2">{{ subIcon }}</v-icon>
            <span :class="subTextColor" class="caption font-weight-light" v-text="subText"/>
        </template>
    </card>
</template>

<script>
import Card from "./Card";

export default {
    inheritAttrs: false,

    props: {
        ...Card.props,
        icon: {
            type: String,
            required: true
        },
        iconColor: {
            type: String,
            default: undefined
        },
        subIcon: {
            type: String,
            default: undefined
        },
        subIconColor: {
            type: String,
            default: undefined
        },
        subTextColor: {
            type: String,
            default: undefined
        },
        subText: {
            type: String,
            default: undefined
        },
        title: {
            type: String,
            default: undefined
        },
        value: {
            type: String,
            default: undefined
        },
        smallValue: {
            type: String,
            default: undefined
        }
    }
};
</script>

<style>
.v-card--material-stats {
    display: flex;
    flex-wrap: wrap;
    position: relative;
}
.v-card--material-stats.v-card .v-offset {
    position: absolute;
}
.v-card--material-stats .v-offset {
    display: inline-block;
    flex: 0 1;
    margin-top: 0;
    margin-left: 0;
    margin-right: auto;
    margin-bottom: 0 !important;
    max-width: auto;
    padding: 0 16px 0;
}

.v-card--material-stats .v-card {
    border-radius: 4px;
    flex: 0 1 auto;
}

.v-card--material-stats .v-card__text {
    display: inline-block;
    position: absolute;
    top: 0;
    right: 0;
    width: 100%;
}

.v-card--material-stats .v-card__actions {
    flex: 1 0 100%;
}
.v-card--material-stats.v-card:not(.v-card--material__header),
.v-card--material-chart.v-card:not(.v-card--material__header) {
    margin: 25px 0 !important;
    margin-bottom: 0 !important;
}
.v-sheet {
    display: block;
    border-radius: 2px;
    position: relative;
    -webkit-transition: 0.3s cubic-bezier(0.25, 0.8, 0.5, 1);
    transition: 0.3s cubic-bezier(0.25, 0.8, 0.5, 1);
}
.v-card--material-stats.v-card .v-card__text {
    text-align: right;
    padding-top: 10px;
    position: relative;
}
.v-card--material-stats.v-card .title {
    margin: 0 !important;
    line-height: 1.5em !important;
    letter-spacing: 0 !important;
    font-size: 1.5625rem !important;
}
.v-card--material-stats.v-card .v-card__actions .caption {
    color: #999;
}
.theme--light.v-icon {
    color: #999;
}
.v-card--material-stats.v-card .v-card__actions i {
    position: relative;
    top: -1px;
    font-size: 16px !important;
}
</style>
