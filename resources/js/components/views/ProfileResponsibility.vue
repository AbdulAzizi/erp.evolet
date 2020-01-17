<template>
    <div>
        <profile-banner :user="user" />
        <responsibilities :responsibilities="responsibilities" />
    </div>
</template>

<script>
export default {
    props: {
        user: {},
        responsibilities: {}
    }
};
</script>