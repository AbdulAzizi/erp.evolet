<template>
  <v-row>
    <v-col cols="12">
      <p class="grey--text text--darken-2 mb-1">{{ product.current_process.name }}</p>
      <p class="grey--text text--darken-2 mb-1">
        {{ product.project.pc.name }} ·
        <span class="grey--text">{{ product.project.country.name }}</span>
      </p>
      <v-btn small depressed color="primary" class="mb-0" @click="window.back()">Назад</v-btn>
    </v-col>
    <v-col cols="6" class="pt-0">
      <v-card class="pt-0">
        <v-simple-table fixed-header dense :height="'calc(100vh - 30vh)'">
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
    <v-col cols="6" class="pt-0">
      <v-card outlined>
        <v-card-text>
          <p v-if="!product.history.length">Нет событий</p>
          <history :history="product.history" v-if="product.history.length" />
        </v-card-text>
      </v-card>
    </v-col>
  </v-row>
</template>

<script>
export default {
  props: ["product"],
  data() {
    return {
      window: window.history
    };
  }
};
</script>
