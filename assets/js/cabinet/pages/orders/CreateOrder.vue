<template>
    <v-container justify="center">
        <v-row>
            <v-col v-if="isCorporate">
                <local-storage></local-storage>
            </v-col>
            <v-col v-else>
                <v-btn large color="green">{{ user.name }}</v-btn>
            </v-col>
            <v-col>
                <v-btn large @click="calendar = true" color="teal">{{dateForUser}}</v-btn>
            </v-col>
        </v-row>
        <v-divider></v-divider>
        <v-row>
            <v-col cols="12" md="6">
                <v-card class="mxauto mt-5 mb-5" width="100%">
                    <v-app-bar dark color="teal darken-1">
                        <span>В Меню на </span>
                        <v-spacer></v-spacer>
                        <span class="title">{{dateForUser}}</span>
                    </v-app-bar>

                    <template v-if="menu">
                        <div class="alex-row" v-for="(dish, index) in menu.dishes" :key="index">
                            <div>{{ dish.title }} {{dish.price}}грн.</div>
                            <div class="alex-row-end">
                                <v-icon color="green " @click="minToOrdered(dish.dish_id)">fa-minus</v-icon>
                                &nbsp; &nbsp;
                                <v-icon color="red darken-2" @click="addToOrdered(index)">fa-plus</v-icon>
                            </div>
                        </div>
                    </template>

                </v-card>
            </v-col>

            <v-col cols="12" md="6" v-if="orderTable">
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
                    <v-alert
                            v-if="message"
                            light
                            class="mt-3"
                    >{{ message }}
                    </v-alert>
                </div>

                <v-row justify="space-between">
                    <v-col class="grow title text-right">Полная стоимость: {{totalSum}} грн.</v-col>
                </v-row>
            </v-col>

        </v-row>

        <v-divider></v-divider>
        <v-row justify="space-between">
            <v-col class="grow title">Со скидой: {{totalSum}} грн.</v-col>
            <v-col class="shrink">
                <v-btn color="blue" large @click="showMessage = true">Сообщение</v-btn>
            </v-col>
            <v-col class="shrink">
                <v-btn color="orange" large @click="createOrder()">Заказать</v-btn>
            </v-col>
        </v-row>

        <v-dialog v-model="showMessage" max-width="500">
            <v-card>
                <v-card-title>
                    Добавьте свои пожелания
                </v-card-title>
                <v-card-text>
                    <v-textarea solo v-model="message"></v-textarea>
                </v-card-text>

                <v-card-actions>
                    <v-btn color="orange" @click="clearMessage()">Отменить</v-btn>
                    <v-spacer></v-spacer>
                    <v-btn color="teal" @click="showMessage = false">Добавить сообщение</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
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
    import {dateFormat} from "../../../plugins/dateFormat";
    import {encodeOrders} from "../../../plugins/dishNormalizer";
    import Storage from "../../components/Storage";

    export default {
        name: "OrderCreate",
        components:{
            'local-storage': Storage
        },
        data() {
            return {
                showChoosePerson: false,
                date: new Date().toISOString().substr(0, 10),
                calendar: false,
                orderID: false,
                orderSuccess: false,
                orderedDishes: [],
                showMessage: false,
                message: '',
                headers: [
                    {text: 'Название', value: 'title'},
                    {text: 'Цена', value: 'price'},
                    {text: 'Кол-во', value: 'cnt'},
                    {text: 'Сумма', value: 'summ'}
                ]
            }
        },
        computed: {
            ...mapGetters('user', {user: 'getUser'}),
            ...mapGetters('menu', {menu: 'getMenu'}),
            ...mapGetters('person', {person:'getPerson'}),
            persons() {
                return this.isCorporate ? this.$store.getters["person/getPersons"] : []
            },
            isCorporate() {
                return this.user.type === 'corporate'
            },
            dateForAPI() {
                return dateFormat(this.date, 'yyyy-mm-dd');
            },
            dateForUser() {
                return dateFormat(this.date, 'dddd, dd mmm')
            },
            normalizeOrders() {
                return encodeOrders(this.orderedDishes)
            },
            orderTable() {
                return this.normalizeOrders.length > 0
            },
            totalSum() {
                return this.normalizeOrders.reduce((acc, item) => {
                    return acc + item.summ
                }, 0);
            },
            order() {
                return {
                    total: this.totalSum,
                    status: 'new',
                    dishes: this.orderedDishes.map(dish => dish.dish_id),
                    menu_id: this.menu.menu_id,
                    person_id: this.person.person_id,
                    message: this.message
                }
            },
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
                if (dishesId.length < 1) {
                    alert('Вы не выбрали ни одного блюда!');
                    return false;
                }
                this.$store.dispatch('order/createOrder', this.order).then(response => {
                    console.log(response)
                    this.orderID = response
                    this.orderSuccess = true
                    this.orderedDishes = []
                })
            },
            clearMessage() {
                this.message = '';
                this.showMessage = false
            }
        },
        watch: {
            date() {
                this.orderedDishes = []
                this.loadMenu()
            },
        },
        beforeRouteEnter(from, to, next) {
            console.log('ORDER CREAYE ROUTIN')
            next(vm => {
                vm.loadMenu()
            })
        }
    }
</script>
