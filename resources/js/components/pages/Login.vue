<template>
    <v-content>
        <v-container class="fill-height" fluid>
            <v-row align="center" justify="center">
                <v-col cols="12" sm="8" md="4">
                    <v-form method="POST" :action="loginRoute">
                        <input
                            type="hidden"
                            name="_token"
                            :value="csrf"
                        />
                        <v-card class="elevation-12">
                            <v-toolbar color="primary" dark flat>
                                <v-toolbar-title>Форма входа</v-toolbar-title>
                            </v-toolbar>
                            <v-card-text>
                                <v-text-field
                                    label="Email"
                                    name="email"
                                    prepend-icon="person"
                                    type="string"
                                    :error-messages="errors.email"
                                    :value="oldEmail"
                                ></v-text-field>

                                <v-text-field
                                    id="password"
                                    label="Пароль"
                                    name="password"
                                    prepend-icon="lock"
                                    type="password"
                                    :error-messages="errors.password"
                                ></v-text-field>
                            </v-card-text>
                            <v-card-actions>
                                <v-spacer />
                                <v-btn color="primary" type="submit"
                                    >Войти</v-btn
                                >
                            </v-card-actions>
                        </v-card>
                    </v-form>
                </v-col>
            </v-row>
        </v-container>
    </v-content>
</template>

<script>
export default {
    props: {
        loginRoute: String,
        errors: null,
        oldEmail: String
    },
    computed: {
        csrf(){
            return Laravel.csrf;
        }
    }
};
</script>
