<template>
    <form-field :field="localField" v-model="selectedItem" />
</template>

<script>
import { setTimeout } from "timers";
export default {
    props: {
        listName: String,
        relatedListName: String,
        value: Array
    },
    data() {
        return {
            selectedItem: this.value || null,
            localField: { ...this.$attrs, type: "autocomplete" },
            itemsHasFiltered: false
        };
    },
    created() {
        this.addRelationFilterListener();
    },
    watch: {
        selectedItem(value) {
            if (this.relatedListName) {
                const itemSelectedEvent = "filter-" + this.relatedListName;
                Event.fire(itemSelectedEvent, {
                    list: this.listName,
                    id: value
                });
            }
        }
    },
    methods: {
        addRelationFilterListener() {
            if (this.listName) {
                const relationFilterEvent = "filter-" + this.listName;
                Event.listen(relationFilterEvent, data => {
                    
                    if (!data.id) {
                        this.localField.items = this.$attrs.items;
                        return;
                    }

                    this.setLoadingState();
                    const { list, id } = data;

                    axios
                        .get("/relation-data", {
                            params: {
                                list: list,
                                listId: id,
                                foreignList: this.listName
                            }
                        })
                        .then(({ data }) => {
                            this.localField.items = data;
                            this.itemsHasFiltered = true;
                            this.disableLoadingState();
                        })
                        .catch(e => console.log(e));
                });
            }
        },
        setLoadingState() {
            this.localField["props"] = {
                disabled: true,
                loading: true
            };
        },
        disableLoadingState() {
            this.localField["props"] = {
                disabled: false,
                loading: false
            };
        }
    }
};
</script>
