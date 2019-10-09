<template>
  <v-row>
    <v-col cols="12">
      <p class="grey--text text--darken-2">
        {{ product.current_process.name }}
      </p>
      <p class="grey--text text--darken-2 mb-0">
        {{ product.project.pc.name }} ·
        <span class="grey--text">{{ product.project.country.name }}</span>
      </p>
    </v-col>
    <v-col cols="6">
      <v-card>
        <v-simple-table fixed-header :height="'calc(100vh - 13vh)'">
          <thead>
            <tr>
              <th class="text-left">Наименование</th>
              <th class="text-left">Значение</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="field in product.fields" :key="field.id">
              <td>{{ field.label }}</td>
              <td>{{ field.pivot.value }}</td>
            </tr>
          </tbody>
        </v-simple-table>
      </v-card>
    </v-col>
    <v-col cols="6">
      <v-card outlined>
        <v-card-text>
          <history :history="product.history" />
        </v-card-text>
      </v-card>
    </v-col>
  </v-row>
</template>

<script>
export default {
  props: ["product"],
  data() {
    return {};
  },
  created() {
    console.log(this.product);
  }
};
</script>
