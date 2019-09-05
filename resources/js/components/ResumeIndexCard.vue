<template>
     <v-card hover :href="`/resume/${resume.id}`">
          <v-list dense>
            <v-list-item>
              <v-list-item-icon>
                <v-icon color="primary">mdi-account</v-icon>
              </v-list-item-icon>
              <v-list-item-content>
                <span class="grey--text text--darken-2">{{resume.name}} {{resume.surname}}</span>
              </v-list-item-content>
            </v-list-item>
            <v-list-item>
              <v-list-item-icon>
                <v-icon color="primary">mdi-calendar-range</v-icon>
              </v-list-item-icon>
              <v-list-item-content>
                <span class="grey--text text--darken-2">{{resume.birthday}}</span>
              </v-list-item-content>
            </v-list-item>
            <v-list-item>
              <v-list-item-icon>
                <v-icon color="primary">mdi-school</v-icon>
              </v-list-item-icon>
              <v-list-item-content>
                <span
                  class="grey--text text--darken-2"
                >{{resume.educations[0] ? resume.educations[0].name + ' &#183; ' + resume.educations[0].specialty : 'Нет данных'}}</span>
              </v-list-item-content>
            </v-list-item>
            <v-list-item>
              <v-list-item-icon>
                <v-icon color="primary">mdi-chat</v-icon>
              </v-list-item-icon>
              <v-list-item-content>
                <span
                  class="grey--text text--darken-2"
                >{{resume.languages[0] ? resume.languages[0].name + ' &#183; ' + resume.languages[0].level : 'Нет данных'}}</span>
              </v-list-item-content>
            </v-list-item>
          </v-list>
        </v-card>
</template>

<script>
export default {
    props: ['resume']
}
</script>