<template>
    <v-container justify="center">
        <v-row>
            <v-col>
                <v-btn large @click="calendar = true" color="teal">{{dateForUser}} &nbsp;
                    <v-icon small>fa-edit</v-icon>
                </v-btn>
            </v-col>
        </v-row>

        <v-divider></v-divider>

        <v-data-table
                v-if="orders"
                disable-pagination
                disable-filtering
                hide-default-footer
                :headers="headers"
                :items="orders"
                class="elevation-1 orders mb-5"
                locale="ru"
                @click:row="routeToOrder"
        ></v-data-table>

        <v-dialog v-model="calendar" max-width="300">
            <v-date-picker
                    locale="ru"
                    first-day-of-week="1"
                    width="290"
                    color="teal"
                    v-model="date"
                    @change="changeDate()"
            ></v-date-picker>
        </v-dialog>
    </v-container>
</template>

<script>
    import {mapGetters} from 'vuex';
    import {dateFormat} from "../../../plugins/dateFormat";

    export default {
        name: "Orders",
        data: function () {
            return {
                date: new Date().toISOString().substr(0, 10),
                calendar: false,
                headers:[
                    {text: 'Номер', value: 'id'},
                    {text: 'Имя', value: 'name'},
                    {text: 'Статус', value: 'status'},
                    {text: 'Сумма', value: 'total'}
                ]
            }
        },
        computed: {
            ...mapGetters('common/commonOrder', {orders: 'getOrders'}),
            ...mapGetters('person', {person: 'getPerson'}),
            dateForAPI() {
                return dateFormat(this.date, 'yyyy-mm-dd');
            },
            dateForUser() {
                return dateFormat(this.date, 'dddd, dd mmm')
            },
            request() {
                return {
                    date: this.dateForAPI
                }
            },


        },
        methods: {
            changeDate() {
                console.log('changeDate  WORKING')
                this.calendar = false
            },
            loadCorporateOrders() {
                const params = [
                    `person_id=${this.person.person_id}`,
                    `date=${this.dateForAPI}`
                ]
                this.$store.dispatch('common/commonOrder/loadOrdersByParams', params.join('&'))
            },
            routeToOrder(row){
                console.log(row)
                this.$router.push('/orders/' + row.id)
                // this.$store.commit('setGlobalDate', this.date)
            }
        },
        mounted() {
            this.loadCorporateOrders()
        },
        watch: {
            date() {
                this.loadCorporateOrders()
            },
        },
    }
</script>

<style scoped>

</style>
