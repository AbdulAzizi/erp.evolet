<template>
    <div>
        <profile-banner :user="user" />
        <positions :positions="positions" />
    </div>
</template>

<script>
export default {
    props: {
        user: {},
        positions: {}
    }
};
</script>