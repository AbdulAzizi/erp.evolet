<template>
    <v-list-item>
        <v-dialog ref="deadlineDialog" v-model="dialog" max-width="400" v-if="edit">
            <template v-slot:activator="{ on }">
                <v-btn v-on="on" height="40" width="40" icon @click class="mr-4 my-2">
                    <v-icon>mdi-pencil</v-icon>
                </v-btn>
            </template>

            <v-card>
                <v-form ref="form">
                    <v-toolbar flat dense color="primary" dark>
                        <v-toolbar-title class="font-weight-regular">Изменение дедлайна</v-toolbar-title>
                    </v-toolbar>
                    <v-card-text class="pt-6">
                        <v-textarea
                            v-model="reason"
                            :rules="[rules.required]"
                            rows="3"
                            label="Укажите причину"
                            filled
                            rounded
                        ></v-textarea>
                        <form-field
                            :field="{
                                type: 'date-time',
                                name: 'deadline',
                                label: 'Дедлайн',
                                rules: [rules.date],
                            }"
                            v-model="formDeadline"
                        />
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="primary" text @click="dialog = false">Отмена</v-btn>
                        <v-btn
                            color="primary"
                            depressed
                            type="submit"
                            @click.prevent="submit"
                        >Изменить</v-btn>
                    </v-card-actions>
                </v-form>
            </v-card>
        </v-dialog>
        <v-list-item-avatar v-else>
            <v-icon>mdi-calendar-clock</v-icon>
        </v-list-item-avatar>
        <v-list-item-content>
            <v-list-item-title>{{ moment(deadline).local().format('DD-MM-Y HH:mm') }}</v-list-item-title>
            <v-list-item-subtitle>Дедлайн</v-list-item-subtitle>
        </v-list-item-content>
    </v-list-item>
</template>
<script>
export default {
    props: {
        task: {
            required: false
        },
        edit: {
            required: false,
            default: false
        }
    },
    data() {
        return {
            id: this.task.id,
            dialog: false,
            deadline: this.task.deadline,
            formDeadline: null,
            reason: null,
            rules: {
                required: value => !!value || "Обязательное поле",
                date: val =>
                    !isNaN(new Date(val.replace(/-/g, "/"))) || "Выберите дату"
            }
        };
    },
    methods: {
        submit() {
            let valid = this.$refs.form.validate();

            if (!valid) return;

            axios
                .put(this.appPath(`api/tasks/${this.id}/deadline`), {
                    deadline: this.deadlineWithTz,
                    reason: this.reason
                })
                .then(resp => {
                    this.deadline = resp.data;
                    this.dialog = false;

                    Event.fire("notify", ["Дедлайн задачи успешно изменено"]);
                });
        }
    },
    computed: {
        deadlineWithTz() {
            let offset = new Date().getTimezoneOffset();
            return this.moment(this.formDeadline, "YYYY-MM-DD HH:mm")
                .utcOffset(offset)
                .format("YYYY-MM-DD HH:mm");
        }
    }
};
</script>