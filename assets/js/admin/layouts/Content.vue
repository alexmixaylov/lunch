<template>
    <v-content>
        <router-view></router-view>
    </v-content>
</template>

<script>
    export default {
        name: "Content",
        data: () => ({
            drawer: null,
        }),
    }
</script>

<style scoped>

</style>
