<template>
  <v-simple-table v-if="localUsers.length">
    <template v-slot:default>
      <thead>
        <tr>
          <th class="text-left">Сотрудник</th>
          <th class="text-left">Присут.</th>
          <th class="text-left">Присут %.</th>
          <th class="text-left">Опозд.</th>
          <th class="text-left">Овертайм</th>
          <th class="text-left">Ушел раньше</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(user,index) in localUsers" :key="index">
          <td>{{ user.name }}</td>
          <td>{{ msToTime(user.totalPresent) }}</td>
          <td>{{Number.isNaN(user.presentPercentage) ? 0 : user.presentPercentage}}%</td>
          <td>{{ msToTime(user.totalLate) }}</td>
          <td>{{ msToTime(user.totalOvertime) }}</td>
          <td>{{ msToTime(user.totalEarly) }}</td>
        </tr>
      </tbody>
    </template>
  </v-simple-table>
</template>
<script>
export default {
  props: {
    users: {
      required: true,
    },
  },
  data() {
    return {
      localUsers: [],
      workDays: 0,
      hoursPerDay: 0,
      timeToLeave: null,
    };
  },
  created() {
    this.prepareEntries();
  },
  methods: {
    prepareEntries() {
      this.users.forEach((u) => {
        this.workDays = 0;

        let user = {
          name: u.name + " " + u.surname,
          totalPresent: this.moment.duration(),
          presentPercentage: 0,
          totalLate: this.moment.duration(),
          totalOvertime: this.moment.duration(),
          totalEarly: this.moment.duration(),
        };

        u.entries.forEach((e) => {
          if (u.position_level_id == 1) {
            this.hoursPerDay = 11;
            this.timeToLeave = "19:30:00";
          } else {
            this.hoursPerDay = 9;
            this.timeToLeave = "17:30:00";
          }

          if (e.sign_in && e.sign_out) {
            user.totalPresent.add(
              this.moment(e.sign_out, "HH:mm").diff(
                this.moment(e.sign_in, "HH:mm")
              )
            );
          }

          if (e.sign_in > "08:30") {
            user.totalLate.add(
              this.moment(e.sign_in, "HH:mm").diff(
                this.moment("08:30", "HH:mm")
              )
            );
          }

          let present = this.moment(
            this.moment(e.sign_out, "HH:mm").diff(
              this.moment(e.sign_in, "HH:mm")
            )
          );

          if (
            present.format("HH:mm:ss") >
            this.moment(this.hoursPerDay, "H").format("HH:mm:ss")
          ) {
            user.totalOvertime.add(
              present.subtract(this.hoursPerDay, "hours").format("HH:mm:ss")
            );
          }

          if (this.timeToLeave > e.sign_out) {
            user.totalEarly.add(
              this.moment(this.timeToLeave, "HH:mm:ss").diff(
                this.moment(e.sign_out, "HH:mm:ss")
              )
            );
          }

          if (
            this.moment(e.date, "YYYY:MM:DD").day() != 6 &&
            this.moment(e.date, "YYYY:MM:DD").day() != 0
          ) {
            this.workDays++;
          }
        });
        user.presentPercentage = Math.round(
          (this.moment.duration(user.totalPresent).asHours() * 100) /
            (this.workDays * this.hoursPerDay)
        );

        this.localUsers.push(user);
      });
    },
    msToTime(duration) {
      return (
        Math.floor(duration.asHours()) +
        ":" +
        duration.minutes() +
        ":" +
        duration.seconds()
      );
    },
  },
};
</script>