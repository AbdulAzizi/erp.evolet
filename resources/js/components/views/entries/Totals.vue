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
      <v-simple-table v-if="preparedUsers.length">
        <template v-slot:default>
          <thead>
            <tr>
              <th class="text-left">Сотрудник</th>
              <th class="text-left">Рабочие дни в месяц</th>
              <th class="text-left">Рабочие часы в день</th>
              <th class="text-left">Рабочие часы в месяц</th>
              <th class="text-left">Присутствовал месяц часы</th>
              <th class="text-left">Опоздал на работу</th>
              <th class="text-left">Ушел с работы пораньше</th>
              <th class="text-left">Присутствовал на работе</th>
              <th class="text-left">Не отработанные дни</th>
              <th class="text-left">Не отработанные часы</th>
              <th class="text-left">Овертайм</th>
              <th class="text-left">Количество опозданий в месяц</th>
              <th class="text-left">Присутствовал в %.</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(user,index) in preparedUsers" :key="index">
              <td>{{ user.name }}</td>
              <td>{{ user.workDays }}</td>
              <td>{{ user.hoursPerDay+':00:00' }}</td>
              <td>{{ (user.workDays * user.hoursPerDay)+':00:00' }}</td>
              <td>{{ msToTime(user.totalPresent) }}</td>
              <td>{{ msToTime(user.totalLate) }}</td>
              <td>{{ msToTime(user.totalEarly) }}</td>
              <td>{{ user.presentDays }}</td>
              <td>{{ user.workDays - user.presentDays}}</td>
              <td>{{ ((user.workDays - user.presentDays) * user.hoursPerDay)+':00:00' }}</td>
              <td>{{ msToTime(user.totalOvertime) }}</td>
              <td>{{ user.totalLateDays }}</td>
              <td>{{Number.isNaN(user.presentPercentage) ? 0 : user.presentPercentage}}%</td>
            </tr>
          </tbody>
        </template>
      </v-simple-table>
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
      localUsers:[...this.users],
      month: null,
      monthMenu: null,
    };
  },
  created() {
    this.prepareEntries();
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