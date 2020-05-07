<template>
    <v-row>
        <v-container>
            <menu-teaser
                    v-for="day in listOfDays"
                    v-bind:key="day.indexWeekDay"
                    :dateObj="day"
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
            ...mapGetters('menu', {menus: 'getMenus'}),
        }
    }
    //TODO Прееписать получение данных по всем менюшкам в этот компонент
    //TODO сразу инициализировать всю неделю, а на стороне сервера сделать проверку - если даты нет то добавить тогда пустое меню
    //TODO можно тогда в цикле где вставляются тизеры сразу прокидывать ID меню, тогда в них можно будет поставить геттер который будет брать из стора уже готовое меню
</script>

<style scoped>

</style>
