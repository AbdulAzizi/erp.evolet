<template>
    <v-list-item>
        <v-dialog ref="deadlineDialog" v-model="dialog" max-width="500" v-if="edit">
            <template v-slot:activator="{ on }">
                <v-btn v-on="on" height="40" width="40" icon @click class="mr-4 my-2">
                    <v-icon>mdi-pencil</v-icon>
                </v-btn>
            </template>

            <v-card>
                <v-form ref="form">
                    <v-toolbar flat dense color="primary" dark>
                        <v-toolbar-title class="font-weight-regular">Изменение времени на задачу</v-toolbar-title>
                    </v-toolbar>

                    <v-col cols="12" class="pb-0">
                        <v-textarea
                            v-model="reason"
                            :rules="[rules.required]"
                            rows="3"
                            label="Укажите причину"
                            filled
                            rounded
                        ></v-textarea>
                    </v-col>
                    <v-col cols="12" class="pt-0">
                        <h1 class="subtitle-1 grey--text text--darken-1 ml-1">Время на задачу</h1>
                    </v-col>
                    <v-row class="pa-0 ma-0">
                        <v-col cols="4" class="py-0">
                            <form-field
                                :field="{
                                    type: 'number',
                                    label: 'Дни',
                                    icon: 'mdi-timelapse',
                                    rules: [rules.taskTimeRule, rules.day],
                                    min:'0'
                                }"
                                v-model="estimateDays"
                                ref="estimateDays"
                            />
                        </v-col>
                        <v-col cols="4" class="py-0">
                            <form-field
                                :field="{
                                    type: 'number',
                                    label: 'Часы',
                                    rules: [rules.taskTimeRule, rules.hour],
                                    min:'0',
                                    max:'23'
                                }"
                                v-model="estimateHours"
                                ref="estimateHours"
                            />
                        </v-col>
                        <v-col cols="4" class="py-0">
                            <form-field
                                :field="{
                                    type: 'number',
                                    label: 'Минуты',
                                    rules: [rules.taskTimeRule, rules.minute],
                                    min:'0',
                                    max:'59'
                                }"
                                v-model="estimateMinutes"
                                ref="estimateMinutes"
                            />
                        </v-col>
                    </v-row>
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
            <v-icon>mdi-timelapse</v-icon>
        </v-list-item-avatar>
        <v-list-item-content>
            <v-list-item-title>
                <span>{{durObj(planned_time)}}</span>
            </v-list-item-title>
            <v-list-item-subtitle>Время на задачу</v-list-item-subtitle>
        </v-list-item-content>
    </v-list-item>
</template>
<script>
export default {
    props: {
        task: {
            required: true
        },
        edit: {
            required: false,
            default: false
        }
    },
    data() {
        return {
            id: this.task.id,
            planned_time: this.task.planned_time,
            reason: null,
            dialog: false,
            rules: {
                required: value => !!value || "Обязательное поле",
                taskTimeRule: () => !!this.estimateTime || "Обязательное поле",
                day: val => val < 366 || "Должно быть меньше 366",
                hour: val => val < 24 || "Должно быть меньше 24",
                minute: val => val < 60 || "Должно быть меньше 60"
            },
            estimateDays: null,
            estimateHours: null,
            estimateMinutes: null,

            estimateTime: null
        };
    },
    methods: {
        submit() {
            let valid = this.$refs.form.validate();

            if (!valid) return;

            axios
                .put(this.appPath(`api/tasks/${this.id}/planned_time`), {
                    estimateTime: this.estimateTime,
                    reason: this.reason,
                })
                .then(resp => {
                    this.planned_time = resp.data;
                    this.dialog = false;
                    this.$refs.form.reset();
                    Event.fire("notify", ["Время на задачу успешно изменено"]);
                });
        },
        toMilliseconds(days, hours, minutes) {
            return days * 86400000 + hours * 3600000 + minutes * 60000;
        }
    },
    watch: {
        estimateTime(value) {
            this.$refs["estimateDays"].validate(true);
            this.$refs["estimateHours"].validate(true);
            this.$refs["estimateMinutes"].validate(true);
        },
        estimateDays(value) {
            this.estimateTime = this.toMilliseconds(
                value,
                this.estimateHours,
                this.estimateMinutes
            );
        },
        estimateHours(value) {
            this.estimateTime = this.toMilliseconds(
                this.estimateDays,
                value,
                this.estimateMinutes
            );
        },
        estimateMinutes(value) {
            this.estimateTime = this.toMilliseconds(
                this.estimateDays,
                this.estimateHours,
                value
            );
        }
    }
};
</script>