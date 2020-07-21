<template>
  <v-simple-table v-if="entries.length">
    <template v-slot:default>
      <thead>
        <tr>
          <th class="text-left">User</th>
          <th class="text-left">Date</th>
          <th class="text-left">Sign In</th>
          <th class="text-left">Sign Out</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(entry,index) in entries" :key="index">
          <td>{{ entry.user ? (entry.user.name + ' ' + entry.user.surname) : 'Unknown'}}</td>
          <td>{{ entry.date }}</td>
          <td>{{ entry.sign_in }}</td>
          <td>{{ entry.sign_out }}</td>
        </tr>
      </tbody>
    </template>
  </v-simple-table>
</template>
<script>
export default {
  props: {
    entries: {
      required: true
    }
  }
};
</script>