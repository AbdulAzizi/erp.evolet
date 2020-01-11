<template>
    <div>
        <component
            :is="formField.component"
            v-bind="{...formField.props}"
            v-model="fieldVar"
            ref="field"
        >
            <slot v-for="slot in Object.keys($slots)" :name="slot" :slot="slot" />

            <template v-for="slot in Object.keys($scopedSlots)" :slot="slot" slot-scope="scope">
                <slot :name="slot" v-bind="scope" />
            </template>
        </component>
        <input
            type="hidden"
            v-if="formField.hasSeparateInput"
            :name="formField.props.name"
            :value="JSON.stringify(fieldVar)"
        />
    </div>
</template>

<script>
import predefinedRules from "./predefinedRules";

const getRuleFunctions = rules => {
    if (!rules) return;
    if (!Array.isArray(rules)) return;

    let ruleFunctions = [];

    for (const rule of rules) {
        if (typeof rule !== "string") {
            ruleFunctions.push(rule);
            continue;
        }
        const predefinedFunctions = predefinedRules.map(pRule =>
            pRule.name === rule ? pRule.func : true
        );
        ruleFunctions = [...ruleFunctions, ...predefinedFunctions];
    }
    return ruleFunctions;
};

const getBaseInput = field => {
    return {
        ...field,
        component: "input",
        props: {
            name: field.name,
            value: field.value,
            type: "hidden"
        }
    };
};

const getBaseField = field => {
    const baseInput = getBaseInput(field);

    delete baseInput.props.value; //Remove value because there is model fieldVar, and I will assign value to that model
    delete baseInput.props.type; //Remove type because other fields shoudn't be hidden

    //Default props for number field
    baseInput.component = "v-text-field";
    baseInput.props = {
        ...baseInput.props,

        label: field.label,
        rules: getRuleFunctions(field.rules),
        "prepend-icon": field.icon,
        hint: field.hint,
        filled: true,
        rounded: true
    };

    return baseInput;
};

const getStringField = field => getBaseField(field);

const getNumberField = field => {
    const baseField = getBaseField(field);

    //Default props for number field
    baseField.props["type"] = "number";
    baseField.props["min"] = field.min || 1;

    return baseField;
};

const getTextField = field => {
    const baseField = getBaseField(field);

    //Default props for textarea
    baseField.component = "v-textarea";
    baseField.props["rows"] = 3;

    return baseField;
};

const getSelectField = field => {
    const baseField = getBaseField(field);

    //Default props for select field
    baseField.component = "v-select";
    baseField.props = {
        ...baseField.props,

        items: field.items,
        "item-text": "name",
        "item-value": "id"
    };

    baseField["hasSeparateInput"] = true;
    return baseField;
};

const getAutoCompleteField = field => {
    const baseSelectField = getSelectField(field);

    //Default props for autocomplete field
    baseSelectField.component = "autocomplete";
    baseSelectField.props = {
        ...baseSelectField.props,

        color: "primary",
        multiple: field.multiple ? true : false,
        hint: field.hint,
        "persistent-hint": true,
        "no-data-text": "Данные отсутствуют",
        "hide-selected": true
    };
    console.log(baseSelectField);
    
    return baseSelectField;
};

const getComboboxField = field => {
    const baseAutoComplete = getAutoCompleteField(field);

    //Default props for combobox
    baseAutoComplete.component = "combobox";

    baseAutoComplete["hasSeparateInput"] = false;
    return baseAutoComplete;
};

const getManyToManyField = field => {
    const baseAutoComplete = getAutoCompleteField(field);

    //Default props for userselector
    baseAutoComplete.component = "many-to-many-select";
    baseAutoComplete.props = {
        ...baseAutoComplete.props,
        listName: field.listName,
        relatedListName: field.relatedListName
    };

    baseAutoComplete["hasSeparateInput"] = false;
    return baseAutoComplete;
};

const getUsersField = field => {
    const baseField = getBaseField(field);

    //Default props for userselector
    baseField.component = "user-selector";
    baseField.props["icon"] = field.icon;
    baseField.props["users"] = field.users;
    baseField.props["multiple"] = field.multiple;

    return baseField;
};

const getPicker = field => {
    const baseField = getBaseField(field);

    baseField.component = "picker";
    baseField.props = {
        ...baseField.props,

        pickerType: field.type,

        picker: {
            min: field.min,
            max: field.max
        }
    };

    return baseField;
};

const getYearField = field => {
    const baseField = getBaseField(field);


    let years = [];
    let currentYear = new Date().getFullYear();

    for (let i = 1990; i <= currentYear; i++) {
        years.push(i);
    }

    //Default props for select field
    baseField.component = "v-select";
    baseField.props = {
        ...baseField.props,

        items: years.reverse()
    };

    baseField["hasSeparateInput"] = true;
    return baseField;
};

const getImagePicker = field => {
    return {
        ...field,
        component: "v-file-input",
        props: {
            name: field.name,
            multiple: true,
            filled: true,
            rounded: true,
            label: field.label,
            "prepend-icon": "mdi-camera",
        }
    }
};

export default {
    props: {
        field: Object,
        errors: Object | Array,
        oldInputs: Object | Array,
        value: null
    },

    data() {
        return {
            fieldVar: this.field.hasOwnProperty("value")
                ? this.field.value
                : null
        };
    },
    created() {
        const fieldHasOldInputs =
            this.oldInputs && this.oldInputs[this.formField.props.name];

        if (fieldHasOldInputs) {
            this.fieldVar = this.oldInputs[this.formField.props.name]; //FIXME Fix old inputs for multiple fields
        }
    },
    mounted() {
        // console.log(this.field.value);
    },
    computed: {
        formField() {
            let fieldData = null;

            switch (this.field.type) {
                case "input":
                    fieldData = getBaseInput(this.field);
                    break;
                case "string":
                    fieldData = getStringField(this.field);
                    break;
                case "text":
                    fieldData = getTextField(this.field);
                    break;
                case "number":
                    fieldData = getNumberField(this.field);
                    break;
                case "select":
                    fieldData = getSelectField(this.field);
                    break;
                case "autocomplete":
                    fieldData = getAutoCompleteField(this.field);
                    break;
                case "many-to-many-list":
                    fieldData = getManyToManyField(this.field);
                    break;
                case "combobox":
                    fieldData = getComboboxField(this.field);
                    break;
                case "users":
                    fieldData = getUsersField(this.field);
                    break;
                case "year":
                    fieldData = getYearField(this.field);
                    break;
                case "date":
                case "time":
                case "date-time":
                    fieldData = getPicker(this.field);
                    break;
                case "image":
                    fieldData = getImagePicker(this.field);
                    break;
            }

            if (this.field.props) {
                fieldData.props = { ...fieldData.props, ...this.field.props };
            }

            const fieldHasErrors =
                this.errors && this.errors[fieldData.props.name];

            if (fieldHasErrors) {
                fieldData.props["error-messages"] = this.errors[
                    fieldData.props.name
                ];
            }
            return fieldData;
        }
    },
    watch: {
        fieldVar(value) {
            this.$emit("input", value);
        },
        value(v) {
            this.fieldVar = v;
        },
        // change value when field prop is changed
        field(val){
            if(this.field.hasOwnProperty("value"))
                this.fieldVar = this.field.value
        }
    },
    methods: {
        validate(value) {
            this.$refs.field.validate(value);
        }
    }
};
</script>

<style>
</style>
