<template>
    <v-container fluid class="py-0 grey lighten-3" >
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
                        <v-card-text class="pa-2">{{message.body}}</v-card-text>
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
            localMessageable: this.messageable
        };
    },
    created() {
        // console.log('------------------');
        
        // console.log(this.messageable);
        // console.log(this.messageable.type);
        
        switch (this.type) {
            case 'App\\User':
                Echo.channel(`newMessage.Users.${this.auth.id}.${this.messageable.id}`).listen("NewMessage", event => {
                    // console.log(event.message);
                    this.localMessageable.messages.push(event.message);
                    this.scrollToBotom();
                });

                Echo.channel(`newMessage.Users.${this.messageable.id}.${this.auth.id}`).listen("NewMessage", event => {
                    // console.log(event.message);
                    this.localMessageable.messages.push(event.message);
                    this.scrollToBotom();
                });

                break;
        
            default:

                Echo.channel(`newMessage.Chats.${this.messageable.id}`).listen("NewMessage", event => {
                    // console.log(event.message);
                    this.localMessageable.messages.push(event.message);
                    this.scrollToBotom();
                });

                break;
        }
        
    },
    methods: {
        storeMessage(messageable) {
            let self = this;
            axios
                .post(this.appPath("api/messages"), {
                    body: self.body,
                    messageable_id: messageable.id,
                    messageable_type: self.type
                })
                .then(function(response) {
                    console.log(response);
                    
                    self.body = "";
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        scrollToBotom() {
            this.$nextTick(function() {
                this.$refs.messagesWrapper.scrollTop =
                    this.$refs.messagesWrapper.scrollHeight;
            });
        }
    },
    watch: {
        messageable(val) {
            console.log("commantable");
            console.log(this.messageable);
            
            this.localMessageable = this.messageable;
            this.scrollToBotom();
        }
    }
};
</script>