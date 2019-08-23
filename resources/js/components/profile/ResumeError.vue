<template>
    <div>
        <profile-banner :user="user"></profile-banner>
        <v-alert
            border="bottom"
            colored-border
            type="info"
            elevation="2"
            >
            На данный момент у вас нету резюме. Вы можете авторизоваться и создать резюме!
        </v-alert>
    </div>
</template>
<script>
export default {
    props: ['user']
}
</script>
