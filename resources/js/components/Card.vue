<template>
    <v-card v-bind="$attrs" :style="styles" v-on="$listeners" class="material--card">
        <helpers-offset v-if="hasOffset" :inline="inline" :full-width="fullWidth" :offset="offset">
            <v-card
                v-if="!$slots.offset"
                :color="color"
                class="v-card--material__header"
                :style="colorfulShadow(color)"
                dark
            >
                <slot v-if="!title && !text" name="header"/>
                <span v-else>
                    <h4 class="title font-weight-light mb-2" v-text="title"/>
                    <p class="category font-weight-thin" v-text="text"/>
                </span>
            </v-card>
            <slot v-else name="offset"/>
        </helpers-offset>

        <v-card-text>
            <slot/>
        </v-card-text>

        <v-divider v-if="$slots.actions" class="mx-3"/>

        <v-card-actions v-if="$slots.actions">
            <slot name="actions"/>
        </v-card-actions>
    </v-card>
</template>

<script>

export default {
    inheritAttrs: false,

    props: {
        color: {
            type: String,
            default: "secondary"
        },
        elevation: {
            type: [Number, String],
            default: 10
        },
        inline: {
            type: Boolean,
            default: false
        },
        fullWidth: {
            type: Boolean,
            default: false
        },
        offset: {
            type: [Number, String],
            default: 24
        },
        title: {
            type: String,
            default: undefined
        },
        text: {
            type: String,
            default: undefined
        }
    },

    computed: {
        hasOffset() {
            return (
                this.$slots.header ||
                this.$slots.offset ||
                this.title ||
                this.text
            );
        },
        styles() {
            if (!this.hasOffset) return null;

            return {
                marginBottom: `${this.offset}px`,
                marginTop: `${this.offset * 2}px`
            };
        },
        
    },
    methods: {
        //
    },
    mounted() {    
    }
};
</script>

<style>
.v-card .v-offset .v-card--material__header.v-card {
    padding: 15px;
}
.v-card--material__header.v-card {
    border-radius: 4px;
}
.material--card.v-card .v-offset .category {
    color: hsla(0, 0%, 100%, 0.62);
}
.v-card .category {
    margin: 0;
}
.v-card .v-card__actions {
    margin: 0 16px 0;
    padding: 10px 0 10px;
    line-height: 22px;
}
.v-card .title {
    margin-top: 0;
    line-height: 1.5em!important;
    letter-spacing: 0!important;
    font-size: 1.125rem!important;
    margin-bottom: 5px!important;
}
.material--card.v-card .category {
    margin: 0!important;
    line-height: 22px;
    color: #999;
}
</style>
