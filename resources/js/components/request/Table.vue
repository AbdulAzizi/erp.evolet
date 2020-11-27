<template>
  <div class="my-3">
    <v-simple-table dense>
      <template v-slot:default>
        <thead>
          <tr>
            <th v-for="(header, i) in headers" :key="i">{{header}}</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="(request, i) in requests"
            :key="i"
            @click="displayRequest(request)"
            style="cursor: pointer"
          >
            <td>{{request.type}}</td>
            <td>
              <div v-for="(parameter, j) in request.parameters" :key="`p-${j}`">{{parameter.value}}</div>
            </td>
            <td>
              <div class="py-1">
                <v-avatar size="30">
                  <img :src="thumb(request.user.img) " alt="avatar" />
                </v-avatar>
                <span class="pl-1">{{ request.user.name }} {{ request.user.surname }}</span>
              </div>
            </td>
            <td>
              <p style="max-width: 150px" class="text-truncate mb-0">{{request.description}}</p>
            </td>
            <td>
              <v-chip
                small
                dark
                label
                class="font-weight-bold"
                :color="statuses[request.status].color"
              >{{statuses[request.status].text}}</v-chip>
            </td>
            <td>
              <update-request-btn v-if="auth.id === request.user_id" :request="request" />
              <delete-request-btn v-if="auth.id === request.user_id" :requestId="request.id" />
              <dismiss-request-btn :request="request" />
              <accept-request-btn :request="request" />
              <print-request-btn :request="request" />
            </td>
          </tr>
        </tbody>
      </template>
    </v-simple-table>
    <v-dialog width="500" v-model="dialog">
      <requests-card v-if="dialog" :request="currentRequest" :displayInDialog="true" />
    </v-dialog>
  </div>
</template>
<script>
export default {
  props: {
    requests: {
      required: true
    }
  },
  data() {
    return {
      localRequests: this.requests,
      currentRequest: null,
      dialog: false,
      statuses: [
        {
          text: "На рассмотрении",
          color: "orange lighten-1"
        },
        {
          text: "Рассмотрено",
          color: "orange lighten-1"
        },
        {
          text: "Одобрено",
          color: "green lighten-1"
        },
        {
          text: "Отклонено",
          color: "red lighten-1"
        }
      ],
      headers: [
        "Тип",
        "Значения",
        "Сотрудник",
        "Описание",
        "Статус",
        "Действия"
      ]
    };
  },
  methods: {
    displayRequest(request) {
      this.dialog = true;
      this.currentRequest = request;
    }
  }
};
</script>