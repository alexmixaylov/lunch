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
        <ul>
            <li v-for="order in orders">
                {{order}}
            </li>
        </ul>
        <v-dialog v-model="calendar" max-width="290">
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
                calendar: true,
            }
        },
        computed: {
            ...mapGetters('order', {orders: 'getOrders'}),
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
                this.$store.dispatch('order/loadOrdersByParams', params.join('&'))
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
