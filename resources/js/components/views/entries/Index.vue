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
          <th class="text-left">Коментарий</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(entry,index) in preparedEntries" :key="index">
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
          <td>{{ entry.late }}</td>
          <td>{{ entry.overtime }}</td>
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
    prepareEntries() {
      this.entries.forEach((e) => {
        let entry = { ...e };
        entry["user"] = e.user ? e.user.name + " " + e.user.surname : "Unknown";
        entry["date"] = e.date;
        entry["sign_in"] = e.sign_in ? e.sign_in : "Unknown";
        entry["sign_out"] = e.sign_out ? e.sign_out : "Unknown";
        // ----------------------------------------------
        if (e.sign_in && e.sign_out) {
          entry["present"] = this.moment(
            this.moment(entry.sign_out, "HH:mm").diff(
              this.moment(entry.sign_in, "HH:mm")
            )
          ).format("HH:mm:ss");
        } else {
          entry["present"] = "Unknown";
        }
        // ----------------------------------------------
        if (e.sign_in > "08:30") {
          entry["late"] = this.moment(
            this.moment(e.sign_in, "HH:mm").diff(this.moment("08:30", "HH:mm"))
          ).format("HH:mm:ss");
        } else {
          entry["late"] = "00:00:00";
        }
        // ----------------------------------------------
        if (entry["present"] != "Unknown") {
          entry["overtime"] = this.moment(
            this.moment(entry["present"], "HH:mm:ss") -
              this.moment("09:00:00", "HH:mm:ss")
          ).format("HH:mm:ss");
        } else {
          entry["overtime"] = "00:00:00";
        }
        entry["comment"] = {
          model: e.comment,
          text: e.comment,
          editing: false,
        };

        this.preparedEntries.push(entry);
      });
    },
  },
};
</script>