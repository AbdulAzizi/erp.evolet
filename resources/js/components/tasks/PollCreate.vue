<template>
    <v-form ref="pollForm" :action="url" method="post">
        <v-card>
            <v-toolbar flat dense color="primary" dark>
                <v-toolbar-title>Новый опрос</v-toolbar-title>
            </v-toolbar>

            <v-card-text>
                <form-field
                    :field="{
                    type:'string',
                    name:'question',
                    label: 'Вопрос',
                    }"
                    v-model="question"
                />

                <v-divider class="pb-5"></v-divider>

                <v-text-field
                    v-for="(option, index) in options"
                    :key="'option-'+index"
                    v-model="options[index]"
                    append-icon="mdi-close"
                    filled
                    rounded
                    :label="'Вариант '+ (index+1)"
                    type="text"
                    @click:append="remove(index)"
                    :hint="getHintsMessage(options, index)"
                    persistent-hint
                ></v-text-field>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn
                    v-if="options.length > 2 && question != ''"
                    text
                    color="primary float-right"
                    type="submit"
                    @click.prevent="submit()"
                >Добавить</v-btn>
            </v-card-actions>
        </v-card>
    </v-form>
</template>

<script>
export default {
    props: {
        // button: {
        //     default: false,
        //     required: false
        // },
        url: {
            required: false
        },
        returnEventName: {
            required: false
        }
    },
    data() {
        return {
            options: [""],
            question: ""
        };
    },
    methods: {
        submit() {
            if (this.returnEventName) {
                Event.fire(this.returnEventName, 
                    { question: this.question, options: this.options }
                );
            } else if (url) {
                this.$refs.pollForm.submit();
            }
        },
        remove(index) {
            this.options.splice(index, 1);
        },
        getHintsMessage(array, index) {
            // if not last element
            if (index != array.length - 1)
                //return nothing
                return "";
            else {
                // if 10th element and not empty
                if (array.length == 10 && array[index] != "")
                    return "Вы добавили максимальное количество вариантов";
                // if not
                else return "Вариантов осталось " + (10 - index);
            }
        }
    },
    watch: {
        options(val) {
            if (val[val.length - 1] != "" && val.length <= 9) val.push("");
        }
    }
};
</script>

<style>
</style>