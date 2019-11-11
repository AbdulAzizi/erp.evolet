<template>
  <v-row>
    <v-col cols="12" class="pt-0">
      <p class="title mb-0">
        {{ product.project.country.name }}
        ·
        {{ product.project.pc.name }}
      </p>
      <p class="grey--text text--darken-2 mb-1">
        <span class="grey--text">Текущий процесс:</span>
        {{ product.current_process.name }}
      </p>
      <v-btn small depressed color="primary" class="mb-0" @click="window.back()">Назад</v-btn>
    </v-col>
    <v-col cols="4" class="pt-0">
      <v-card flat class="pt-0">
        <v-simple-table fixed-header dense>
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
    <v-col cols="4" class="pt-0">
      <v-card outlined>
        <v-card-text>
          <div class="text-center">Журнал действий продукта</div>
          <p v-if="!product.history.length">Нет событий</p>
          <history :history="product.history" v-if="product.history.length" />
        </v-card-text>
      </v-card>
      <v-card outlined class="mt-3 pa-0">
        <v-card-text class="px-0">
          <div class="text-center">История статуса</div>
          <p v-if="!product.processes.length">Нет событий</p>
          <v-simple-table dense class="mt-3">
            <template v-slot:default>
              <thead>
                <tr>
                  <th class="text-left">Статус</th>
                  <th class="text-left">Дата</th>
                  <th class="text-left">Потраченное время</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(status, index) in getDuration" :key="index">
                  <td>{{status.name}}</td>
                  <td>{{moment(status.pivot.created_at).local().format('DD-M-YY HH:mm')}}</td>
                  <td v-if="status.spent_time">
                      <span
                  v-if="durObj(status.spent_time).days()"
                >{{ durObj(status.spent_time).days() }}д</span>
                <span
                  v-if="durObj(status.spent_time).hours()"
                >{{ durObj(status.spent_time).hours() }}ч</span>
                <span
                  v-if="durObj(status.spent_time).minutes()"
                >{{ durObj(status.spent_time).minutes() }}м</span>
                  </td>
                </tr>
              </tbody>
            </template>
          </v-simple-table>
        </v-card-text>
      </v-card>
    </v-col>

    <v-col cols="4" class="pt-0">
      <v-card class="mx-auto mb-2" outlined>
        <v-card-text class="pb-0">
          <div class="text-center">Участники продукта</div>
        </v-card-text>
        <v-list disabled>
          <v-list-item-group color="primary">
            <v-list-item
              v-for="(participant, index) in participants.project_participant"
              :key="index"
            >
              <v-list-item-avatar>
                <v-img :src="photo(participant.img)"></v-img>
              </v-list-item-avatar>
              <v-list-item-content>
                <v-list-item-title>{{participant.role.name}}</v-list-item-title>
                <v-list-item-subtitle>{{participant.participant.name}} {{participant.participant.surname}}</v-list-item-subtitle>
              </v-list-item-content>
            </v-list-item>
          </v-list-item-group>
        </v-list>
      </v-card>
      <messages :messageable="product" type="App\Product" class="mt-5" />
    </v-col>
  </v-row>
</template>

<script>
export default {
  props: ["product", "participants"],
  data() {
    return {
      window: window.history,
      localProducts: this.product,
      generatedData: null
    };
  },
  computed: {
  getDuration() {
      if(this.localProducts.processes.length > 1){
          return this.localProducts.processes.reduce((prev, next) => {
              prev['spent_time'] = this.moment(next.pivot.created_at).diff(this.moment(prev.pivot.created_at))
              return this.localProducts.processes;
          });
      }
      else {
          return this.localProducts.processes;
      }
    }
  },
  created() {
    console.log(this.getDuration);
  }
};
</script>

<style>
    table{
        width: 100%
    }
</style>
