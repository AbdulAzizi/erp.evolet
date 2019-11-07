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
        <v-col lg="4" md="6" sm="12" class="pt-0">
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
        <v-col lg="4" md="6" sm="12" class="pt-0">
            <v-card outlined>
                <v-card-text>
                    <div class="text-center">Журнал действий продукта</div>
                    <p v-if="!product.history.length">Нет событий</p>
                    <history :history="product.history" v-if="product.history.length" />
                </v-card-text>
            </v-card>
        </v-col>

        <v-col lg="4" md="6" sm="12" class="pt-0">
            <v-card class="mx-auto mb-2" outlined>
                <v-card-text class="pb-0">
                    <div class="text-center">Участники продукта</div>
                </v-card-text>
                <v-list disabled>
                    <v-list-item-group color="primary">
                        <v-list-item
                            v-for="(participant, i) in participants.project_participant"
                            :key="i"
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
            window: window.history
        };
    }
};
</script>
