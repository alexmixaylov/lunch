<template>
    <v-container>
        <v-row class="min-width-buttons">
            <v-col v-if="isCorporate">
                <local-storage></local-storage>
            </v-col>
            <v-spacer></v-spacer>
            <v-col class="text-right">
                <v-btn large @click="calendar = true" color="teal">
                    {{dateForUser}}
                    <v-icon small>fa-edit</v-icon>
                </v-btn>
            </v-col>
        </v-row>

        <v-divider></v-divider>

        <order-create
                :date="dateForAPI"
                :person="person"
        ></order-create>

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
    import OrderCreate from "../../../common/pages/orders/OrderCreate";
    import {mapGetters} from "vuex";
    import {dateFormat} from "../../../plugins/dateFormat";
    import LocalStorage from "../../../cabinet/components/Storage";


    export default {
        name: "UserOrderCreate",
        components: {
            OrderCreate,
            LocalStorage
        },
        data: function () {
            return {
                date: new Date().toISOString().substr(0, 10),
                calendar: false,
            }
        },
        computed: {
            ...mapGetters('person',
                {
                    person: 'getPerson',
                    persons: 'getPersons'
                }
            ),
            ...mapGetters('common/user', {user: 'getUser'}),
            dateForAPI() {
                return dateFormat(this.date, 'yyyy-mm-dd');
            },
            dateForUser() {
                return dateFormat(this.date, 'dddd, dd mmm')
            },
            isCorporate() {
                return this.user.type === 'corporate'
            },
        },
        methods: {
            changeDate() {
                this.calendar = false
            },
        }
    }
</script>

<style scoped>

</style>
