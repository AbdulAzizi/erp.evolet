<template>
    <div
        style="position: absolute;
				top: 60px;
				right: 13px;
				align-self: center;
				z-index: 6;"
    >
        <alert 
        v-for="(alert,index) in alerts" 
        :key="'alert-'+index" 
        :message="alert"
        ></alert>
    </div>
</template>
<script>
export default {
    data() {
        return {
			alerts: window.Laravel.alerts,
        };
    },
    created() {
        // Event.listen("notify", ([message, color]) => {
        //     if (color) this.snackbar.color = color;
        //     this.snackbar.message = message;
        //     this.snackbar.show = true;
        // });

        // if (window.Laravel.alerts) {
        //     this.snackbar.message = window.Laravel.alerts;
        //     this.snackbar.show = true;
        // }
    },
};
</script>
<style>
</style>