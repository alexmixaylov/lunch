<template>
    <v-row align="center">
        <v-col class="grow headline">Форма добавления</v-col>

        <router-link :to="{name: 'order'}">Отменить</router-link>
    </v-row>
</template>

<script>
    import {mapGetters} from 'vuex';

    export default {
        name: "Create",
        props: ['date-default'],
        computed: {
            ...mapGetters('menu', {menu: 'dfdfdf'})
        }
    }
</script>

<style scoped>

</style>
