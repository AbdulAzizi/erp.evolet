<template>
    <div>
        <v-container fluid class="py-0">
            <v-row>
                <v-col>
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
                    />
                </v-col>
                <v-col>
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
                    />
                </v-col>
                <v-col>
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
                    ></v-select>
                </v-col>
            </v-row>
        </v-container>
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
            height="calc(100vh - 154px)"
            dense
            @page-count="pageCount = $event"
        />
        <v-pagination v-if="preparedItems.length" v-model="page" :length="pageCount"></v-pagination>
    </div>
</template>

<script>
export default {
    props: ["pcs", "countries", "processes"],
    data() {
        return {
            loading: false,
            pcObjects: [],
            countryObjects: null,
            filters: {},
            items: [],
            preparedItems: [],
            headers: [],
            page: 1,
            pageCount: 0,
            selectedProcess: null
        };
    },
    created() {},
    methods: {
        getData() {
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
        selectedProcess(val) {
            if (val) delete this.filters.process_id;
            this.filters.process_id = val;

            this.getData();
        },
        pcObjects(val) {
            if (val.length == 0) delete this.filters.pc_ids;
            else this.filters.pc_ids = JSON.stringify(val.map(pc => pc.id));

            this.getData();
        },
        countryObjects(val) {
            if (val.length == 0) delete this.filters.country_ids;
            else
                this.filters.country_ids = JSON.stringify(
                    val.map(country => country.id)
                );

            this.getData();
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