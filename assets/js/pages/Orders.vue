<template>
    <v-row justify="center">
        <v-container>
            <v-row align="center">
                <v-col class="grow headline">Список заказов:</v-col>
                <v-col class="shrink">
                    <v-btn color="teal" large @click="calendar = true">{{dateForUser}} &nbsp;
                        <v-icon>fa-edit</v-icon>
                    </v-btn>
                </v-col>
                <v-col class="shrink">
                    <v-btn color="orange" large>
                        <router-link tag="span" :to="{name:'orders#create'}">Создать &nbsp;<v-icon>fa-plus</v-icon>
                        </router-link>
                    </v-btn>

                </v-col>
            </v-row>

            <v-divider class="pa-5"></v-divider>

            <v-data-table
                    :dense="compactMode"
                    v-if="isOrders"
                    :headers="headers"
                    :items="orders"
                    :items-per-page="10"
                    class="elevation-1"
                    locale="ru"
            ></v-data-table>

            <v-alert v-else type="warning" class="subtitle-1"><b>{{dateForUser}}</b> - заказов нет, можно выбрать другую
                дату
            </v-alert>
            <v-switch v-model="compactMode" label="Компактный режим" class="mx-4"></v-switch>
        </v-container>
        <v-dialog v-model="calendar" max-width="290">
            <v-date-picker
                    locale="ru"
                    first-day-of-week="1"
                    width="290"
                    color="teal"
                    v-model="date"
                    @change="calendar = false"
            ></v-date-picker>
        </v-dialog>
    </v-row>
</template>

<script>
    import {dateFormat} from "../plugins/dateFormat";
    import {mapGetters} from 'vuex';
    import OrderCreate from "../components/order/OrderCreate";

    export default {
        name: "Orders",
        components: {OrderCreate},
        data() {
            return {
                date: new Date().toISOString().substr(0, 10),
                calendar: false,
                compactMode: false,
                headers: [
                    {text: 'ID', value: 'id'},
                    {text: 'Client', value: 'client'},
                    {text: 'Статус', value: 'status'},
                    {text: 'Сумма', value: 'total'}
                ],
            }
        },
        computed: {
            ...mapGetters('order', {orders: 'getOrders'}),
            dateForAPI() {
                return dateFormat(this.date, 'yyyy-mm-dd');
            },
            dateForUser() {
                return dateFormat(this.date, 'dddd, dd mmm')
            },
            isOrders() {
                return this.orders.length > 0
            }
        },
        methods: {
            showCalendar() {
                this.orders
            },
        },
        watch: {
            date() {
                console.log('date changed')
                this.$store.dispatch('order/loadOrdersByDate', this.dateForAPI)
            }
        },
        beforeRouteEnter(from, to, next) {
            next(vm => {
                vm.$store.dispatch('order/loadOrdersByDate', vm.dateForAPI)
            });
        }
    }
</script>

<style scoped>
</style>
