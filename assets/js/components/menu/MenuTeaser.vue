<template>
    <v-card class="mxauto mt-10" width="90%">

        <v-app-bar
                dark
                color="#385F73"
        >
            <v-toolbar-title>{{dateObj.stringDate}} {{dateObj.month}}</v-toolbar-title>

            <v-spacer></v-spacer>
            <router-link
                    v-if="menuId"
                    :to="{name: 'menu',params: {id: menuId}}"
                    tag="button">
                <v-icon>fa-edit</v-icon>
            </router-link>
            <v-btn
                    v-else
                    fab small outlined
                    :loading="loading"
                    :disabled="loading"
                    @click="addEmptyMenuToDatabase()"
            >
                <v-icon>fa-plus</v-icon>
            </v-btn>

        </v-app-bar>

        <v-container v-if="menuId"></v-container>
    </v-card>
</template>

<script>
    import axios from 'axios';
    import {mapGetters, mapState} from 'vuex';

    export default {
        name: "MenuTeaser",
        props: ['menuId', 'date', 'dishes'],
        data: function () {
            return {
                menuId: false,
                dishes: [],
                loader: null,
                loading: false,
            }
        },
        computed: {
            menu() {
                // return this.$store.getters["menu/getMenus"];
            },
        },
        methods: {
            addEmptyMenuToDatabase() {
                this.loader = 'loading';
                // this.$store.dispatch('menu/addEmptyMenu', this.dateObj.rawDate).then(console.log(this))
            }
        },
        mounted() {
            // this.$store.dispatch('menu/loadDishesIntoMenuByDate', this.dateObj.rawDate);
            // this.$store.dispatch('menu/loadDishesIntoMenuByDate', this.dateObj.rawDate);
        },
        watch: {
            loader() {
                const l = this.loader
                this[l] = !this[l]

                setTimeout(() => (this[l] = false), 3000)
                // this.menuId = 1
                this.loader = null
            },
            menu(){
                console.log('menu was updayed')
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
