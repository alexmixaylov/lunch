<template>
    <v-row>
        <v-container>
<!--            <menu-item-->
<!--                    v-for="day in listOfDays"-->
<!--                    v-bind:key="day.indexWeekDay"-->
<!--                    :day="day.stringDate"-->
<!--                    :month="day.month"-->
<!--            ></menu-item>-->
            <menu-teaser
                    v-for="day in listOfDays"
                    v-bind:key="day.indexWeekDay"
                    :date="day"
            ></menu-teaser>
        </v-container>
    </v-row>
</template>

<script>
    import Menu from "./Menu";
    import MenuTeaser from "./MenuTeaser";
    import {mapGetters} from 'vuex';


    export default {
        name: "MenuList",
        props: ['listOfDays'],
        components: {
            'menu-item': Menu,
            MenuTeaser
        },
        data: function () {
            return {}
        },
        computed: {
            ...mapGetters('dish', {dishes: 'getDishes'}),
        }
    }
</script>

<style scoped>

</style>
