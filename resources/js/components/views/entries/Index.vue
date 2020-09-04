<template>
  <v-simple-table v-if="entries.length">
    <template v-slot:default>
      <thead>
        <tr>
          <th class="text-left">Сотрудник</th>
          <th class="text-left">Дата</th>
          <th class="text-left">Вход</th>
          <th class="text-left">Выход</th>
          <th class="text-left">Присут.</th>
          <th class="text-left">Опозд.</th>
          <th class="text-left">Овертайм</th>
          <th class="text-left">Ушел раньше</th>
          <th class="text-left">Коментарий</th>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="(entry,index) in preparedEntries"
          :key="index"
          :class="((moment(entry.date,'YYYY:MM:DD').day() == 6) || (moment(entry.date,'YYYY:MM:DD').day() == 0)) ? 'grey lighten-4' : '' "
        >
          <td>{{ entry.user }}</td>
          <td>{{ entry.date }}</td>
          <td
            :class="entry.sign_in == 'Unknown' ? 'grey--text text--lighten-1' : '' "
          >{{ entry.sign_in }}</td>
          <td
            :class="entry.sign_out == 'Unknown' ? 'grey--text text--lighten-1' : '' "
          >{{ entry.sign_out }}</td>
          <td
            :class="entry.present == 'Unknown' ? 'grey--text text--lighten-1' : '' "
          >{{ entry.present }}</td>
          <td :class="entry.late == 'Unknown' ? 'grey--text text--lighten-1' : '' ">{{ entry.late }}</td>
          <td
            :class="entry.overtime == 'Unknown' ? 'grey--text text--lighten-1' : '' "
          >{{ entry.overtime }}</td>
          <td
            :class="entry.early == 'Unknown' ? 'grey--text text--lighten-1' : '' "
          >{{ entry.early }}</td>
          <v-hover v-slot:default="{ hover }">
            <td style="position:relative;">
              <v-text-field
                dense
                single-line
                hide-details
                outlined
                v-model="entry.comment.model"
                v-if="entry.comment.editing"
              >
                <template v-slot:append>
                  <v-icon @click="close(entry)">mdi-close</v-icon>
                  <v-icon @click="save(entry)">mdi-check</v-icon>
                </template>
              </v-text-field>
              <div v-else>
                <span class="align-center">{{entry.comment.text}}</span>
                <v-btn v-if="hover" icon absolute style="top:5px;right:5px;" @click="select(entry)">
                  <v-icon size="20">mdi-pencil</v-icon>
                </v-btn>
              </div>
            </td>
          </v-hover>
        </tr>
      </tbody>
    </template>
  </v-simple-table>
