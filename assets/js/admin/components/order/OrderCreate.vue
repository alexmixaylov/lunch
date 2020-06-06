<template>
    <v-container justify="center">
        <v-row class="space-between">
            <v-col class="grow headline">Новый заказ
                <span v-if="totalSum">{{totalSum}}грн.</span>
                <span v-else>Поехали? :)</span>
            </v-col>
            <v-col class="shrink">
                <v-btn large color="red" v-if="!company" @click="showChooseCompany = true">Выбрать компанию</v-btn>
                <v-btn large color="green darken-4" v-if="company" @click="companyIndex = false">{{company.title}}&nbsp;
                    <v-icon small>fa-edit</v-icon>
                </v-btn>
            </v-col>
            <v-col class="shrink">
                <v-btn large color="red" v-if="!person" @click="showChoosePerson = true">Выбрать человека</v-btn>
                <v-btn large color="green darken-4" v-if="person" @click="personIndex = false">{{person.name}}&nbsp;
                    <v-icon small>fa-edit</v-icon>
                </v-btn>
            </v-col>
            <v-col class="shrink">
                <v-btn color="teal" large @click="calendar = true">{{dateForUser}} &nbsp;
                    <v-icon small>fa-edit</v-icon>
                </v-btn>
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

                    <div class="alex-row" v-for="(dish, index) in menu.dishes" :key="index">

                        <div>{{ dish.title }} {{dish.price}}грн. {{ dish.dish_id }}</div>
                        <div class="alex-row-end">
                            <v-btn text>
                                <v-icon small color="red" @click="minToOrdered(dish.dish_id)">fa-minus</v-icon>
                            </v-btn>
                            <v-btn text>
                                <v-icon small color="green" @click="addToOrdered(index)">fa-plus</v-icon>
                            </v-btn>

                        </div>
                    </div>
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

        <v-dialog v-model="showChooseCompany" persistent max-width="500">
            <v-card>
                <div class="alex-row" v-for="(company, index) in companies" :key="company.company_id">
                    <v-btn text @click="chooseCompany(index)">{{ company.title }}</v-btn>
                </div>
            </v-card>
        </v-dialog>

        <v-dialog v-model="showChoosePerson" persistent max-width="500">
            <v-card>
                <div class="alex-row" v-for="(person, index) in persons" :key="person.person_id">
                    <v-btn text @click="choosePerson(index)">{{ person.name }}</v-btn>
                </div>
            </v-card>
        </v-dialog>
    </v-container>
</template>

<script>
    //TODO maybe we can use default date in Before Router hook  :date-default="dateForAPI"
    import {mapGetters} from 'vuex';
    import {dateFormat} from "../../../plugins/dateFormat";
    import {encodeOrders} from "../../../plugins/dishNormalizer";

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
                showChooseCompany: true,
                showChoosePerson: false,
                companyIndex: false,
                personIndex: false,
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
            ...mapGetters('menu', {menu: 'getMenu'}),
            ...mapGetters('company', {
                companies: 'getCompanies',
                persons: 'getPersons'
            }),
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
            company() {
                return this.companies[this.companyIndex]
            },
            person() {
                return this.persons[this.personIndex]
            },
            order() {
                // const person = this.persons[] ? this.persons[this.personIndex] : false;
                return {
                    total: this.totalSum,
                    status: 'new',
                    dishes: this.dishes,
                    menu_id: this.menu.menu_id,
                    person_id: this.person.person_id,
                    message: this.message
                }
            },
            dishes() {
                return this.orderedDishes.map(dish => dish.dish_id)
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
            chooseCompany(index) {
                this.companyIndex = index
                this.showChooseCompany = false
                this.$store.dispatch('company/loadPersonsByCompany', this.company.company_id).then(this.showChoosePerson = true)
            },
            choosePerson(index) {
                // alert(index)
                this.personIndex = index
                this.showChoosePerson = false
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
            // console.log('ORDER CREAYE ROUTING')
            next(vm => {
                vm.loadMenu()
                vm.$store.dispatch('company/loadCompanies')
            })
        }
    }
</script>

<style>
    .fa-plus:before, .fa-minus:before {
        width: 16px;
        height: 16px;
    }

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
