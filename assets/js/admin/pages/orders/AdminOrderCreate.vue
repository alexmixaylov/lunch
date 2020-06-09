<template>
    <v-container>
        <v-row class="space-between min-width-buttons">
            <v-col>
                <v-btn large color="red" v-if="!company" @click="showChooseCompany = true">Выбрать компанию</v-btn>
                <v-btn large color="green darken-4" v-if="company" @click="companyIndex = false">{{company.title}}&nbsp;
                    <v-icon small>fa-edit</v-icon>
                </v-btn>
            </v-col>
            <v-col>
                <v-btn large color="red" v-if="!person" @click="showChoosePerson = true">Выбрать человека</v-btn>
                <v-btn large color="green darken-4" v-if="person" @click="personIndex = false">{{person.name}}&nbsp;
                    <v-icon small>fa-edit</v-icon>
                </v-btn>
            </v-col>
            <v-col>
                <v-btn color="teal" large @click="calendar = true">{{dateForUser}} &nbsp;
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
        <v-dialog v-model="showChooseCompany" persistent max-width="500">
            <v-card>
                <div class="alex-row" v-for="(company, index) in companies" :key="company.company_id">
                    <v-btn text @click="chooseCompany(index)">{{ company.title }}</v-btn>
                </div>
            </v-card>
        </v-dialog>

        <v-dialog v-model="showChoosePerson" persistent max-width="500">
            <v-card>
                <div class="alex-row" v-for="(person, index) in persons" :key="person.person_id">
                    <v-btn text @click="choosePerson(index)">{{ person.name }}</v-btn>
                </div>
            </v-card>
        </v-dialog>
    </v-container>

</template>

<script>
    import {mapGetters} from 'vuex';

    import OrderCreate from "../../../common/pages/orders/OrderCreate";
    import {dateFormat} from "../../../plugins/dateFormat";

    export default {
        name: "AdminOrderCreate",
        components: {
            OrderCreate
        },
        data: function () {
            return {
                date: new Date().toISOString().substr(0, 10),
                calendar: false,
                showChooseCompany: true,
                showChoosePerson: false,
                companyIndex: false,
                personIndex: false,
            }
        },
        computed: {
            ...mapGetters('person', {person: 'getPerson'}),
            ...mapGetters('common/company', {
                companies: 'getCompanies',
                persons: 'getPersons'
            }),
            dateForAPI() {
                return dateFormat(this.date, 'yyyy-mm-dd');
            },
            dateForUser() {
                return dateFormat(this.date, 'dddd, dd mmm')
            },
            company() {
                return this.companies[this.companyIndex]
            },
            person() {
                return this.persons[this.personIndex]
            },
        },
        methods: {
            changeDate() {
                this.calendar = false

            },
            chooseCompany(index) {
                this.companyIndex = index
                this.showChooseCompany = false
                this.$store.dispatch('common/company/loadPersonsByCompany', this.company.company_id).then(this.showChoosePerson = true)
            },
            choosePerson(index) {
                // alert(index)
                this.personIndex = index
                this.showChoosePerson = false
            },
        },
        beforeRouteEnter(from, to, next) {
            // console.log('ORDER CREAYE ROUTING')
            next(vm => {
                vm.$store.dispatch('common/company/loadCompanies')
            })
        }
    }
</script>
<style scoped>
    .min-width-buttons .col button{
        min-width: 250px;
    }
</style>
