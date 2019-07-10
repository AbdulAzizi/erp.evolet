<template>
    <div v-if="form">
        <v-fab-transition>
            <v-btn @click="addProduct" dark fab fixed bottom right small color="primary">
                <v-icon>add</v-icon>
            </v-btn>
        </v-fab-transition>
        <dynamic-form 
        :fields="form.fields" 
        :title="form.name" 
        activatorEventName="addProduct"
        actionUrl="/products"
        method="post"
        ></dynamic-form>
    </div>
</template>

<script>
export default {
    props: {
        form: {
            required: false
        }
    },
    data() {
        return {
            dialog: false
        };
    },
    methods: {
        addProduct() {
            Event.fire("addProduct");
        }
    },
    created() {},
    computed: {
        preparedFields() {
            if (this.form)
                return this.form.fields.map(field => {
                    field["rules"] = field.pivot.required
                        ? ["required"]
                        : [true];
                    return field;
                });
        }
    }
};
</script>

<style>
</style>
