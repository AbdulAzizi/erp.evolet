<template>
    <v-container fluid class="py-0 grey lighten-3" >
        <v-row style="height:calc(100vh - 230px); overflow:auto;" ref="commentsWrapper">
            <v-container fluid>
                <v-row
                    v-for="(comment,index) in localCommentable.comments"
                    :key="'comment-'+index"
                    :justify="(( comment.sender.id === auth.id ) ? 'end' : 'start')"
                >
                    <avatar
                        :user="comment.sender"
                        size="30"
                        :class="' ml-2 mt-2'"
                        v-if="comment.sender.id != auth.id"
                    />

                    <v-card flat :class="'mx-2 mt-2'" max-width="60%">
                        <v-card-text class="pa-2">{{comment.body}}</v-card-text>
                    </v-card>

                    <avatar
                        :user="comment.sender"
                        size="30"
                        :class="' mr-2 mt-2'"
                        v-if="comment.sender.id === auth.id"
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
                        @click:append="body ? storeComment(localCommentable) : '' "
                        @keyup.enter="body ? storeComment(localCommentable) : '' "
                    ></v-text-field>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>
<script>
export default {
    props: {
        commentable: {
            required: true
        },
        type: {
            required: true
        }
    },
    data() {
        return {
            body: "",
            localCommentable: this.commentable
        };
    },
    created() {
        // console.log('------------------');
        
        // console.log(this.commentable);
        // console.log(this.commentable.type);
        
        switch (this.type) {
            case 'App\\User':
                Echo.channel(`newComment.Users.${this.auth.id}.${this.commentable.id}`).listen("NewComment", event => {
                    // console.log(event.comment);
                    this.localCommentable.comments.push(event.comment);
                    this.scrollToBotom();
                });

                Echo.channel(`newComment.Users.${this.commentable.id}.${this.auth.id}`).listen("NewComment", event => {
                    // console.log(event.comment);
                    this.localCommentable.comments.push(event.comment);
                    this.scrollToBotom();
                });

                break;
        
            default:

                Echo.channel(`newComment.Chats.${this.commentable.id}`).listen("NewComment", event => {
                    // console.log(event.comment);
                    this.localCommentable.comments.push(event.comment);
                    this.scrollToBotom();
                });

                break;
        }
        
    },
    methods: {
        storeComment(commentable) {
            let self = this;
            axios
                .post(this.appPath("api/comments"), {
                    body: self.body,
                    commentable_id: commentable.id,
                    commentable_type: self.type
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
                this.$refs.commentsWrapper.scrollTop =
                    this.$refs.commentsWrapper.scrollHeight;
            });
        }
    },
    watch: {
        commentable(val) {
            console.log("commantable");
            console.log(this.commentable);
            
            this.localCommentable = this.commentable;
            this.scrollToBotom();
        }
    }
};
</script>