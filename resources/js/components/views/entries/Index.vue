<template>
  <v-simple-table v-if="entries.length">
    <template v-slot:default>
      <thead>
        <tr>
          <th class="text-left"></th>
          <th class="text-left">Сотрудник</th>
          <th class="text-left">Дата</th>
          <th class="text-left">Вход</th>
          <th class="text-left">Выход</th>
          <th class="text-left">Присут.</th>
          <th class="text-left">Опозд.</th>
          <th class="text-left">Овертайм</th>
          <th class="text-left">Ушел ран.</th>
          <th class="text-left">Коментарии</th>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="(entry,index) in preparedEntries"
          :key="index"
          :class="rowBackgroundColor(entry)"
        >
          <td>{{ preparedEntries.length-1 != index ? (index+1) : '' }}</td>
          <td>{{ entry.user_name }}</td>
          <td>{{ entry.date }}</td>
          <td
            :class="entry.sign_in == 'нет данных' ? 'grey--text text--lighten-1' : '' "
          >{{ entry.sign_in }}</td>
          <td
            :class="entry.sign_out == 'нет данных' ? 'grey--text text--lighten-1' : '' "
          >{{ entry.sign_out }}</td>
          <td
            :class="entry.present == 'нет данных' ? 'grey--text text--lighten-1' : '' "
          >{{ entry.present }}</td>
          <td
            :class="((preparedEntries.length-1 != index) ? lateBackgroundColor(entry) : '') + (entry.late == 'нет данных' ? ' grey--text text--lighten-1 ' : '') "
          >{{ entry.late }}</td>
          <td
            :class="entry.overtime == 'нет данных' ? 'grey--text text--lighten-1' : '' "
          >{{ entry.overtime }}</td>
          <td
            :class="entry.early == 'нет данных' ? 'grey--text text--lighten-1' : '' "
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
        entry["user_name"] = e.user
          ? e.user.name + " " + e.user.surname
          : "нет данных";
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
          entry["sign_in"] = "нет данных";
          entry["late"] = "нет данных";
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
          entry["sign_out"] = "нет данных";
          entry["early"] = "нет данных";
        }
        if (e.sign_in && e.sign_out) {
          entry["present"] = this.moment(
            this.moment(e.sign_out, "HH:mm").diff(
              this.moment(e.sign_in, "HH:mm")
            )
          ).format("HH:mm:ss");
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
          entry["present"] = "нет данных";
          entry["overtime"] = "нет данных";
        }

        entry["comment"] = {
          model: e.comment,
          text: e.comment,
          editing: false,
        };

        if (entry["present"] != "нет данных") {
          totalPresent.add(this.moment.duration(entry["present"]));
        }
        if (entry["late"] != "нет данных") {
          totalLate.add(this.moment.duration(entry["late"]));
        }
        if (entry["overtime"] != "нет данных") {
          totalOvertime.add(this.moment.duration(entry["overtime"]));
        }
        if (entry["early"] != "нет данных") {
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
        user_name: "",
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
    lateBackgroundColor(entry) {
      if (
        entry.late != "00:00:00" &&
        entry.late != "нет данных" &&
        !entry.comment.text
      )
        return "red lighten-4";
    },
    rowBackgroundColor(entry) {
      if((entry.sign_in == 'нет данных' || entry.sign_out == 'нет данных') &&  !entry.comment.text){
        return "red lighten-4";
      }
      if (
        (this.moment(entry.date, "YYYY:MM:DD").day() == 6 &&
          entry.user.position_level_id != 1) ||
        this.moment(entry.date, "YYYY:MM:DD").day() == 0
      ) {
        return "grey lighten-4";
      }
    },
  },
};
</script>