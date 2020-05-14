<template>
    <v-container justify="center">
        <v-row class="space-between">
            <v-col class="grow headline">Новый заказ
                <span v-if="totalSumm">{{totalSumm}}грн.</span>
                <span v-else>Поехали? :)</span>
            </v-col>
            <v-col class="shrink">
                <v-btn color="teal" large @click="calendar = true">{{dateForUser}} &nbsp;
                    <v-icon>fa-edit</v-icon>
                </v-btn>
            </v-col>
        </v-row>

        <v-row>
            <v-col cols="12" lg="6">
                <v-card class="mxauto mt-5 mb-5" width="100%">
                    <v-list-item>
                        <v-list-item-content>
                            <v-list-item-title class="subtitle-1">Меню на <span class="title">{{dateForUser}}</span>
                            </v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>


                    <v-list-item v-for="(dish, index) in menu.dishes" :key="index">
                        <v-list-item-content>
                            <v-col class="grow">{{ dish.title }} {{dish.price}}грн. {{ dish.dish_id }}</v-col>
                            <v-col class="shrink">
                                <v-icon color="orange" @click="minToOrdered(dish.dish_id)">fa-minus</v-icon>
                            </v-col>
                            <v-col class="shrink">
                                <v-icon color="orange" @click="addToOrdered(index)">fa-plus</v-icon>
                            </v-col>

                            <!--                        <v-spacer></v-spacer>-->

                        </v-list-item-content>
                    </v-list-item>


                </v-card>
            </v-col>

            <v-col cols="12" lg="6" v-if="orderTable">
                <header class="title">Ваш заказ</header>
                <v-data-table
                        v-if="orderTable"
                        disable-pagination
                        disable-sort
                        disable-filtering
                        hide-default-footer
                        :headers="headers"
                        :items="normalizeOrders"
                        class="elevation-1"
                        locale="ru"
                ></v-data-table>

                <v-row justify="space-between">
                    <v-col class="grow title text-right">Полная стоимость: {{totalSumm}} грн.</v-col>
                </v-row>
            </v-col>

        </v-row>

        <v-divider></v-divider>
        <v-row justify="space-between">
            <v-col class="grow title">Со скидой: {{totalSumm}} грн.</v-col>
            <v-col class="shrink">
                <v-btn color="orange" large @click="createOrder()">Заказать</v-btn>
            </v-col>
        </v-row>
        СО скидкой

        <v-dialog v-model="calendar" max-width="290">
            <v-date-picker
                    locale="ru"
                    first-day-of-week="1"
                    width="290"
                    color="teal"
                    v-model="date"
                    @change="changeDate()"
            ></v-date-picker>
        </v-dialog>

        <v-dialog v-model="orderSuccess">
            <v-card>
                <v-app-bar dark color="teal darken-1">
                    <v-toolbar-title>Приятного апетита!</v-toolbar-title>
                </v-app-bar>


                <v-container>
                    <v-card-text class="title">
                        Ваш заказ №{{orderID}} успешно оформлен
                    </v-card-text>
                </v-container>

                <v-card-actions>
                        <v-btn color="orange darken-2" @click="orderSuccess = false">Закрыть</v-btn>
                        <v-spacer></v-spacer>
                        <v-btn color="green darken-2">
                            <router-link tag="span" :to="{name: 'orders#read', params:{id: orderID}}">Посмотреть
                            </router-link>
                        </v-btn>
                </v-card-actions>
            </v-card>

        </v-dialog>
    </v-container>
</template>

<script>
    //TODO maybe we can use default date in Before Router hook  :date-default="dateForAPI"
    import {mapGetters} from 'vuex';
    import {dateFormat} from "../../plugins/dateFormat";

    export default {
        name: "OrderCreate",
        props: [],
        data() {
            return {
                date: new Date().toISOString().substr(0, 10),
                calendar: false,
                orderID: false,
                orderSuccess: false,
                orderedDishes: [],
                headers: [
                    {text: 'Название', value: 'title'},
                    {text: 'Цена', value: 'price'},
                    {text: 'Кол-во', value: 'cnt'},
                    {text: 'Сумма', value: 'summ'}
                ]
            }
        },
        computed: {
            ...mapGetters('menu', {menu: 'getMenu'}),
            dateForAPI() {
                return dateFormat(this.date, 'yyyy-mm-dd');
            },
            dateForUser() {
                return dateFormat(this.date, 'dddd, dd mmm')
            },
            normalizeOrders() {
                const orderItems = Object.values(this.orderedDishes);
                return orderItems.map(value => {
                    let item = value;
                    item.summ = item.cnt * item.price
                    // console.log(item)
                    return item
                })
            },
            orderTable() {
                return this.normalizeOrders.length > 0
            },
            totalSumm() {
                return this.normalizeOrders.reduce((acc, item) => {
                    return acc + item.summ
                }, 0);
            }
        },
        methods: {
            addToOrdered(index) {
                this.orderedDishes.push(this.menu.dishes[index])
            },
            minToOrdered(dishId) {
                let dishIndex = this.orderedDishes.findIndex((item) => item.dish_id === dishId);
                if (dishIndex !== -1) {
                    this.orderedDishes.splice(dishIndex, 1)
                }
            },
            loadMenu() {
                this.$store.dispatch('menu/loadMenuByDate', this.dateForAPI)
            },
            changeDate() {
                console.log('changeDate  WORKING')
                this.calendar = false

            },
            createOrder() {
                let dishesId = this.orderedDishes.map(dish => dish.dish_id);

                console.log(dishesId)

                const order = {
                    total: 1000000, //this.totalSumm,
                    status: 'new',
                    dishes: dishesId,
                    menu_id: this.menu.menu_id,
                    client: 2
                }
                console.log(order)
                this.$store.dispatch('order/createOrder', order).then(response => {
                    console.log(response)
                    this.orderID = response
                    this.orderSuccess = true
                    this.orderedDishes = []
                })
            },
        },
        watch: {
            date() {
                this.orderedDishes = []
                this.loadMenu()
            },
        },
        beforeRouteEnter(from, to, next) {
            // console.log('ORDER CREAYE ROUTING')
            next(vm => {
                vm.loadMenu()
            })
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
