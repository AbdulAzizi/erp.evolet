<template>
    <v-dialog
        v-model="pickerDialog"
        ref="pickerDialog"
        :width="width ? width : '290px'"
        v-bind="$attrs.dialog"
    >
        <template v-slot:activator="{ on:dialog }">
            <v-text-field
                v-on="{ ...dialog }"
                :prepend-icon="prependIcon"
                :label="label"
                :name="name"
                v-model="pickerDataField"
                readonly
                :rules="rules"
                v-bind="$attrs.textField"
                rounded
                filled
            />
        </template>
        <component
            :is="picker.component"
            v-bind="{...picker.props, ...$attrs.picker}"
            v-model="pickerData"
        >
            <v-layout row wrap>
                <v-flex xs12 v-if="dateWithTime" class="mx-3">
                    <picker
                        picker-type="time"
                        label="Выберите время"
                        v-model="time"
                        prepend-icon="mdi-clock-outline"
                    />
                </v-flex>
                <v-flex xs12 class="text-xs-right ma-1">
                    <v-btn text color="primary" @click="pickerDialog = false">Отмена</v-btn>
                    <v-btn text color="primary" @click="saveData()">Выбрать</v-btn>
                </v-flex>
            </v-layout>
        </component>
    </v-dialog>
</template>

<script>
const datePicker = {
    component: "v-date-picker",
    props: {
        scrollable: true,
        color: "primary"
    }
};
const timePicker = {
    component: "v-time-picker",
    props: {
        "full-width": true,
        color: "primary"
    }
};

export default {
    props: {
        pickerType: {
            type: String,
            required: true
        },
        name: String,
        label: String,
        prependIcon: String,
        rules: Array,
        width: String,
        value: String
    },
    data() {
        return {
            pickerData: "",
            pickerDataField: this.value || "",
            pickerDialog: false,

            //Date with time mode
            dateWithTime: false,
            time: ""
        };
    },
    computed: {
        picker() {
            switch (this.pickerType) {
                case "date-time":
                    this.dateWithTime = true;
                    return datePicker;
                    break;
                case "date":
                    return datePicker;
                    break;
                case "time":
                    return timePicker;
                    break;
            }
        }
    },
    methods: {
        saveData() {
            this.pickerDataField = `${this.pickerData} ${this.time}`;
            this.pickerDialog = false;
        }
    },
    watch: {
        pickerDataField(value) {
            this.$emit("input", value);
        }
    }
};
</script>

<style>
</style>
