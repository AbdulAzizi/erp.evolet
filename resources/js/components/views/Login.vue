<template>
    <v-container fluid fill-height>
        <v-layout align-center justify-center>
            <v-flex xs12 sm8 md4>
                <v-layout justify-center class="mb-5">
                    <v-flex xs8>
                        <v-img :src="photo('dark-logo.png')" />
                    </v-flex>
                </v-layout>
                <v-form ref="form" :action="appPath('login')" method="POST">
                    <input type="hidden" name="_token" :value="csrf" />
                    <v-card>
                        <v-toolbar dark flat color="primary">
                            <v-toolbar-title>Авторизация</v-toolbar-title>

                            <v-spacer></v-spacer>
                            <!-- <v-tooltip bottom>
                            <v-btn 
                            icon
                            slot="activator"
                            >
                                <v-icon>assignment</v-icon>
                            </v-btn>
                            <span>Регистрация</span>
                            </v-tooltip>-->
                        </v-toolbar>
                        <v-card-text class="pb-0">
                            <input type="hidden" name="remember" value="1" />

                            <v-text-field
                                v-model="email"
                                filled
                                rounded
                                name="email"
                                label="Email"
                                prepend-inner-icon="mdi-account"
                                :error-messages="email == oldInputs.email ?  errors.email : ''"
                                :rules="[required]"
                            ></v-text-field>

                            <v-text-field
                                :rules="[required]"
                                filled
                                rounded
                                name="password"
                                type="password"
                                label="Пароль"
                                prepend-inner-icon="mdi-lock"
                                :error-messages="errors.password"
                            ></v-text-field>
                        </v-card-text>
                        <v-card-actions class="pt-0">
                            <v-btn
                                text
                                color="primary"
                                :href="appPath('password/reset')"
                            >Забыл пароль</v-btn>
                            <v-spacer></v-spacer>
                            <v-btn
                                @click.prevent="submit"
                                type="submit"
                                color="primary"
                                depressed
                            >Вход</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-form>
            </v-flex>
        </v-layout>
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
        }
    },
    data() {
        return {
            email: this.oldInputs.email,
            required: value => !!value || "This field is required."
        };
    },
    created() {
        // console.log(this.oldInputs);
    },
    methods: {
        submit() {
            let valid = this.$refs.form.validate();
            if (valid) {
                this.$refs.form.$el.submit();
            }
        }
    }
};
</script>