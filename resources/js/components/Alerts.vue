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
			alerts: window.Laravel.alerts ? window.Laravel.alerts : [],
        };
    },
    created() {
        Event.listen("notify", ([message, color]) => {
            console.log(this.alerts);
            this.alerts.push(message);
        });
    },
};
</script>
<style>
</style>