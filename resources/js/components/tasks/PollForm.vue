<template>
    <v-card class="poll-form">
        <v-toolbar flat dense color="primary" dark>
            <v-toolbar-title>{{poll.body}}</v-toolbar-title>
        </v-toolbar>

        <v-card-text class="pt-0 pb-1">
            <v-row>
                <v-radio-group v-model="selectedOption" style="width:100% !important;">
                    <template v-for="(option, index) in poll.options">
                        <v-col cols="12" :key="'progress-text-'+index">
                            <v-radio :value="option" color="primary">
                                <template v-slot:label>
                                    <v-row style="width:100% !important;">
                                        <v-col cols="12 pt-0 pb-1">{{option.body}}</v-col>
                                        <v-col cols="12 py-0" style="height:100%;">
                                            <v-progress-linear rounded height="12" value="20">
                                                <template v-slot="{ value }">
                                                    <span
                                                        class="grey--text text--darken-2 caption"
                                                        style="font-size:10px !important;"
                                                    >{{ Math.ceil(value) }}%</span>
                                                </template>
                                            </v-progress-linear>
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
            selectedOption: null
        };
    },
    created() {
        console.log(this.poll);
    },
    watch: {
        selectedOption(selectedOption) {
            // alert(selectedOption.body);
            axios
                .post(this.appPath('api/polls'), selectedOption)
                .then(function(response) {
                    console.log(response.data);
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