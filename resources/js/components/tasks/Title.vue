<template>
    <div style="align-items: center; display: flex; position: relative; z-index: 0;">
        <v-toolbar-title v-if="!editing">{{title}}</v-toolbar-title>
        <v-btn v-if="!editing && edit" icon @click="editing = true">
            <v-icon>mdi-pencil</v-icon>
        </v-btn>

        <v-autocomplete
            v-if="editing"
            v-model="selectedResponsibilityDescritionID"
            rounded
            dense
            :disabled="userResponsibilityDescriptions.length == 0"
            :items="userResponsibilityDescriptions"
            item-text="text"
            item-value="id"
            :rules="[required=>!!selectedResponsibilityDescritionID || 'Обязательное поле']"
            hide-details
            outlined
            @blur="save"
        >
            <template v-slot:item="data">
                <v-list-item-content>{{data.item.text}}</v-list-item-content>
            </template>
        </v-autocomplete>
    </div>
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
            title: this.task.responsibility_description.text,
            editing: false,
            userResponsibilityDescriptions: [],
            selectedResponsibilityDescritionID: this.task
                .responsibility_description.id
        };
    },
    methods: {
        save() {
            axios
                .put(
                    this.appPath(
                        `api/tasks/${this.task.id}/responsibilitydescription`
                    ),
                    {
                        responsibility_description_id: this
                            .selectedResponsibilityDescritionID
                    }
                )
                .then(resp => {
                    // Event.fire(
                    //     `tasks/${this.task.id}/responsibilitydescription/changed`,
                    //     resp.data
                    // );
                    this.editing = false;
                    this.title = resp.data.text;
                    Event.fire('notify', ['Категория задачи успешно изменена']);
                });
        },
        fetchResponsibilityDescription() {
            axios
                .get(
                    this.appPath(
                        `api/users/${this.task.responsible_id}/responsibilitydescriptions`
                    )
                )
                .then(response => {
                    this.userResponsibilityDescriptions = response.data;
                });
        }
    },
    watch: {
        editing(val) {
            if (val) {
                this.fetchResponsibilityDescription();
            }
        },
        edit(val){
            if(!val){
                this.editing = false;
            }
        }
    }
};
</script>
<style>
</style>