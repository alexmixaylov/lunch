<template>
    <v-card>
        <v-card-title>
            Заказ № {{ order.order_id }}
        </v-card-title>
        <v-simple-table>
            <thead>
            <tr>
                <th>Блюдо</th>
                <th>Кол-во</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="dish in dishes" :key="dish.dish_id">
                <td>{{ dish.title }}</td>
                <td>{{ dish.counters[dish.dish_id] }}</td>
            </tr>
            </tbody>
        </v-simple-table>
        <v-card-actions>
            <v-btn color="orange" @click="closeModal()">Закрыть</v-btn>
            <v-spacer></v-spacer>
            <v-btn v-if="!isPacked" color="green" @click="packed()">
                Спаковать
            </v-btn>
            <v-btn v-else color="red" @click="packedCanceled">
                Отменить упаковку
            </v-btn>
        </v-card-actions>
    </v-card>
</template>

<script>
    export default {
        name: "DeliveryDishes",
        props: ['date', 'order', 'dishes'],
        data: function () {
            return {}
        },
        computed: {
            isPacked() {
                return this.order.status === 'packed';
            },
            orderId() {
                return this.order.order_id
            }

        },
        methods: {
            packed() {
                const params = {
                    order_id: this.orderId,
                    status: 'packed'
                }
                this.$store.dispatch('order/changeOrderStatus', params)
                    .then(response => {
                        this.order.status = response
                        this.closeModal()
                    });
            },
            packedCanceled() {
                const params = {
                    order_id: this.order.order_id,
                    status: 'packed_canceled'
                }
                this.$store.dispatch('order/changeOrderStatus', params)
                    .then(response => {
                        this.order.status = response
                        this.closeModal()
                    }).then();
            },
            closeModal() {
                this.$emit('closeModal')
            }
        },
    }
</script>

<style scoped>

</style>