</template>
<script>
export default {
  props: {
    entries: {
      required: true,
    },
  },
  data() {
    return {
      preparedEntries: [],
      workDays: 0,
      timeToLeave: null,
      hoursPerDay: null,
    };
  },
  created() {
    this.prepareEntries();
  },
  methods: {
    select(entry) {
      entry.comment.editing = true;
      entry.comment.model = entry.comment.text;
    },
    close(entry) {
      entry.comment.editing = false;
    },
    save(entry) {
      axios
        .put(this.appPath(`api/entries/${entry.id}`), {
          comment: entry.comment.model,
        })
        .then((resp) => {
          entry.comment.text = resp.data.comment;
          entry.comment.editing = false;
          Event.fire("notify", ["Сохранено успешно!"]);
        });
    },
    msToTime(duration) {
      var milliseconds = parseInt((duration % 1000) / 100),
        seconds = Math.floor((duration / 1000) % 60),
        minutes = Math.floor((duration / (1000 * 60)) % 60),
        hours = Math.floor(duration / (1000 * 60 * 60));

      hours = hours < 10 ? "0" + hours : hours;
      minutes = minutes < 10 ? "0" + minutes : minutes;
      seconds = seconds < 10 ? "0" + seconds : seconds;

      return hours + ":" + minutes;
    },
    prepareEntries() {
      let totalPresent = this.moment.duration("00:00:00");
      let totalLate = this.moment.duration("00:00:00");
      let totalOvertime = this.moment.duration("00:00:00");
      let totalEarly = this.moment.duration("00:00:00");

      this.entries.forEach((e) => {
        let entry = { ...e };
        if (e.user.position_level_id == 1) {
          this.hoursPerDay = 11;
          this.timeToLeave = "19:30:00";
        } else {
          this.hoursPerDay = 9;
          this.timeToLeave = "17:30:00";
        }
        entry["user"] = e.user ? e.user.name + " " + e.user.surname : "Unknown";
        entry["date"] = e.date;
        if (
          this.moment(e.date, "YYYY:MM:DD").day() != 6 &&
          this.moment(e.date, "YYYY:MM:DD").day() != 0
        ) {
          this.workDays++;
        }
        if (e.sign_in) {
          if (e.sign_in > "08:30") {
            entry["late"] = this.moment(
              this.moment(e.sign_in, "HH:mm").diff(
                this.moment("08:30", "HH:mm")
              )
            ).format("HH:mm:ss");
          } else {
            entry["late"] = "00:00:00";
          }
        } else {
          entry["sign_in"] = "Unknown";
          entry["late"] = "Unknown";
        }
        if (e.sign_out) {
          if (this.timeToLeave > e.sign_out) {
            entry["early"] = this.moment(
              this.moment(this.timeToLeave, "HH:mm:ss").diff(
                this.moment(entry.sign_out, "HH:mm:ss")
              )
            ).format("HH:mm:ss");
          } else {
            entry["early"] = "00:00:00";
          }
        } else {
          entry["sign_out"] = "Unknown";
          entry["early"] = "Unknown";
        }
        if (e.sign_in && e.sign_out) {
          entry["present"] = this.moment(
            this.moment(e.sign_out, "HH:mm").diff(
              this.moment(e.sign_in, "HH:mm")
            )
          ).format("HH:mm:ss");
          console.log(e.id + ' '+
            this.moment(e.sign_out, "HH:mm").diff(
              this.moment(e.sign_in, "HH:mm")
            )
          );
          // ----------------------------------------------

          if (
            entry["present"] >
            this.moment(this.hoursPerDay, "H").format("HH:mm:ss")
          ) {
            entry["overtime"] = this.moment(
              this.moment(entry["present"], "HH:mm:ss") -
                this.moment(this.hoursPerDay, "H")
            ).format("HH:mm:ss");
          } else {
            entry["overtime"] = "00:00:00";
          }
        } else {
          entry["present"] = "Unknown";
          entry["overtime"] = "Unknown";
        }

        entry["comment"] = {
          model: e.comment,
          text: e.comment,
          editing: false,
        };

        if (entry["present"] != "Unknown") {
          totalPresent.add(this.moment.duration(entry["present"]));
        }
        if (entry["late"] != "Unknown") {
          totalLate.add(this.moment.duration(entry["late"]));
        }
        if (entry["overtime"] != "Unknown") {
          totalOvertime.add(this.moment.duration(entry["overtime"]));
        }
        if (entry["early"] != "Unknown") {
          totalEarly.add(this.moment.duration(entry["early"]));
        }

        this.preparedEntries.push(entry);

        // console.log(totalPresent);
        // console.log(totalPresent.format("HH:mm:ss"));
        // console.log(totalLate.format("HH:mm:ss"));
        // console.log(totalOvertime.format("HH:mm:ss"));
        // console.log(totalEarly.format("HH:mm:ss"));
      });

      this.preparedEntries.push({
        user: "",
        date: "",
        sign_in: "",
        sign_out: "",
        present:
          this.msToTime(totalPresent) + "/" + this.hoursPerDay * this.workDays,
        late: this.msToTime(totalLate),
        overtime: this.msToTime(totalOvertime),
        early: this.msToTime(totalEarly),
        comment: "",
      });
    },
  },
};
</script>