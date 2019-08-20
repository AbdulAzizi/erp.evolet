<template>
    <div>
        <v-autocomplete
            v-model="selectedUsers"
            :items="preparedUsers"
            item-text="fullname"
            item-value="id"
            no-data-text="Данные отсутствуют"
            hide-selected
            chips
            multiple
            color="primary"
            :label="label"
            :prepend-icon="icon"
            :hint="hint"
            persistent-hint
            v-bind="$attrs"
        >
            <template slot="selection" slot-scope="data">
                <v-chip
                    :input-value="data.selected"
                    color="primary"
                    textColor="white"
                    pill
                    close
                    @click:close="remove(data.item)"
                >
                    <v-avatar left>
                        <img :src="photo(data.item.img)">
                    </v-avatar>
                    {{ data.item.name }}
                </v-chip>
            </template>

            <template slot="item" slot-scope="data">
                <template>
                    <v-list-item-avatar>
                        <img v-if="data.item.img" :src="photo(data.item.img)">
                    </v-list-item-avatar>
                    <v-list-item-content>
                        <v-list-item-title>{{data.item.name}} {{data.item.surname}}</v-list-item-title>
                        <v-list-item-subtitle>
                            <span
                                v-for="(responsibility,key) in data.item.responsibilities"
                                :key="'responsibility-'+key"
                            >
                                {{responsibility.name}}
                                <span
                                    v-if="key != data.item.responsibilities.length-1"
                                >|</span>
                            </span>
                            - {{data.item.division.abbreviation}}
                        </v-list-item-subtitle>
                    </v-list-item-content>
                </template>
            </template>
        </v-autocomplete>
        <input type="hidden" :name="name" :value="JSON.stringify(selectedUsers)">
    </div>
</template>

<script>
export default {
    props: {
        users: Array,
        label: String,
        icon: String,
        name: String,
        hint: String,
        value: Array
    },
    data(){
        console.log(this.value);
        return {
            selectedUsers: this.value || [],
            searchText: null
        }
    },
    methods: {
        remove(user) {            
            this.selectedUsers = this.selectedUsers.filter(
                userId => userId !== user.id
            );
        }
    },
    created() {},
    watch: {
        selectedUsers(selectedUsersId) {
            this.$emit("input", selectedUsersId);
        }
    },
    computed: {
        preparedUsers() {
            return this.users.map(user => {
                user["fullname"] = user.name + " " + user.surname;
                return user;
            });
        }
    }
};
</script>