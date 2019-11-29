<template>
    <v-autocomplete
        v-model="selectedItems"
        :items="items"
        :item-text="itemText"
        :item-value="itemValue"
        :label="label"
        v-bind="$attrs"
    >
        <template v-slot:selection="{ item, parent, selected }" v-if="$attrs.multiple">
            <v-chip color="primary" :input-value="selected" dark small>
                <span class="pr-1">{{ item[itemText] }}</span>
                <v-icon small @click="remove(item)">mdi-close</v-icon>
            </v-chip>
        </template>
    </v-autocomplete>
</template>

<script>
export default {
    props: ["name", "label", "items", "value", "itemText", "itemValue"], //All other props goes from form-field
    data() {
        return {
            selectedItems: Array.isArray(this.value) ? this.value : this.value
        };
    },
    watch: {
        selectedItems(value) {
            // console.log(value);

            this.$emit("input", value);
        }
    },
    methods: {
        remove(item) {
            if (Array.isArray(this.selectedItems)) {
                this.selectedItems = this.selectedItems.filter(
                    selected => selected !== item.id
                );
            } else {
                this.selectedItems = null;
            }
        }
    }
};
</script>