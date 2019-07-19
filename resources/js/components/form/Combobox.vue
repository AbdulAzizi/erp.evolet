<template>
    <div>
        <v-combobox
            v-model="selectedItems"
            :items="items"
            :item-text="itemText"
            :label="label"
            v-bind="$attrs"
        >
            <template v-slot:selection="{ item, parent, selected }">
                <v-chip color="primary" :input-value="selected" dark small>
                    <span class="pr-1">{{ item[itemText] }}</span>
                    <v-icon small @click="parent.selectItem(item)">mdi-close</v-icon>
                </v-chip>
            </template>
        </v-combobox>
        <input type="hidden" :name="'new'+name" :value="pluck(newItems, itemValue)"/>
        <input type="hidden" :name="name" :value="pluck(existingItems, itemValue)"/>
    </div>
</template>

<script>
export default {
    props: ["name", "label", "items", "value", "itemText", 'itemValue'],
    data() {
        return {
            selectedItems: this.initSelectedItems(),
        };
    },
    methods: {
        initSelectedItems(){
            return Array.isArray(this.value) ? this.items.filter(item => this.value.includes(item.id)) : []
        }
    },
    watch: {
        selectedItems(newValue, oldValue) {
            this.$emit('input', newValue);
            if(newValue.length === oldValue.length) return;

            this.selectedItems = newValue.map(item => {
                if (typeof item === "string") {
                    return {
                        id: -1,
                        [this.itemText]: item
                    };
                }
                return item;
            });
        }
    },
    computed: {
        newItems() {
            return Array.isArray(this.selectedItems) ? this.selectedItems.filter(item => item.id === -1): null;
        },
        existingItems() {
            return Array.isArray(this.selectedItems) ? this.selectedItems.filter(item => item.id !== -1): null;
        }
    }
};
</script>