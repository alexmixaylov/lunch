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
                    <dish-teaser
                            v-for="(dishe, index) in dishes"
                            v-bind:key="index"
                            :dish="dishe"
                            :index="index"
                            @edit-dish="editDish(index)"
                    ></dish-teaser>
                </v-container>
                <v-card-actions>
                    <v-btn @click="goBack()" color="teal darken-2">
                        <v-icon>fa-angle-left</v-icon> &nbsp;
                        <span> Назад</span></v-btn>
                    <v-btn @click="addDish()" color="green accent-4">Новое Блюдо &nbsp; <v-icon>fa-plus</v-icon> </v-btn>
                </v-card-actions>
            </v-card>
        </v-row>
        <template v-if="dishForEditing">
            <dish-edit
                    :menu_id="menu_id"
                    :dish="dishForEditing"
                    :index="dishIndex"
                    @delete-dish="deleteDish(dishIndex)"
                    @cancel-editing="cancelEditing(dishIndex)"
                    @reset-dish-index="resetDishIndex"
            ></dish-edit>
        </template>
    </v-container>
</template>
<script>
    import DishTeaser from "../dish/DishTeaser";
    import {mapGetters} from 'vuex';
    import {dateFormat} from "../../plugins/dateFormat";
    import router from "../../routes";
    import DishEdit from "../dish/DishEdit";

    export default {
        name: "Menu",
        components: {
            DishTeaser,
            DishEdit
        },
        data: function () {
            return {
                status: false,
                menu_id: false,
                dishIndex: null
            }
        },
        computed: {
            ...mapGetters('menu', {menu: 'getMenu'}),
            dishes() {
                return this.menu.dishes ?? [];
            },
            dishForEditing() {
                return this.dishIndex !== null ? this.dishes[this.dishIndex] : null
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
                this.$store.dispatch('menu/addEmptyDishToMenu')
                    .then(response => {
                        this.dishIndex = response
                    })

            },
            deleteDish(index) {
                console.log(index)
                this.dishes.splice(index, 1)
                this.dishIndex = null
            },
            editDish(index) {
                console.log(index)
                console.log('edit work in MENU PARENT COMPONENT')
                this.dishIndex = index
            },
            cancelEditing(response) {
                this.dishIndex = null
                if (response.remove) {
                    this.dishes.splice(response.index, 1)
                }
            },
            resetDishIndex() {
                this.dishIndex = null
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
            next(vm => {
                let params = to.params;
                if (!params.id) {
                    // TODO эта проверка нужна чтобы гарантировать наличие ID меню, если человек осуществляет навигацию, то у него будет это значение по умолчанию
                    // TODO возможно есть смысл сделать запрос в базу и получить ID меню по дате, которая всегда  точно известна
                    // TODO можно сделать короткий запрос и в случае неуспеха сделать редирект
                    vm.$store.dispatch('menu/getMenuIdByDate', params.date).then(response => {
                        params.id = response
                    })
                        .catch(error => {
                            console.log(error)
                        })
                }
                console.log(params)
                if (!params.hasOwnProperty('id')) {
                    // router.push('/week/');
                }

                vm.$store.dispatch('menu/loadDishesIntoMenuByDate', {date: params.date, menu_id: params.id});
                vm.menu_id = params.id
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
