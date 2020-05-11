<template>
    <v-row align="center">
        <v-col class="grow headline">Форма добавления</v-col>

        <router-link :to="{name: 'order'}">Отменить</router-link>
    </v-row>
</template>

<script>
    export default {
        name: "Create"
    }
</script>

<style scoped>

</style>
