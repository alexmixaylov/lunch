<template>
    <v-card class="mxauto mt-10" width="90%">
        <v-app-bar
                dark
                :color="toolbarColor"
        >
            <v-toolbar-title>{{dateForUser}}</v-toolbar-title>

            <v-spacer></v-spacer>
            <router-link
                    :to="{name: 'menu',params: {id: menuId, date: date}}"
                    tag="button">
                <v-icon>fa-edit</v-icon>
            </router-link>
        </v-app-bar>
        <v-card-text class="body-1 text-between"
                     v-for="dish in dishes"
                     v-bind:key="dish.dish_id"
        >
            <span class="left">{{ dish.title }}</span><span class="right"><b>{{ dish.price}}</b>грн.</span>
        </v-card-text>
    </v-card>
</template>

<script>
    import MenuTeaser from "./MenuTeaser";
    import {mapGetters} from 'vuex';
    import {dateFormat} from "../../../plugins/dateFormat";

    export default {
        name: "MenuList",
        props: ['menuId', 'date', 'dishes'],
        components: {
            MenuTeaser
        },
        data: function () {
            return {}
        },
        computed: {
            dateForUser() {
                return dateFormat(this.date, 'ddd, d mmmm');
            },
            toolbarColor() {
                const count = Object.keys(this.dishes).length;
                switch (count) {
                    case 0: {
                        return 'red darken-3';
                    }
                    case 1:
                    case 2:
                    case 3: {
                        return 'teal darken-' + (count - 1)
                    }
                    default: {
                        return 'green darken-2'
                    }
                }
            },
            test() {
                return Object.keys(this.dishes)
            },
        },
        beforeRouteLeave(to, from, next) {
            const answer = window.confirm('Вы хотите уйти? У вас есть несохранённые изменения!')
            if (answer) {
                next()
            } else {
                next(false)
            }
        }
    }
</script>

<style scoped>
    .text-between {
        display: flex;
        justify-content: space-between;
        border-bottom: 1px solid gray;

    }

    .text-between:last-child {
        border-bottom: none;
    }
</style>
