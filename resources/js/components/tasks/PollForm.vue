<template>
    <v-card class="poll-form" flat>
        <v-toolbar flat dense color="primary" dark>
            <v-toolbar-title>{{localPoll.body}}</v-toolbar-title>
        </v-toolbar>

        <v-card-text class="pt-0 pb-1">
            <v-row>
                <v-radio-group v-model="selectedOption" style="width:100% !important;" hide-details>
                    <template v-for="(option, index) in localPoll.options">
                        <v-col cols="12 py-0" :key="'progress-text-'+index">
                            <v-radio :value="option" color="primary">
                                <template v-slot:label>
                                    <v-row style="width:100% !important;">
                                        <v-col cols="12 pt-0 pb-1">{{option.body}}</v-col>
                                        <v-col cols="12 py-0" style="height:100%;">
                                            <v-menu offset-y open-on-hover >
                                                <template v-slot:activator="{ on:menu }">
                                                    <v-progress-linear
                                                        v-on="{ ...menu }"
                                                        rounded
                                                        height="12"
                                                        :value="Math.round(( 100 / localPoll.answers_count ) * option.users.length )"
                                                    >
                                                        <template v-slot="{ value }">
                                                            <span
                                                                class="grey--text text--darken-2 caption"
                                                                style="font-size:10px !important;"
                                                            >{{ value }}%</span>
                                                        </template>
                                                    </v-progress-linear>
                                                </template>
                                                <v-card max-width="auto" v-if="option.users.length">
                                                    <v-card-text>
                                                        <avatars-set :items="option.users" />
                                                    </v-card-text>
                                                </v-card>
                                            </v-menu>
                                        </v-col>
                                    </v-row>
                                </template>
                            </v-radio>
                        </v-col>
                    </template>
                </v-radio-group>
            </v-row>
        </v-card-text>
    </v-card>
</template>

<script>
export default {
    props: {
        poll: {
            required: true
        }
    },
    data() {
        return {
            localPoll: this.poll,
            selectedOption: null
        };
    },
    watch: {
        selectedOption(selectedOption) {
            let self = this;
            axios
                .post(this.appPath("api/polls"), {
                    poll: this.poll,
                    selected_option: selectedOption
                })
                .then(function(response) {
                    self.localPoll = response.data;
                })
                .catch(function(error) {
                    console.log(error);
                });
        }
    }
};
</script>
<style>
.poll-form .v-input__control {
    width: 100%;
}
.poll-form .v-label {
    width: 100%;
}
</style>