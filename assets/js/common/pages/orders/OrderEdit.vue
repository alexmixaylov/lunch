<template>
    <v-container justify="center">
        <v-row>
            <v-col class="title">Заказ №:{{order.order_id}}</v-col>
        </v-row>
        <v-divider></v-divider>
        <v-row>
            <v-col cols="12" sm="6">
                <v-card class="mxauto mt-5 mb-5" width="100%">
                    <v-card-title>{{dateForUser}}</v-card-title>
                    <div class="alex-row" v-for="(dish, index) in menu.dishes" :key="index">
                        <div>{{ dish.title }} {{dish.price}}грн.</div>
                        <div class="alex-row-end">
                            <v-icon small color="red " @click="minToOrdered(dish.dish_id)">fa-minus</v-icon>
                            &nbsp; &nbsp;
                            <v-icon small color="green" @click="addToOrdered(index)">fa-plus</v-icon>
                        </div>
                    </div>
                </v-card>
            </v-col>

            <v-col cols="12" sm="6" v-if="orderTable">
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
                    ><span @click="showMessage = true">{{ message }}</span>
                    </v-alert>
                </div>
                <v-row justify="space-between">
                    <v-col class="grow title text-right">Полная стоимость: {{totalSum}} грн.</v-col>
                </v-row>
            </v-col>

        </v-row>

        <v-divider></v-divider>

        <v-row>
            <v-col>
                <div class="subtitle-1">Получи скидку при заказе комплексного обеда</div>
            </v-col>
            <v-col>
                sdsd
            </v-col>
        </v-row>

        <v-divider></v-divider>

        <v-row justify="space-between">
            <v-col class="shrink">
                <v-btn large color="green darken-2">
                    <router-link tag="span" :to="{name: 'orders#read', params:{id: this.order.order_id}}">< Назад
                    </router-link>
                </v-btn>
            </v-col>

            <v-col class="grow title">Со скидой: {{totalSum}} грн.</v-col>
            <v-col class="shrink">
                <v-btn color="blue" large @click="showMessage = true">Сообщение</v-btn>
            </v-col>
            <v-col class="shrink">
                <v-btn color="orange" large @click="updateOrder()">Сохранить</v-btn>
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
                    <v-btn color="orange" @click="cancelEditingMessage()">Отменить</v-btn>
                    <v-spacer></v-spacer>
                    <v-btn color="teal" @click="showMessage = false">Добавить сообщение</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-dialog v-model="orderSuccess" max-width="500">
            <v-card>
                <v-app-bar dark color="teal">
                    <v-toolbar-title>Приятного апетита !!!</v-toolbar-title>
                </v-app-bar>


                <v-container>
                    <v-card-text class="title">
                        Заказ успешно обновлен
                    </v-card-text>
                </v-container>

                <v-card-actions>
                    <!--                    <v-btn color="orange darken-2" @click="orderSuccess = false">Закрыть</v-btn>-->
                    <v-spacer></v-spacer>
                    <v-btn color="green darken-2">
                        <router-link tag="span" :to="{name: 'orders#read', params:{id: this.order.order_id}}">OK
                        </router-link>
                    </v-btn>
                </v-card-actions>
            </v-card>

        </v-dialog>
    </v-container>
</template>

<script>
    import {mapGetters} from 'vuex';
    import {dateFormat} from "../../../plugins/dateFormat";
    import {encodeOrders} from "../../../plugins/dishNormalizer";

    export default {
        name: "OrderEdit",
        data() {
            return {
                orderSuccess: false,
                showMessage: false,
                calendar: false,
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
            ...mapGetters('common/menu', {menu: 'getMenu'}),
            ...mapGetters('common/commonOrder', {order: 'getOrder'}),
            orderedDishes() {
                if (this.order) {
                    return this.order.dishes
                }
            },
            dateForUser() {
                return dateFormat(this.order.date, 'dddd, dd mmm')
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
                this.$store.dispatch('common/menu/loadMenuByDate', this.order.date)
            },
            changeDate() {
                console.log('changeDate  WORKING')
                this.calendar = false

            },
            updateOrder() {
                let dishesId = this.orderedDishes.map(dish => dish.dish_id);
                if (dishesId.length < 1) {
                    alert('Вы не выбрали ни одного блюда!');
                    return false;
                }

                const order = {
                    order_id: this.order.order_id,
                    total: this.totalSum,
                    status: 'changed',
                    dishes: this.dishes,
                    message: this.message
                }

                this.$store.dispatch('common/commonOrder/updateOrder', order).then(response => {
                    console.log(response)
                    this.orderID = response
                    this.orderSuccess = true
                })
            },
            cancelEditingMessage() {
                this.message = this.order.message;
                this.showMessage = false
            }
        },
        mounted() {
            this.message = this.order.message
        },
        beforeRouteEnter(from, to, next) {
            const params = from.params;
            console.log(params)
            if (params.date) {
                next(vm => {
                    vm.$store.dispatch('common/commonOrder/loadOrderById', params.order_id)
                    vm.$store.dispatch('common/menu/loadMenuByDate', params.date)
                })
            }

        }
    }
</script>

<style scoped>
</style>
