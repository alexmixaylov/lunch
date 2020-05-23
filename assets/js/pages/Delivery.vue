<template>
    <v-container>
        <v-row>
            <v-col>Заказы на {{ dateForUser }}</v-col>
        </v-row>
        <v-simple-table>
            <thead>
            <tr>
                <th>Компания</th>
                <th>Заказов</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="order in orders">
                <td>{{ order.title }}</td>
                <td class="title">{{ order.cnt }}</td>
                <td>
                    <v-btn text color="teal">
                        <router-link tag="span" :to="{name: 'delivery#by_company', params:{company: order.company_id}}">
                            Заказы
                        </router-link>
                    </v-btn>
                </td>
            </tr>
            </tbody>
        </v-simple-table>
    </v-container>
</template>

<script>
    import {mapGetters} from 'vuex';
    import {dateFormat} from "../plugins/dateFormat";

    export default {
        name: "Delivery",
        data: function () {
            return {
                date: new Date().toISOString().substr(0, 10)
            }
        },
        computed: {
            ...mapGetters('delivery', {orders: 'getAllOrdersByDay'}),
            dateForUser() {
                return dateFormat(this.date, 'dddd, dd mmm')
            },
            dateForAPI() {
                return dateFormat(this.date, 'yyyy-mm-dd');
            },

        },

        beforeRouteEnter(from, to, next) {
            next(vm => {
                vm.$store.dispatch('delivery/loadAllOrdersByDay', vm.dateForAPI)
            });
        }
    }
</script>

<style scoped>

</style>
