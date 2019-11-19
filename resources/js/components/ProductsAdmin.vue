<template>
    <div>
        <v-container fluid class="py-0">
            <v-row align="center" justify="center">
                <v-combobox
                    v-model="pcObjects"
                    :items="pcs"
                    item-text="name"
                    item-value="id"
                    dense
                    solo
                    label="Промо Компания"
                    multiple
                    small-chips
                    deletable-chips
                    hide-details
                    class="mx-3 mt-3 mb-0"
                />
                <v-combobox
                    v-model="countryObjects"
                    :items="countries"
                    item-text="name"
                    item-value="id"
                    dense
                    solo
                    label="Страна"
                    multiple
                    small-chips
                    deletable-chips
                    hide-details
                    class="mx-3 mt-3 mb-0"
                />
                <v-select
                    v-model="selectedProcess"
                    :items="processes"
                    item-text="name"
                    item-value="id"
                    dense
                    label="Процесс"
                    solo
                    clearable
                    hide-details
                    class="mx-3 mt-3 mb-0"
                ></v-select>
                <v-row
                    align="center"
                    justify="center"
                    :class="'mx-3 mt-3 mb-0 '+ (selectedField ? 'fields_group': '')"
                >
                    <v-select
                        v-model="selectedField"
                        :items="fields"
                        item-text="label"
                        item-value="id"
                        dense
                        label="Поле"
                        solo
                        clearable
                        hide-details
                        return-object
                    ></v-select>

                    <template v-if="selectedField">
                        <v-text-field
                            v-if="selectedField.type.name == 'string'"
                            v-model="fieldValue"
                            dense
                            label="Значение"
                            solo
                            hide-details
                        ></v-text-field>
                    </template>

                    <template v-if="selectedField">
                        <v-autocomplete
                            v-model="fieldValue"
                            v-if="selectedField.type.name != 'string'"
                            label="Components"
                            :items="listItems"
                            item-text="name"
                            item-value="id"
                            solo
                            dense
                            hide-details
                        ></v-autocomplete>
                    </template>

                </v-row>
                <div class="mx-3 mt-3 mb-0">
                    <v-btn class @click="getData">Filter</v-btn>
                </div>
            </v-row>
        </v-container>
        <v-overlay :value="loading" color="white" opacity="0.7">
            <v-progress-circular color="primary" indeterminate size="64"></v-progress-circular>
        </v-overlay>
        <v-data-table
            class="mt-3"
            v-if="items.length"
            hide-default-footer
            :headers="headers"
            :items="preparedItems"
            :page.sync="page"
            item-key="id"
            :items-per-page="100"
            :fixed-header="true"
            height="calc(100vh - 202px)"
            dense
            @page-count="pageCount = $event"
        />
        <v-pagination v-if="preparedItems.length" v-model="page" :length="pageCount"></v-pagination>
    </div>
</template>

<script>
export default {
    props: ["pcs", "countries", "processes", "fields"],
    data() {
        return {
            fieldValue: null,
            loading: false,
            pcObjects: [],
            countryObjects: null,
            filters: {},
            items: [],
            preparedItems: [],
            headers: [],
            page: 1,
            pageCount: 0,
            selectedProcess: null,
            selectedField: null,
            listItems: []
        };
    },
    created() {},
    methods: {
        getData() {
            console.log(this.filters);
            
            this.loading = true;
            axios
                .get(this.appPath("api/get/products"), { params: this.filters })
                .then(response => {
                    this.loading = false;
                    this.items = response.data;
                    console.log(response);
                });
        }
    },
    watch: {
        selectedField(field) {
            if (field != null) {
                if (field.type.name != "string") {
                    axios
                        .get(this.appPath(`api/lists/${field.id}/items`))
                        .then(response => {
                            this.listItems = response.data;
                            console.log(response.data);
                        });
                }
            }else{
                delete this.filters.fields;
            }
        },
        selectedProcess(val) {
            if (val) delete this.filters.process_id;
            this.filters.process_id = val;
        },
        pcObjects(val) {
            if (val.length == 0) delete this.filters.pc_ids;
            else this.filters.pc_ids = JSON.stringify(val.map(pc => pc.id));
        },
        countryObjects(val) {
            if (val.length == 0) delete this.filters.country_ids;
            else
                this.filters.country_ids = JSON.stringify(
                    val.map(country => country.id)
                );
        },
        fieldValue(val) {
            let fieldFilter = {};
            fieldFilter[this.selectedField.id] = this.fieldValue;
            this.filters.fields = JSON.stringify([JSON.stringify(fieldFilter)]);
            console.log(this.filters);
        },
        items(val) {
            if (val.length != 0) {
                let fieldsHeaders = this.items[0].fields.map(function(field) {
                    return {
                        text: field.label,
                        value: field.label,
                        class: ["primary", "table-header"]
                    };
                });
                // merge headers
                this.headers = [
                    {
                        text: "Промо Компания",
                        value: "pc",
                        class: ["primary", "table-header"]
                    },
                    {
                        text: "Страна",
                        value: "country",
                        class: ["primary", "table-header"]
                    }
                ];
                this.headers = [...this.headers, ...fieldsHeaders];

                this.preparedItems = this.items.map(function(item) {
                    let preparedFields = {};

                    for (const field of item.fields) {
                        preparedFields[field.label] = field.pivot.value;
                    }

                    return {
                        id: item.id,
                        country: item.project.country.name,
                        pc: item.project.pc.name,
                        ...preparedFields
                    };
                });
            }
        }
    }
};
</script>
<style>
.fields_group .v-input {
    border-radius: 0;
}
.fields_group .v-input:first-child {
    border-radius: 4px 0 0 4px;
}
.fields_group .v-input:last-child {
    border-radius: 0 4px 4px 0;
}
</style>