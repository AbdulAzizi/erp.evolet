<template>
    <v-container fluid class="py-0">
        <v-row style="height:calc(100vh - 230px); overflow:auto;" ref="commentsWrapper">
            <v-container fluid>
                <v-row
                    v-for="(comment,index) in localCommentable.comments"
                    :key="'comment-'+index"
                    :justify="(( comment.user.id === auth.id ) ? 'end' : 'start')"
                >
                    <avatar
                        :user="comment.user"
                        size="30"
                        :class="' ml-2 mt-2'"
                        v-if="comment.user.id != auth.id"
                    />

                    <v-card flat :class="'mx-2 mt-2'" max-width="60%">
                        <v-card-text class="pa-2">{{comment.body}}</v-card-text>
                    </v-card>

                    <avatar
                        :user="comment.user"
                        size="30"
                        :class="' mr-2 mt-2'"
                        v-if="comment.user.id === auth.id"
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
        Echo.channel("chats."+this.commentable.id).listen("NewComment", event => {
            this.localCommentable.comments.push(event.comment);
            this.scrollToBotom();
        });
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
            this.localCommentable = this.commentable;
            this.scrollToBotom();
        }
    }
};
</script>