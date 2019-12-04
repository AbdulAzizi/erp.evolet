<template>
    <div>
        <v-fab-transition>
            <v-btn
                @click="displayRightDrawer = !displayRightDrawer"
                color="primary"
                fab
                dark
                bottom
                right
                fixed
            >
                <v-icon>mdi-filter</v-icon>
            </v-btn>
        </v-fab-transition>

        <v-navigation-drawer v-model="displayRightDrawer" right fixed temporary>
            <v-list-item>
                <v-list-item-content>
                    <v-list-item-title class="title">Фильтры</v-list-item-title>
                </v-list-item-content>
            </v-list-item>

            <v-divider></v-divider>
            <v-card tile flat>
                <v-card-text>
                    <v-combobox
                        v-model="selectedFile"
                        :items="files"
                        item-text="name"
                        item-value="id"
                        dense
                        outlined
                        label="Файл"
                    />
                    <v-combobox
                        v-model="pcObjects"
                        :items="pcs"
                        item-text="name"
                        item-value="id"
                        dense
                        outlined
                        label="Промо Компания"
                        multiple
                        small-chips
                        deletable-chips
                    />
                    <v-combobox
                        v-model="countryObjects"
                        :items="countries"
                        item-text="name"
                        item-value="id"
                        dense
                        outlined
                        label="Страна"
                        multiple
                        small-chips
                        deletable-chips
                    />
                    <v-select
                        v-model="selectedProcess"
                        :items="processes"
                        item-text="name"
                        item-value="id"
                        dense
                        label="Процесс"
                        outlined
                        clearable
                    ></v-select>
                    <template v-for="(field, index) in filterFields">
                        <v-divider class="py-3" :key="'divider-'+index"></v-divider>
                        <v-select
                            v-model="field.selectedField"
                            :items="fields"
                            item-text="label"
                            item-value="id"
                            dense
                            label="Поле"
                            outlined
                            clearable
                            return-object
                            :key="'field-'+index"
                            @change="handleFieldChange(field)"
                        ></v-select>
                        <template v-if="field.selectedField">
                            <v-text-field
                                v-if="field.selectedField.type.name == 'string'"
                                v-model="field.fieldValue"
                                dense
                                label="Значение"
                                outlined
                                :key="'fieldValue-'+index"
                                @change="synchronizeFieldsAndFilters()"
                            ></v-text-field>
                        </template>

                        <template v-if="field.selectedField">
                            <v-autocomplete
                                v-model="field.fieldValue"
                                v-if="field.selectedField.type.name != 'string'"
                                label="Components"
                                :items="field.list"
                                item-text="name"
                                item-value="id"
                                dense
                                outlined
                                :key="'field-list-'+index"
                                @change="synchronizeFieldsAndFilters()"
                            ></v-autocomplete>
                        </template>
                    </template>

                    <v-btn outlined block color="primary" class="mb-5" @click="addNewFilterField">
                        <v-icon left>mdi-plus</v-icon>Добавить поле
                    </v-btn>
                    <v-btn depressed block color="primary" class="mb-5" @click="getData">Filter</v-btn>
                </v-card-text>
            </v-card>
        </v-navigation-drawer>

        <v-overlay :value="loading" color="white" opacity="0.7">
            <v-progress-circular color="primary" indeterminate size="64"></v-progress-circular>
        </v-overlay>

        <v-data-table
            v-if="items.length"
            hide-default-footer
            :headers="headers"
            :items="preparedItems"
            :page.sync="page"
            item-key="id"
            :items-per-page="100"
            :fixed-header="true"
            height="calc(100vh - 92px)"
            dense
            @page-count="pageCount = $event"
        />
        <v-pagination v-if="preparedItems.length" v-model="page" :length="pageCount"></v-pagination>
    </div>
</template>

<script>
export default {
    props: ["pcs", "countries", "processes", "fields", "files"],
    data() {
        return {
            selectedFile: null,
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
            listItems: [],
            displayRightDrawer: false,
            filterFields: [{ selectedField: null, fieldValue: null, list: [] }]
        };
    },
    methods: {
        getData() {
            this.loading = true;
            axios
                .get(this.appPath("api/get/products"), {
                    params: {
                        ...this.filters
                        // fields: JSON.stringify(this.filters.fields)
                    }
                })
                .then(response => {
                    this.loading = false;
                    this.items = response.data;
                });
        },
        handleFieldChange(field) {
            // if selected field is not null
            if (field.selectedField != null) {
                //
                field.fieldValue = null;
                // if selected type is not input text
                if (field.selectedField.type.name != "string") {
                    axios
                        .get(
                            this.appPath(
                                `api/lists/${field.selectedField.id}/items`
                            )
                        )
                        .then(response => {
                            field.list = response.data;
                        });
                }
            }
            // if selected field is null
            else {
                // loop through filterFields
                this.filterFields.forEach((filterField, index) => {
                    // if field is null and there is more than one field
                    if (
                        filterField.selectedField == null &&
                        this.filterFields.length > 1
                    ) {
                        // delete field
                        this.filterFields.splice(index, 1);
                    } else {
                        // remove field filter
                        delete this.filters.fields;
                    }
                });
            }
            this.synchronizeFieldsAndFilters();
        },
        synchronizeFieldsAndFilters() {
            // get filters that has value
            let fields = this.filterFields.filter(
                filterField => filterField.fieldValue != null
            );
            // if there are fields that has values
            if (fields.length != 0) {
                // restructure and initialize fields to filter
                this.filters.fields = fields.map(field => {
                    return {
                        field_id: field.selectedField.id,
                        value: field.fieldValue
                    };
                });
            }
            // no fields that has value
            else {
                delete this.filters.fields;
            }
        },
        addNewFilterField() {
            this.filterFields.push({
                selectedField: null,
                fieldValue: null,
                list: []
            });
        }
    },
    watch: {
        selectedFile(file) {
            if (file) this.filters.file_id = file.id;
            else delete this.filters.file_id;
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