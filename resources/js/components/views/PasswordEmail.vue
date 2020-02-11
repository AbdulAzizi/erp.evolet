<template>
    <v-container fluid fill-height>
        <v-row align="center" justify="center">
            <v-form :action="appPath('password/email')" method="POST">
                <input type="hidden" name="_token" :value="csrf" />
                <v-card>
                    <v-toolbar dense dark flat color="primary">
                        <v-toolbar-title>Сброс пароля</v-toolbar-title>
                        <v-spacer></v-spacer>
                    </v-toolbar>
                    <v-card-text>
                        <v-alert v-if="status" dense text color="green">{{status}}</v-alert>
                        <v-text-field
                            filled
                            rounded
                            name="email"
                            label="Email"
                            prepend-inner-icon="mdi-account"
                            :error-messages="errors.email"
                            :value="oldInputs.email"
                        ></v-text-field>
                        <v-btn rounded block type="submit" color="primary" depressed>Вход</v-btn>
                    </v-card-text>
                </v-card>
            </v-form>
        </v-row>
    </v-container>
</template>

<script>
export default {
    props: {
        errors: {
            required: false
        },
        oldInputs: {
            required: false
        },
        status: {
            required: false
        }
    }
};
</script>
<style>
</style>