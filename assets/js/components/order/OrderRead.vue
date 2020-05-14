<template>
    <v-row justify="center">
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
                </v-card-title>

                <v-card-text>
                    <div v-for="(dish, i) in dishes" :key="i">
                        {{ dish.title}}
                    </div>
                </v-card-text>
                <v-card-actions>
                   Сумма: {{order.total}}
                </v-card-actions>
            </v-card>
        </v-container>
    </v-row>
</template>

<script>
    import {mapGetters} from 'vuex'
    import {dateFormat} from "../../plugins/dateFormat";

    export default {
        name: "OrderRead",
        data: function () {
            return {}
        },
        computed: {
            ...mapGetters('order', {order: 'getOrder'}),
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
                return this.order.dishes
            }
        },
        methods: {
            loadOrder() {
                this.$store.dispatch('order/loadOrderById', this.$route.params.id)
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
