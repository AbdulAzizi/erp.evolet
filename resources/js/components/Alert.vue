<template>
    <v-alert
        :value="value"
        :color="color"
        border="top"
        elevation="10"
        colored-border
    >{{ message}}</v-alert>
</template>

<script>
export default {
    props: {
        message: {
            required: true
        },
        color: {
            required: false,
            default: "primary"
        },
        timeout: {
            required: false,
            default: 3000
        }
    },
    data() {
        return {
            value: true
        };
    },
    created() {
		let self = this;
		setTimeout(function() {
			self.value = false;
			
		}, this.timeout);
			
        Event.listen("notify", ([message, color]) => {
            this.value = true;
            setTimeout(function() {
                this.value = false;
            }, this.timeout);
        });
    }
};
</script>