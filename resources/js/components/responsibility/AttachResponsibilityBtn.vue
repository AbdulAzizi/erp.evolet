<template>
    <v-dialog eager width="600" v-model="dialog">
        <template v-slot:activator="{ on }">
            <v-btn v-on="on" depressed color="primary">Добавить должность</v-btn>
        </template>
        <v-form ref="form">
            <v-card>
                <v-toolbar
                    flat
                    dark
                    color="primary"
                    dense
                >Добавить должность {{user.name}} {{user.surname}}</v-toolbar>
                <v-card-text class="pa-4">
                    <v-select
                        :rules="[!!selectedPosition||'Обязательное поле']"
                        filled
                        rounded
                        :items="positions"
                        item-text="label"
                        item-value="id"
                        name="position"
                        label="Выберите должность"
                        v-model="selectedPosition"
                        return-object
                    ></v-select>
                    <v-select
                        :disabled="selectedPosition ? false : true"
                        filled
                        rounded
                        :items="selectedPosition ? selectedPosition.responsibilities : []"
                        item-text="text"
                        item-value="id"
                        name="responsibility"
                        label="Выберите ДЗ"
                        multiple
                        v-model="selectedResponsibilities"
                        :rules="[!!selectedResponsibilities.length||'Обязательное поле']"
                    >
                        <template v-slot:selection="{item}">
                            <v-chip small color="primary">{{item.text}}</v-chip>
                        </template>
                    </v-select>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="primary" text depressed @click="dialog = false">Отмена</v-btn>
                    <v-btn color="primary" depressed @click="attachResponsibilitiesToUser">Добавить</v-btn>
                </v-card-actions>
            </v-card>
        </v-form>
    </v-dialog>
</template>
<script>
export default {
    props: {
        user: {
            required: true
        },
        positions: {
            required: true
        }
    },
    data() {
        return {
            dialog: null,
            selectedPosition: null,
            selectedResponsibilities: []
        };
    },
    methods: {
        attachResponsibilitiesToUser() {
            if (this.$refs.form.validate()) {
                axios
                    .post(
                        this.appPath(
                            `api/users/${this.user.id}/responsibilities`
                        ),
                        {
                            position: this.selectedPosition.id,
                            responsibilities: this.selectedResponsibilities
                        }
                    )
                    .then(resp => {
                        this.dialog = false;
                        console.log(resp.data);

                        Event.fire("responsibilitiesAddedToUser", resp.data);
                    });
            }
        }
    },
    watch: {
        selectedPosition(position) {
            if (position != null) {
                let posResult = this.user.positions.filter(
                    pos => pos.id == position.id
                );
                console.log(posResult);

                if (posResult.length) {
                    this.selectedResponsibilities = posResult[0].responsibilities.map(
                        resp => {
                            return resp.id;
                        }
                    );
                }
                console.log(position);
            }
        },
        // selectedResponsibilities(val) {
        //     console.log(val);
        // }
        dialog() {
            this.selectedPosition = null;
            this.selectedResponsibilities = [];
        }
    }
};
</script>
<style>
</style>