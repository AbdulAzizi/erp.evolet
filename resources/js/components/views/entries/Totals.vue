<template>
  <v-container fluid class="pt-0">
    <v-row class="pb-3" justify="space-between" align="center">
      <v-menu
        ref="monthMenu"
        v-model="monthMenu"
        :close-on-content-click="false"
        transition="scale-transition"
        offset-y
        min-width="290px"
      >
        <template v-slot:activator="{ on, attrs }">
          <v-text-field
            v-model="month"
            label="Месяц"
            prepend-inner-icon="mdi-calendar"
            readonly
            solo
            single-line
            hide-details
            dense
            flat
            v-bind="attrs"
            v-on="on"
            style="max-width:290px;"
          ></v-text-field>
        </template>
        <v-date-picker type="month" v-model="month" @input="monthMenu = false" no-title scrollable />
      </v-menu>
      <views-entries-add />
    </v-row>
    <v-row>
      <v-data-table
        v-if="preparedUsers.length"
        :items="preparedUsers"
        :headers="headers"
        item-key="name"
        :items-per-page="-1"
        hide-default-footer
        fixed-header
        dense
      >
        <template v-slot:item="{ item }">
          <tr>
            <td>{{ item.name }}</td>
            <td>{{ item.workDays }}</td>
            <td>{{ item.hoursPerDay+':00:00' }}</td>
            <td>{{ item.hoursPerMonth +':00:00' }}</td>
            <td>{{ msToTime(item.totalPresent) }}</td>
            <td>{{ msToTime(item.totalLate) }}</td>
            <td>{{ msToTime(item.totalEarly) }}</td>
            <td>{{ item.presentDays }}</td>
            <td>{{ item.unworkedDays}}</td>
            <td>{{ item.unworkedHours+':00:00' }}</td>
            <td>{{ msToTime(item.totalOvertime) }}</td>
            <td>{{ item.totalLateDays }}</td>
            <td>{{Number.isNaN(item.presentPercentage) ? 0 : item.presentPercentage}}%</td>
          </tr>
        </template>
      </v-data-table>
    </v-row>
  </v-container>
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
      preparedUsers: [],
      localUsers: [...this.users],
      month: null,
      monthMenu: null,
      headers: [
        { text: "Сотрудник", value: "name" },
        { text: "Рабочие дни в месяц", value: "workDays" },
        { text: "Рабочие часы в день", value: "hoursPerDay" },
        { text: "Рабочие часы в месяц", value: "hoursPerMonth" },
        { text: "Присутствовал месяц часы", value: "totalPresent" },
        { text: "Опоздал на работу", value: "totalLate" },
        { text: "Ушел с работы пораньше", value: "totalEarly" },
        { text: "Присутствовал на работе", value: "presentDays" },
        { text: "Неотработанные дни", value: "unworkedDays" },
        { text: "Неотработанные часы", value: "unworkedHours" },
        { text: "Овертайм", value: "totalOvertime" },
        { text: "Количество опозданий в месяц", value: "totalLateDays" },
        { text: "Присутствовал в %.", value: "presentPercentage" },
      ],
    };
  },
  created() {
    this.prepareEntries();
    console.log(this.preparedUsers);
  },
  methods: {
    prepareEntries() {
      this.preparedUsers = [];
      this.localUsers.forEach((u) => {
        let user = {
          name: u.name + " " + u.surname,
          totalPresent: this.moment.duration(),
          presentPercentage: 0,
          totalLate: this.moment.duration(),
          totalOvertime: this.moment.duration(),
          totalEarly: this.moment.duration(),
          totalLateDays: 0,
          workDays: 0,
          hoursPerDay: 0,
          presentDays: 0,
          timeToLeave: null,
        };

        u.entries.forEach((e) => {
          if (u.position_level_id == 1) {
            user.hoursPerDay = 11;
            user.timeToLeave = "19:30:00";
          } else {
            user.hoursPerDay = 9;
            user.timeToLeave = "17:30:00";
          }

          if (e.sign_in && e.sign_out) {
            user.totalPresent.add(
              this.moment(e.sign_out, "HH:mm").diff(
                this.moment(e.sign_in, "HH:mm")
              )
            );
            user.presentDays++;
          }

          if (e.sign_in > "08:30") {
            user.totalLate.add(
              this.moment(e.sign_in, "HH:mm").diff(
                this.moment("08:30", "HH:mm")
              )
            );
            user.totalLateDays++;
          }

          let present = this.moment(
            this.moment(e.sign_out, "HH:mm").diff(
              this.moment(e.sign_in, "HH:mm")
            )
          );

          if (
            present.format("HH:mm:ss") >
            this.moment(user.hoursPerDay, "H").format("HH:mm:ss")
          ) {
            user.totalOvertime.add(
              present.subtract(user.hoursPerDay, "hours").format("HH:mm:ss")
            );
          }

          if (user.timeToLeave > e.sign_out) {
            user.totalEarly.add(
              this.moment(user.timeToLeave, "HH:mm:ss").diff(
                this.moment(e.sign_out, "HH:mm:ss")
              )
            );
          }

          if (
            (this.moment(e.date, "YYYY:MM:DD").day() != 0 &&
              u.position_level_id == 1) ||
            (u.position_level_id != 1 &&
              this.moment(e.date, "YYYY:MM:DD").day() != 6 &&
              this.moment(e.date, "YYYY:MM:DD").day() != 0)
          ) {
            user.workDays++;
          }
        });
        user.presentPercentage = Math.round(
          (this.moment.duration(user.totalPresent).asHours() * 100) /
            (user.workDays * user.hoursPerDay)
        );
        user.hoursPerMonth = user.workDays * user.hoursPerDay;
        user.unworkedDays = user.workDays - user.presentDays;
        user.unworkedHours = user.unworkedDays * user.hoursPerDay;

        this.preparedUsers.push(user);
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
  watch: {
    month(val) {
      axios
        .get(this.appPath("api/entries"), {
          params: {
            month: this.month,
          },
        })
        .then((resp) => {
          console.log(resp.data);
          this.localUsers = resp.data;
          this.prepareEntries();
        });
    },
  },
};
</script>