<template>
    <v-container>
        <v-simple-table>
            <thead>
            <tr>
                <th>№ Заказа</th>
                <th>Person</th>
                <th>Status</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(order, index) in orders" :key="order.order_id">
                <td>{{ order.order_id }}</td>
                <td>{{index}}</td>
                <td>{{ order.status }}</td>
                <td>
                    <v-btn text color="orange" @click="goToActiveOrder(index, order.order_id)">
                        <span class="green--text" v-if="order.status === 'packed'">Упаковано</span>
                        <span v-else class="orange--text">Упаковать</span>
                    </v-btn>
                </td>
            </tr>
            </tbody>
        </v-simple-table>

        <v-row>
            <v-col class="shrink">
                <v-btn color="blue">
                    <router-link tag="span" :to="{name: 'delivery'}">< Все доставки</router-link>
                </v-btn>
            </v-col>
        </v-row>



        <v-dialog v-model="modal">
            <delivery-dishes
                    :date="date"
                    :order="order"
                    :dishes="dishes"
                    @closeModal="closeModal"
            ></delivery-dishes>
        </v-dialog>
    </v-container>
</template>

<script>
    import {mapGetters} from 'vuex';
    import {dateFormat} from "../../plugins/dateFormat";
    import DeliveryDishes from "./DeliveryDishes";

    export default {
        name: "DeliveryRead",
        components: {DeliveryDishes},
        data: function () {
            return {
                date: new Date().toISOString().substr(0, 10),
                orderIndex: false,
                modal: false
            }
        },
        computed: {
            ...mapGetters('delivery', {orders: 'getOrdersByCompany'}),
            dateForUser() {
                return dateFormat(this.date, 'ddd, dd mmm')
            },
            dateForAPI() {
                return dateFormat(this.date, 'yyyy-mm-dd');
            },
            order() {
                return this.orders[this.orderIndex]
            },
            dishes() {
                return this.$store.getters['delivery/getDishes']
            },
        },
        methods: {
            goToActiveOrder(index, orderId) {
                console.log('GO ACTIVE ORDER')
                this.orderIndex = index
                this.$store.dispatch('delivery/loadDishesByOrder', orderId).then(this.modal = true)
            },
            closeModal() {
                this.modal = false
            }
        },
        beforeRouteEnter(from, to, next) {
            next(vm => {
                const params = {
                    company_id: from.params.company,
                    date: vm.dateForAPI
                }

                vm.$store.dispatch('delivery/loadOrdersByCompanyAndDate', params)
            });
        }
    }
</script>

<style scoped>

</style>
