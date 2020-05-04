<template>
    <v-card class="mxauto mt-10" width="90%">

        <v-app-bar
                dark
                color="#385F73"
        >
            <v-toolbar-title>{{date.stringDate}} {{date.month}}</v-toolbar-title>

            <v-spacer></v-spacer>
            <router-link :to="{name: 'menu',params: {id: 1}}" tag="button">
                <v-icon>fa-edit</v-icon>
            </router-link>

        </v-app-bar>

        <v-container></v-container>
    </v-card>
</template>

<script>
    export default {
        name: "MenuTeaser",
        props: ['date'],
        data: function () {
            return {
                teaser: 1
            }
        },
        computed: {
            inDatabase() {
                return false;
            }
        }
    }
</script>

<style scoped>

</style>


<!--дергаем меню по дате-->
<!--    если с такой датой меню нет-->
<!--        создаем пустой объект-->

<!--    если меню есть возвращаем заполненый-->
