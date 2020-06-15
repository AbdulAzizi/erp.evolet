<template>
  <v-container fluid class="py-0 grey lighten-3">
    <v-row style="height:calc(100vh - 230px); overflow:auto;" ref="messagesWrapper">
      <v-container fluid>
        <v-row
          v-for="(message,index) in localMessageable.messages"
          :key="'message-'+index"
          :justify="(( message.sender.id === auth.id ) ? 'end' : 'start')"
        >
          <avatar
            :user="message.sender"
            size="30"
            :class="' ml-2 mt-2'"
            v-if="message.sender.id != auth.id"
          />
          <v-card flat :class="'mx-2 mt-2'" max-width="60%">
            <v-card-text class="pa-2">
              <p class="ma-0">{{message.body}}</p>
              <p
                class="ma-0 text-lowercase grey--text float-right"
                style="font-size: 10px"
              >{{showDateOfMessage(message.created_at)}}</p>
            </v-card-text>
          </v-card>

          <avatar
            :user="message.sender"
            size="30"
            :class="' mr-2 mt-2'"
            v-if="message.sender.id === auth.id"
          />
        </v-row>
      </v-container>
    </v-row>
    <v-row>
      <v-col cols="12 pa-0" align-self="end">
        <v-card tile flat>
          <v-text-field
            v-model="body"
            hide-details
            append-icon="mdi-send"
            solo
            @click:append="body ? storeMessage(localMessageable) : '' "
            @keyup.enter="body ? storeMessage(localMessageable) : '' "
          ></v-text-field>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>
<script>
export default {
  props: {
    messageable: {
      required: true
    },
    type: {
      required: true
    }
  },
  data() {
    return {
      body: "",
      localMessageable: { ...this.messageable }
    };
  },
  created() {
    if (this.type == "App\\User") {
      Echo.channel(
        `User.${this.auth.id}.${this.messageable.id}.messages`
      ).listen("NewMessage", event => {
        this.localMessageable.messages.push(event.message);
      });

      Echo.channel(
        `User.${this.messageable.id}.${this.auth.id}.messages`
      ).listen("NewMessage", event => {
        this.localMessageable.messages.push(event.message);
      });
    } else {
      let result = this.type.split("\\");
      let model = result[1];

      Echo.channel(`${model}.${this.messageable.id}.messages`).listen(
        "NewMessage",
        event => {
          this.localMessageable.messages.push(event.message);
        }
      );
    }
  },
  methods: {
    storeMessage(messageable) {
      axios
        .post(this.appPath("api/messages"), {
          body: this.body,
          messageable_id: messageable.id,
          messageable_type: this.type
        })
        .then(response => {
          this.body = "";
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    scrollToBotom() {
      this.$nextTick(function() {
        this.$refs.messagesWrapper.scrollTop = this.$refs.messagesWrapper.scrollHeight;
      });
    },
    showDateOfMessage(date) {
      let currentYear = this.moment()
        .local()
        .format("Y");
      let currentDay = this.moment()
        .local()
        .format("DD");
      let messageDate = this.moment(date).local();

      if (messageDate.format("Y") !== currentYear) {
        return messageDate.format("hh:mm DD.M.YY");
      }
      if (messageDate.format("DD") !== currentDay) {
        return messageDate.format("DD MMM hh:mm");
      } else {
        return messageDate.format("hh:mm");
      }
    }
  },
  watch: {
    messageable(val) {
      this.localMessageable = { ...this.messageable };
      this.scrollToBotom();
    },
    messages(val) {
      this.scrollToBotom();
    }
  },
  computed: {
    messages() {
      return this.localMessageable.messages;
    }
  },
  beforeDestroy() {
    if (this.type == "App\\User") {

      Echo.channel(`User.${this.auth.id}.${this.messageable.id}.messages`)
        .stopListening("NewMessage");

      Echo.channel(`User.${this.messageable.id}.${this.auth.id}.messages`)
        .stopListening("NewMessage");
        
    } else {
      let result = this.type.split("\\");
      let model = result[1];

      Echo.channel(`${model}.${this.messageable.id}.messages`)
        .stopListening("NewMessage");
    }
    
  }
};
</script>