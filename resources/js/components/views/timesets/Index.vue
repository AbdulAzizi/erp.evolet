<template>
    <v-card>
        <timeline :items="localItems" :groups="localUsers" :options="options"></timeline>
    </v-card>
</template>
<script>
export default {
    props: {
        timesets: {
            required: true
        },
        users: {
            required: true
        }
    },
    data() {
        return {
            localUsers: [],
            localItems: [],
            options: {
                configure: false,
                editable: false,
                stack: false,
                template: function(item, element, data) {
                    // console.log(item);
                    // console.log(element);
                    // console.log(data);
                    return `<span>${item.content}</span>`;
                },
                orientation: {
                    axis: "both"
                },
                zoomMax: 31557600000,
                zoomMin: 3600000,
                tooltip:{
                    followMouse:true
                }
            },
            colors: [
                "red",
                "pink",
                "purple",
                "deep-purple",
                "indigo",
                "blue",
                "light-blue",
                "cyan",
                "teal",
                "green",
                "light-green",
                "lime",
                "amber",
                "orange",
                "deep-orange",
                "blue-grey"
            ]
        };
    },
    created() {
        this.prepareData();
    },
    methods: {
        prepareData() {
            this.localUsers = this.users.map(user => {
                return {
                    id: user.id,
                    content: user.fullname,
                    color: this.colors[this.rand(0, this.colors.length - 1)],
                    content: '<div tabindex="0" role="listitem" aria-selected="false" class="px-2 v-list-item v-list-item--dense theme--light" style="min-height:30px;"><div class="v-avatar my-0 v-list-item__avatar" style="height: 27px; min-width: 27px; width: 27px;"><div class="v-responsive v-image"><div class="v-responsive__sizer" style="padding-bottom: 100%;"></div><div class="v-image__image v-image__image--cover" style="background-image: url(&quot;'+ this.photo(user.img) +'&quot;); background-position: center center;"></div><div class="v-responsive__content" style="width: 128px;"></div></div></div><div class="v-list-item__content py-0"><div class="v-list-item__title">'+ user.fullname + '</div></div></div>'
                };
            });

            this.localItems = this.timesets.map(timeset => {
                return {
                    id: timeset.id,
                    title: timeset.task.description,
                    content: timeset.task.description,
                    group: timeset.task.responsible_id,
                    start: this.moment(timeset.start_time),
                    end: this.moment(timeset.end_time),
                    className:
                        this.localUsers.filter(user => {
                            return user.id == timeset.task.responsible_id;
                        })[0].color +
                        " " +
                        "white--text caption"
                };
            });
        },
        rand(min, max) {
            return Math.floor(Math.random() * (max - min + 1)) + min;
        }
    }
};
</script>
<style>
.vis-item-content {
    padding: 0px 10px !important;
}
.vis-inner{
    padding: 0px !important;
}
</style>