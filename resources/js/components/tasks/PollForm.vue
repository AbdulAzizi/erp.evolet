<template>
  <v-card class="poll-form" flat>
    <v-toolbar flat dense color="primary" dark>
      <v-toolbar-title>
        {{this.localQuestionTask.question.body}}
      </v-toolbar-title>
        <v-spacer></v-spacer>
        <v-toolbar-title v-if="localQuestionTask.answers.length">Голосов: {{localQuestionTask.answers.length}}</v-toolbar-title>
    </v-toolbar>

    <v-card-text class="pt-0 pb-1">
      <v-row>
        <v-radio-group v-model="selectedOption" style="width:100% !important;" hide-details>
          <template v-for="(option, index) in this.localQuestionTask.question.options">
            <v-col cols="12 py-0" :key="'progress-text-'+index">
              <v-radio :value="option.id" color="primary">
                <template v-slot:label>
                  <v-row style="width:100% !important;">
                    <v-col cols="12 pt-0 pb-1">{{option.body}}</v-col>
                    <v-col cols="12 py-0" style="height:100%;">
                      <v-menu offset-y open-on-hover>
                        <template v-slot:activator="{ on:menu }">
                          <v-progress-linear
                            v-on="{ ...menu }"
                            rounded
                            height="12"
                            :value="Math.round(( 100 / localQuestionTask.answers.length ) * getAnswersFor(option.id).length )"
                          >
                            <template v-slot="{ value }">
                              <span
                                class="grey--text text--darken-2 caption"
                                style="font-size:10px !important;"
                              >{{ value }}%</span>
                            </template>
                          </v-progress-linear>
                        </template>
                        <v-card max-width="auto" v-if="getAnswersFor(option.id).length">
                          <v-card-text>
                            <avatars-set :items="getUsersFor(option.id)" />
                          </v-card-text>
                        </v-card>
                      </v-menu>
                    </v-col>
                  </v-row>
                </template>
              </v-radio>
            </v-col>
          </template>
        </v-radio-group>
      </v-row>
    </v-card-text>
  </v-card>
</template>

<script>
export default {
  props: {
    questionTask: {
      required: true
    }
  },
  data() {
    return {
      localQuestionTask: this.questionTask,
      selectedOption: null
    };
  },
  created(){
    // Listen
    Echo.channel(
      `questionTasks.${this.questionTask.id}`
    ).listen("PollOptionChosenEvent", event => {
      this.localQuestionTask = event.questionTask;
    });
    
  },
  watch: {
    selectedOption(selectedOption) {
      let self = this;
      axios
        .post(this.appPath("api/polls"), {
          questionTask: this.localQuestionTask,
          selected_option_id: selectedOption
        })
        .then(function(response) {
          console.log(response.data);
          
          self.localQuestionTask = response.data;
        })
        .catch(function(error) {
          console.log(error);
        });
    }
  },
  methods:{
    getAnswersFor(optionId){
      return this.localQuestionTask.answers.filter(answer => answer.option_id == optionId);
    },
    getUsersFor(optionId){
      let users = [];
      
      this.localQuestionTask.answers.forEach(answer => {
        if(answer.option_id == optionId)
          users.push(answer.user);
      });
      
      return users;
    }
  }
};
</script>
<style>
.poll-form .v-input__control {
  width: 100%;
}
.poll-form .v-label {
  width: 100%;
}
</style>