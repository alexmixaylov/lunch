<template>
    <div>
        <v-card-title class="headline" v-if="isOrders">
            {{dateForUser}}
            <v-spacer></v-spacer>
            <v-text-field
                    v-model="search"
                    append-icon="mdi-magnify"
                    label="Поиск по: ID, Компании и Человеку"
                    single-line
                    hide-details
            ></v-text-field>
        </v-card-title>

        <v-data-table
                v-if="isOrders"
                @click:row="routeToOrder"
                :dense="compactMode"
                :headers="headers"
                :items="orders"
                :search="search"
                class="elevation-1 orders mb-5"
                locale="ru"
                disable-pagination
                hide-default-footer
        ></v-data-table>
        <v-alert v-if="!isOrders" type="warning" class="subtitle-1">На <span class="title">{{dateForUser}}</span>
            заказов нет
        </v-alert>
    </div>
</template>

<script>
    import {dateFormat} from "../../../plugins/dateFormat";

    export default {
        name: "OrdersTable",
        props: ['orders', 'date', 'compactMode'],
        data: function () {
            return {
                headers: [
                    {text: 'ID', value: 'id', filterable: true,},
                    {text: 'Компания', value: 'title'},
                    {text: 'Человек', value: 'name'},
                    {text: 'Статус', value: 'status', filterable: false,},
                    {text: 'Сумма', value: 'total', filterable: false,}
                ],
                search: '',
            }
        },
        computed: {
            isOrders() {
                return this.orders.length > 0
            },
            dateForUser() {
                return dateFormat(this.date, 'dddd, dd mmm');
            }
        },
        methods: {
            routeToOrder(e) {
                console.log(e)
                this.$router.push('/orders/' + e.id)
                this.$store.commit('setGlobalDate', this.date)
            },
        }
    }
</script>

<style scoped>

</style>
