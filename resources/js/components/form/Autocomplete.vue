<template>
    <v-autocomplete
        v-model="selectedItems"
        :items="items"
        :item-text="itemText"
        :item-value="itemValue"
        hide-selected
        :label="label"
        v-bind="$attrs"
    >
        <template v-slot:selection="{ item, parent, selected }">
            <v-chip color="primary" :selected="selected" dark small>
                <span class="pr-1">{{ item[itemText] }}</span>
                <v-icon small @click="parent.selectItem(item)">close</v-icon>
            </v-chip>
        </template>
    </v-autocomplete>
</template>

<script>
export default {
    props: ["name", "label", "items", "itemText", "itemValue"], //All other props goes from form-field
    data() {
        return {
            selectedItems: []
        };
    },
    watch: {
        selectedItems(value) {
            this.$emit("input", value);
        }
    }
};
</script>