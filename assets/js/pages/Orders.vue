<template>
    <v-container>
        <dates-bar title="Список заказов на:" :isGlobalDate="true" @date-bar="dateChanged"></dates-bar>

        <v-divider class="pa-5"></v-divider>

        <template v-if="calendarMode === 'week'" v-for="day in ordersWeek">
            <orders-table :date="day.date" :compact-mode="compactMode" :orders="day.orders"></orders-table>
        </template>

        <template v-if="calendarMode !== 'week'">
            <orders-table :date="date" :compact-mode="compactMode" :orders="orders"></orders-table>
        </template>

        <v-switch v-model="compactMode" label="Компактный режим" class="mx-4"></v-switch>
        <v-btn color="orange" large>
            <router-link tag="span" :to="{name:'orders#create'}">Создать &nbsp;<v-icon>fa-plus</v-icon>
            </router-link>
        </v-btn>
    </v-container>
</template>

<script>
    import {mapGetters} from 'vuex';
    import {dateFormat} from "../plugins/dateFormat";
    import OrderCreate from "../components/order/OrderCreate";
    import DatesBar from '../components/dates/DatesBar';
    import OrdersTable from "../components/order/OrdersTable";

    export default {
        name: "Orders",
        components: {OrderCreate, DatesBar, OrdersTable},
        data() {
            return {
                localDate: new Date().toISOString().substr(0, 10),
                calendarMode: 'day',
                compactMode: false,
            }
        },
        computed: {
            ...mapGetters('order', {
                orders: 'getOrders',
                ordersWeek: 'getOrdersWeek'
            }),
            date: {
                get() {
                    return this.globalDate ? this.globalDate : this.localDate
                },
                set(date) {
                    console.log('GLOBAL DATE SET UP')
                    this.$store.commit('setGlobalDate', date)
                }
            },
            globalDate() {
                return this.$store.getters.getGlobalDate
            },
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
            dateChanged(dateAndModeObj) {
                console.log('EMIT CATHED', dateAndModeObj)
                const date = dateAndModeObj.date;
                const mode = dateAndModeObj.mode;
                this.calendarMode = mode;
                this.date = date;

                if (mode === 'week') {
                    this.$store.dispatch('order/loadOrdersByWeek', this.dateForAPI);
                } else {
                    console.log('EMIT CATHED  DAY MODE NOW MUST BE ACTTION')
                    this.$store.dispatch('order/loadOrdersByDate', date)
                }

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
    .orders tr:hover td {
        cursor: pointer !important;
    }
</style>
