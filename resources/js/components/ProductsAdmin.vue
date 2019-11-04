<template>
  <v-data-table
    :headers="headers"
    :items="preparedItems"
    item-key="id"
    hide-default-footer
    :items-per-page="100"
    :fixed-header="true"
    height="calc(100vh - 96px)"
    dense
  />
</template>

<script>
export default {
  props: ["items"],
  data() {
    return {
      preparedItems: [],
      headers: [
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
      ]
    };
  },
  created() {
    let fieldsHeaders = this.items[0].fields.map(function(field) {
      return {
        text: field.label,
        value: field.label,
        class: ["primary", "table-header"]
      };
    });
    // merge headers
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
};
</script>