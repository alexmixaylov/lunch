<template>
    <v-container>
        <v-row justify="center">
            <v-card class="mxauto mt-10" width="90%">
                <v-app-bar
                        dark
                        color="teal darken-1"
                >
                    <v-toolbar-title>{{ dateForUser }}</v-toolbar-title>

                    <v-spacer></v-spacer>
                    <router-link :to="{name: 'order'}" tag="button">
                        <v-btn text>
                            Добавить заказ &nbsp;<v-icon>fa-plus</v-icon>
                        </v-btn>
                    </router-link>

                </v-app-bar>

                <v-container>
                    <dish-component
                            v-for="(dishe, index) in dishes"
                            v-bind:key="index"
                            :dish="dishe"
                            :date="menu.date"
                            :menu_id="menu_id"
                            @delete-dish="deleteDish(index)"
                    ></dish-component>
                </v-container>
                <v-card-actions>
                    <v-btn text @click="goBack()" color="blue">
                        <v-icon>fa-angle-left</v-icon>
                        <span> Назад</span></v-btn>
                    <v-btn text @click="addDish()" color="light-green">Новое Блюдо</v-btn>
                </v-card-actions>
            </v-card>
        </v-row>
    </v-container>
</template>
<script>
    import Dish from "../dish/Dish";
    import {mapGetters} from 'vuex';
    import {dateFormat} from "../../plugins/dateFormat";
    import router from "../../routes";

    export default {
        name: "Menu",
        components: {
            'dish-component': Dish
        },
        data: function () {
            return {
                status: false,
                menu_id: false
            }
        },
        computed: {
            ...mapGetters('menu', {menu: 'getMenu'}),
            dishes() {
                return this.menu.dishes ?? [];
            },
            isDishes() {
                return this.dishes.length
            },
            dateForUser() {
                return dateFormat(this.menu.date, 'dddd, d mmmm');
            }
        },
        methods: {
            addDish() {
                console.log('ADD DISHE')
                this.$store.commit('menu/addEmptyDish', {price: null, title: null, weight: null, type: null})
            },
            deleteDish(index) {
                console.log(index)
                this.dishes.splice(index, 1)
            },
            goBack() {
                this.$router.go(-1)
            }
        },
        watch: {
            // menu(){
            //     console.log('UPDATED MENU')
            // }
        },
        beforeRouteEnter(to, from, next) {
            console.log('BEFORE ROUTER ENTER HAS WORKED')
            console.log(to.params.id)
            if (!to.params.id) {
                // TODO эта проверка нужна чтобы гарантировать наличие ID меню, если человек осуществляет навигацию, то у него будет это значение по умолчанию
                // TODO возможно есть смысл сделать запрос в базу и получить ID меню по дате, которая всегда  точно известна
                // TODO можно сделать короткий запрос и в случае неуспеха сделать редирект
                router.push('/week/')
            }
            next(vm => {
                vm.$store.dispatch('menu/loadMenuByDate', {date: to.params.date, menu_id: to.params.id});
                vm.menu_id = to.params.id
            })
        }
    }
</script>

<style scoped>
    .inputNumber input[type='number'] {
        -moz-appearance: textfield;
    }

    .inputNumber input::-webkit-outer-spin-button,
    .inputNumber input::-webkit-inner-spin-button {
        -webkit-appearance: none;
    }

    .inputNumber {
        display: inline-block;
        width: 40%;
    }
</style>
