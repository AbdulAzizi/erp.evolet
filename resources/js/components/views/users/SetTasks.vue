<template>
    <div>
        hello
    </div>
</template>
<script>
export default {
    props: {
        tasks: {
            required: false
        }
    },
    created(){
        console.log(this.tasks);
    }
}
</script>