<template>
    <div v-if="task.description" class="pb-3">
        <span v-if="!editing" class="font-weight-medium pb-1">Описание</span>
        <v-btn v-if="!editing && edit" icon small @click="editing = true">
            <v-icon small>mdi-pencil</v-icon>
        </v-btn>
        <v-textarea
            ref="description"
            v-show="editing"
            label="Описание"
            counter="300"
            rows="5"
            v-model="temporaryDescription"
            maxlength="300"
            rounded
            outlined
            @blur="save"
        />
        <div v-show="!editing" v-html="description"></div>
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
            id: this.task.id,
            description: this.task.description,
            temporaryDescription: null,
            editing: false
        };
    },
    methods: {
        save() {
            if (
                this.temporaryDescription != "" &&
                this.temporaryDescription != this.description
            ) {
                // save axios
                axios
                    .put(this.appPath(`api/tasks/${this.id}/description`), {
                        description: this.temporaryDescription
                    })
                    .then(resp => {
                        this.description = resp.data;
                        Event.fire("notify", [
                            "Описание задачи успешно изменено"
                        ]);
                    });
            }
            this.editing = false;
        }
    },
    watch: {
        editing() {
            this.temporaryDescription = this.description;
            this.$nextTick(() => {
                this.$refs.description.focus();
            });
        },
        edit(val) {
            if (!val) {
                this.editing = false;
            }
        }
    }
};
</script>