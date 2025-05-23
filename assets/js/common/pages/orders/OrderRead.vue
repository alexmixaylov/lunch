<template>
    <v-container>
        <v-card>
            <v-card-title class="blue-grey">
                <span>Заказ ID: {{ order.order_id }} &nbsp;</span>
                <v-tooltip bottom>
                    <template v-slot:activator="{ on }">
                        <span v-on="on"><v-icon>fa-info-circle</v-icon></span>
                    </template>
                    <span>{{tooltip}}</span>
                </v-tooltip>

                <v-spacer></v-spacer>
                Дата: {{ order.date }}
                &nbsp;
                Итого: {{ order.total }}грн.
            </v-card-title>

            <v-card-text>
                <v-data-table
                        v-if="dishes"
                        disable-pagination
                        disable-sort
                        disable-filtering
                        hide-default-footer
                        :headers="headers"
                        :items="dishes"
                        class="elevation-1"
                        locale="ru"
                ></v-data-table>
            </v-card-text>

            <v-card-actions>
                <v-btn color="blue">
                    <router-link tag="span" :to="{name: 'orders'}">< Заказы</router-link>
                </v-btn>
                <v-spacer></v-spacer>
                <template v-if="order.status === 'packed'">
                    <v-btn color="green" @click="orderPacked">заказ упакован</v-btn>
                </template>
                <template v-else>
                    <template v-if="order.status !== 'canceled'">
                        <v-btn color="red" @click="cancelOrder()">Отменить заказ</v-btn>
                    </template>
                    <template v-else>
                        <v-btn disabled>Заказ отменен</v-btn>
                    </template>
                    <v-btn color="orange">
                        <router-link tag="span" :to="{name: 'orders#edit', params: order}">Изменить заказ</router-link>
                    </v-btn>
                </template>
            </v-card-actions>
        </v-card>
        <v-dialog v-model="confirmDialog" persistent max-width="300">

            <v-card>
                <v-card-title class="headline">Уверены что хотите удалить заказ?</v-card-title>
                <v-card-text>Возможно вы можете изменить статус заказа. Это действие нельзя отменить</v-card-text>
                <v-card-actions>
                    <v-btn color="green darken-1" @click="confirmDelete(false)">Передумал</v-btn>
                    <v-spacer></v-spacer>
                    <v-btn color="red darken-1" @click="confirmDelete(true)">Конечно</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <loading :dialog="loading"></loading>
    </v-container>
</template>

<script>
    import {mapGetters} from 'vuex'
    import {dateFormat} from "../../../plugins/dateFormat";
    import {encodeOrders} from "../../../plugins/dishNormalizer";
    import Loading from "../../../admin/components/dialog/Loading";

    export default {
        name: "OrderRead",
        components: {Loading},
        data: function () {
            return {
                confirmDialog: false,
                confirmDialogData: {
                    question: '',
                    text: ''
                },
                loading: false,
                headers: [
                    {text: 'Название', value: 'title'},
                    {text: 'Цена', value: 'price'},
                    {text: 'Кол-во', value: 'cnt'},
                    {text: 'Сумма', value: 'summ'}
                ]
            }
        },
        computed: {
            ...mapGetters('common/commonOrder', {order: 'getOrder'}),
            created() {
                return dateFormat(this.order.created, 'dddd, dd mmm HH:MM')
            },
            updated() {
                return dateFormat(this.order.updated, 'dddd, dd mmm HH:MM')
            },
            tooltip() {
                return this.created === this.updated ? `Создан: ${this.created}` : `Обновлен: ${this.updated}`
            },
            dishes() {
                return encodeOrders(this.order.dishes)
            },

        },
        methods: {
            loadOrder() {
                this.$store.dispatch('common/commonOrder/loadOrderById', this.$route.params.id)
            },
            confirmDelete(answer) {
                this.confirmDialog = true

                if (answer) {
                    this.loading = true
                    this.deleteOrder()
                } else {
                    this.confirmDialog = false
                }

            },
            deleteOrder() {
                this.$store.dispatch('common/commonOrder/deleteOrder', this.order.order_id).then(this.loading = false).then(this.$router.push({name: 'orders'}))
            },
            cancelOrder() {
                const params = {
                    order_id: this.order.order_id,
                    status: 'canceled'
                }
                this.$store.dispatch('common/commonOrder/changeOrderStatus', params).then(response => {
                    this.$store.commit('common/commonOrder/setOrderStatus', response)
                    console.log(response)
                })
            },
            orderPacked(){
                alert('Сейчас вы уже не можете изменить или отменить ваш заказ, потому что он уже в дороге.')
            }
        },
        beforeRouteEnter(from, to, next) {
            console.log(to)
            next(vm => {
                vm.loadOrder();
            })
        }
    }
</script>

<style scoped>

</style>
