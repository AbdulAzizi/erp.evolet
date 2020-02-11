<template>
    <division-structure :division="division" :is-user-head="true"/>
</template>
<script>
export default {
    props: {
        division: {
            required: true
        }
    },
    created() {
        console.log(this.divisions);
    }
};
</script>
<style>
</style>