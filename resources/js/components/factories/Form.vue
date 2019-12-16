<template>
    <v-form :action="appPath('factories')" enctype="multipart/form-data" method="post">
        <input type="hidden" name="_token" :value="csrf" />
        <v-card>
            <v-toolbar dark color="primary" flat>
                <v-toolbar-title>Новый завод</v-toolbar-title>
            </v-toolbar>
            <v-card-text>
                <v-row>
                    <v-col cols="6">
                        <v-text-field name="name" rounded filled label="Name" />
                    </v-col>
                    <v-col cols="6">
                        <v-text-field name="about" rounded filled label="About" />
                    </v-col>
                    <v-col cols="6">
                        <input name="country_id" :value="selectedCountry" type="hidden" />
                        <v-autocomplete
                            :items="countries"
                            item-text="name"
                            item-value="id"
                            v-model="selectedCountry"
                            rounded
                            filled
                            label="Country"
                        />
                    </v-col>
                    <v-col cols="6">
                        <v-autocomplete
                            :items="['I','II','III','IVA','IVB']"
                            name="stability_zone"
                            rounded
                            filled
                            label="Stability Zone"
                        />
                    </v-col>
                    <v-col cols="6">
                        <v-text-field
                            name="website"
                            rounded
                            filled
                            label="Website"
                        />
                    </v-col>
                    <v-col cols="6">
                        <v-autocomplete
                            :items="['MED','FS','MD','COSM']"
                            name="product_class"
                            rounded
                            filled
                            label="Product Class"
                        />
                    </v-col>
                    <v-col cols="6">
                        <v-file-input
                            name="logo"
                            prepend-icon="mdi-camera"
                            rounded
                            filled
                            label="Logo"
                        ></v-file-input>
                    </v-col>
                </v-row>
            </v-card-text>
            <v-card-actions>
                <v-btn text color="primary">Назад</v-btn>
                <v-spacer />
                <v-btn type="submit" depressed color="primary">Добавить</v-btn>
            </v-card-actions>
        </v-card>
    </v-form>
</template>
<script>
export default {
    props: {
        countries: {}
    },
    data() {
        return {
            selectedCountry: null
        };
    }
};
</script>
<style>
</style>