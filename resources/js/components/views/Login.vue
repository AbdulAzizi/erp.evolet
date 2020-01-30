<template>
    <v-container fluid fill-height>
        <v-layout align-center justify-center>
            <v-flex xs12 sm8 md4>
                <v-layout justify-center class="mb-5">
                    <v-flex xs8>
                        <v-img :src="photo('dark-logo.png')" />
                    </v-flex>
                </v-layout>
                <v-card>
                    <v-form :action="appPath('login')" method="POST">
                        <input type="hidden" name="_token" :value="csrf" />
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
                        <v-card-text>
                            <input type="hidden" name="remember" value="1" />

                            <v-text-field
                                name="email"
                                label="Email"
                                prepend-icon="mdi-account"
                                :error-messages="errors.email"
                                :value="oldInputs.email"
                            ></v-text-field>

                            <v-text-field
                                name="password"
                                type="password"
                                label="Пароль"
                                prepend-icon="mdi-lock"
                                :error-messages="errors.password"
                            ></v-text-field>
                        </v-card-text>
                        <v-card-actions>
                            <v-btn text color="primary">Забыл пароль</v-btn>
                            <v-spacer></v-spacer>
                            <v-btn type="submit" color="primary">Вход</v-btn>
                        </v-card-actions>
                    </v-form>
                </v-card>
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
        oldInputs:{
            required:false
        }
    },
    created(){
        // console.log(this.oldInputs);
    }
};
</script>