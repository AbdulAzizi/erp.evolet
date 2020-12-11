<template>
  <v-btn
    v-if="show"
    icon
    class="ml-2"
    color="green lighten-1"
    dark
    small
    depressed
    @click.stop="changeStatus()"
  >
    <v-icon>mdi-check-outline</v-icon>
  </v-btn>
</template>
<script>
export default {
  props: {
    request: {
      required: true
    }
  },
  data() {
    return {};
  },
  methods: {
    changeStatus() {
      axios
        .post(`/api/requests/${this.request.id}/changeStatus`, {
          status:
            this.role.headOfDivision && this.role.ceo
              ? 2
              : this.role.headOfDivision
              ? 1
              : 0
        })
        .then(res => Event.fire("requestApproved", {request: res.data, message: this.role.ceo ? "Заявка одобрена" : "Заявка рассмотрена"}))
        .catch(err => console.error(err.message));
    }
  },
  computed: {
    show() {
      return (
        (this.role.headOfDivision && this.request.status == 0) ||
        (this.role.ceo && this.request.status < 2)
      );
    },
    role() {
      const headOfDivision =
        this.auth.id === this.auth.division.head_id && !ceo;
      const ceo = this.auth.positions.some(
        el => el.name == "HR" || el.name == "РВЗ"
      );
      return {
        headOfDivision: headOfDivision,
        ceo: ceo
      };
    }
  }
};
</script>