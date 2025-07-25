<template>
  <div>
    <v-autocomplete
      v-model="selectedUsers"
      :items="users ? users : preparedDivisions"
      item-text="fullname"
      item-value="id"
      no-data-text="Данные отсутствуют"
      hide-selected
      chips
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
          :close="removeable"
          @click:close="remove(data.item)"
        >
          <v-avatar left>
            <img :src="photo(data.item.img)" />
          </v-avatar>
          {{ data.item.name }} {{ data.item.surname }}
        </v-chip>
      </template>

      <template slot="item" slot-scope="data">
        <template v-if="'surname' in data.item">
          <v-list-item-avatar>
            <img v-if="data.item.img" :src="photo(data.item.img)" />
          </v-list-item-avatar>

          <v-list-item-content>
            <v-list-item-title>
              {{ data.item.name }}
              {{ data.item.surname }}
            </v-list-item-title>
            <v-list-item-subtitle>
              <span v-for="(position, key) in data.item.positions" :key="'position-' + key">
                {{ position.label }}
                <span v-if="key != data.item.positions.length - 1">|</span>
              </span>
              <!-- - {{data.item.division.abbreviation}} -->
            </v-list-item-subtitle>
          </v-list-item-content>
        </template>
        <v-list-item-content v-else @click.stop="handleDivisionSelection(data.item)">
          <v-list-item-title>{{ data.item.name }}</v-list-item-title>
        </v-list-item-content>
      </template>
    </v-autocomplete>
    <input type="hidden" :name="name" :value="JSON.stringify(selectedUsers)" />
  </div>
</template>

<script>
export default {
  props: {
    users: {
      required: false,
      type: Array
    },
    divisions: {
      required: false
    },
    label: String,
    icon: String,
    name: String,
    hint: String,
    value: null,
    selectedUserEventName: {
      required: false,
      default: "userSelected",
      type: String
    },
    removeable: {
      required: false,
      default: true
    }
  },
  data() {
    return {
      selectedUsers: this.value || [],
      searchText: null
    };
  },
  methods: {
    handleDivisionSelection(el) {
      // this.selectedUsers.pop();
      this.divisions.forEach(division => {
        if (division.id == el.id) {
          division.users.forEach(user => {
            if (!this.selectedUsers.includes(user.id)) {
              this.selectedUsers.push(user.id);
            }
          });
        }
      });
    },
    remove(user) {
      if (this.$attrs.multiple) {
        this.selectedUsers = this.selectedUsers.filter(userOrUserID => {
          if (this.$attrs.returnObject || this.$attrs["return-object"]) {
            return userOrUserID.id !== user.id;
          } else {
            return userOrUserID !== user.id;
          }
        });
      } else {
        this.selectedUsers = null;
      }
    }
  },
  watch: {
    selectedUsers(selectedUsersId) {
      Event.fire(this.selectedUserEventName, selectedUsersId);
      this.$emit("input", selectedUsersId);
    },
    value(val) {
      this.selectedUsers = val;
    }
  },
  computed: {
    preparedDivisions() {
      let divisionWithUsers = [];
      this.divisions.forEach(division => {
        division.id = division.id + "d";
        divisionWithUsers.push(division);
        if (division.users.length) {
          division.users.forEach(user => {
            divisionWithUsers.push(user);
          });
        }
      });
      return divisionWithUsers;
    }
  }
};
</script>