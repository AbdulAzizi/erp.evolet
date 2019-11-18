<template>
  <div>
    <dynamic-form :fields="preparedFields" title="Forms" :fieldsPerRows="[2]"></dynamic-form>
  </div>
</template>

<script>
export default {
  props: ["product"],
  data() {
    return {
      localProduct: this.product,
      preparedForms: []
    };
  },
  computed: {
    preparedFields() {
      return this.localProduct.fields_with_lists.map(field => {
        return {
          ...field,
          rules: field.pivot && field.pivot.required ? ["required"] : [true],
          type: (field.type.name == "list" || field.type.name == "many-to-many-list") ? "autocomplete" : field.type.name,
          value: field.pivot.value
        };
      });
    }
  },
  created(){
    // console.log(this.preparedFields);
  }
};
</script>