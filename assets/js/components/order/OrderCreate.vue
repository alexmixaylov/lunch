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


        <v-card class="mxauto mt-5 mb-5" width="100%">
            <v-list-item>
                <v-list-item-content>
                    <v-list-item-title class="subtitle-1">Меню на <span class="title">{{dateForUser}}</span>
                    </v-list-item-title>
                </v-list-item-content>
            </v-list-item>


            <v-list-item v-for="(dish, index) in menu.dishes" :key="index">
                <v-list-item-content>
                    <v-col class="grow">{{ dish.title }} {{dish.price}}грн.</v-col>
                    <v-col class="shrink">
                        <v-icon color="orange" @click="minToOrdered(dish)">fa-minus</v-icon>
                    </v-col>
                    <v-col class="shrink">
                        <v-icon color="orange" @click="addToOrdered(dish)">fa-plus</v-icon>
                    </v-col>

                    <!--                        <v-spacer></v-spacer>-->

                </v-list-item-content>
            </v-list-item>


        </v-card>


        <template v-if="orderTable">
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
                <v-col class="grow title">Итого: {{totalSumm}} грн.</v-col>
                <v-col class="grow title">Со скидкой:</v-col>
                <v-col class="shrink">
                    <v-btn color="orange" large @click="createOrder()">Заказать</v-btn>
                </v-col>
            </v-row>
        </template>
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
                orderedDishes: {},
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
                    console.log(item)
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
            addToOrdered(dish) {
                let orderObj = this.orderedDishes;
                const dishId = dish.dish_id;

                let item = {
                    cnt: 1,
                    dish_id: dishId,
                    price: dish.price,
                    title: dish.title
                }

                if (orderObj.hasOwnProperty(dishId)) {
                    orderObj[dishId].cnt++
                } else {
                    orderObj[dishId] = item
                }

                // для реактивности изменений
                this.orderedDishes = Object.assign({}, this.orderedDishes, orderObj)
            },
            minToOrdered(dish) {
            },
            loadMenu() {
                this.$store.dispatch('menu/loadMenuByDate', this.dateForAPI)
            },
            changeDate() {
                console.log('changeDate  WORKING')
                this.calendar = false

            },
            createOrder() {
                let dishesId = Object.values(this.orderedDishes).map(dish => dish.dish_id);

                const order = {
                    total: this.totalSumm,
                    status: 'new',
                    dishes: dishesId,
                    menu_id: this.menu.menu_id
                }
                console.log(order)
                this.$store.dispatch('order/createOrder', order).then(response => {
                    console.log(response)
                    this.orderedDishes = {}
                })
            },
        },
        watch: {
            date() {
                this.orderedDishes = {}
                this.loadMenu()
            },
            // orderedDishes() {
            // console.log('orderedDishes WATCHER WORKING')

            // }
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
