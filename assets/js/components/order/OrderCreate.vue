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

        <v-divider></v-divider>

        <v-row>
            <v-col cols="12" lg="6">
                <v-card class="mxauto mt-5 mb-5" width="100%">
                    <v-app-bar dark color="teal darken-1">
                        <span>В Меню на </span>
                        <v-spacer></v-spacer>
                        <span class="title">{{dateForUser}}</span>
                    </v-app-bar>

                    <div class="alex-row" v-for="(dish, index) in menu.dishes" :key="index">

                        <div>{{ dish.title }} {{dish.price}}грн. {{ dish.dish_id }}</div>
                        <div class="alex-row-end">
                            <v-icon color="green " @click="minToOrdered(dish.dish_id)">fa-minus</v-icon>
                            &nbsp; &nbsp;
                            <v-icon color="red darken-2" @click="addToOrdered(index)">fa-plus</v-icon>
                        </div>
                    </div>
                </v-card>
            </v-col>

            <v-col  cols="12" lg="6" v-if="orderTable">
                <div class="mxauto mt-5 mb-5">
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
                </div>

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

        <v-dialog v-model="orderSuccess" max-width="500">
            <v-card>
                <v-app-bar dark color="teal">
                    <v-toolbar-title>Приятного апетита !!!</v-toolbar-title>
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
                let orderObj = {};
                this.orderedDishes.forEach(item => {
                    const dishId = item.dish_id;
                    if (orderObj.hasOwnProperty(dishId)) {
                        orderObj[dishId].cnt++
                        orderObj[dishId].summ = orderObj[dishId].summ + orderObj[dishId].price
                    } else {
                        orderObj[dishId] = {
                            title: item.title,
                            price: item.price,
                            summ: item.price,
                            cnt: 1
                        }
                    }
                });
                return Object.values(orderObj)
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
    .alex-row {
        display: flex;
        justify-content: space-between;
        padding: 10px 15px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    }

    .alex-row:last-of-type {
        border: none;
    }

    .alex-row-end {
        text-align: end;
    }

    .alex-row:hover {
        background-color: rgba(255, 255, 255, 0.2);
    }
</style>
