<template>
    <v-container fluid fill-height>
        <v-row align="center" justify="center">
            <v-form :action="appPath('password/reset')" method="POST">
                <input type="hidden" name="_token" :value="csrf" />
                <v-card min-width="350">
                    <v-toolbar dense dark flat color="primary">
                        <v-toolbar-title>Установка пароля</v-toolbar-title>
                        <v-spacer></v-spacer>
                    </v-toolbar>
                    <v-card-text>
                        <input type="hidden" name="token" :value="token" />
                        <v-text-field
                            filled
                            rounded
                            name="email"
                            label="Email"
                            prepend-inner-icon="mdi-account"
                            :error-messages="errors.email"
                            :value="oldInputs.email"
                        ></v-text-field>
                        <v-text-field
                            filled
                            rounded
                            name="password"
                            type="password"
                            label="Пароль"
                            prepend-inner-icon="mdi-lock"
                            :error-messages="errors.password"
                        ></v-text-field>
                        <v-text-field
                            filled
                            rounded
                            name="password_confirmation"
                            type="password"
                            label="Подтверждение пароля"
                            prepend-inner-icon="mdi-lock"
                        ></v-text-field>
                        <v-btn rounded block type="submit" color="primary" depressed>Установить</v-btn>
                    </v-card-text>
                </v-card>
            </v-form>
        </v-row>
    </v-container>
</template>
<script>
export default {
    props: {
        token: {
            required: true
        },
        errors: {
            required: false
        },
        oldInputs: {
            required: false
        }
    }
};
</script>
<style>
</style>