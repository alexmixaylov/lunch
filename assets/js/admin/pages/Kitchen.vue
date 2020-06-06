<template>
    <v-container>
        <dates-bar title="Список блюд на:" @date-bar="dateChanged"></dates-bar>

        <v-divider class="pa-5"></v-divider>

        <template v-if="calendarMode !== 'week'">
            <dishes-table :dishes="dishes" :date="date"></dishes-table>
        </template>

        <template v-else v-for="day in dishesWeek">
            <dishes-table :dishes="day.dishes" :date="day.date"></dishes-table>
        </template>
    </v-container>
</template>

<script>
    import {dateFormat} from "../../plugins/dateFormat";
    import {mapGetters} from 'vuex';
    import Calendar from "../components/dates/Calendar";
    import DishesTable from "../components/kitchen/DishesTable";
    import DatesBar from '../components/dates/DatesBar';

    export default {
        name: "Kitchen",
        components: {Calendar, DishesTable, DatesBar},
        data() {
            return {
                date: new Date().toISOString().substr(0, 10),
                calendarMode: 'day'
            }
        },
        computed: {
            ...mapGetters(
                'reducer', {
                    dishes: 'getDishes',
                    dishesWeek: 'getDishesWeek'
                }
            ),
            dateForAPI() {
                return dateFormat(this.date, 'yyyy-mm-dd');
            },
            dateForUser() {
                return dateFormat(this.date, 'ddd, dd mmm')
            },
            activeDateButton() {
                return this.dateToggle
            },
            isDishes() {
                return this.dishes.length > 0
            },
            weekStart() {
                const date = this.dishesWeek[0] ? this.dishesWeek[0].date : null;
                return dateFormat(date, 'dd mmm')
            },
            weekEnd() {
                const date = this.dishesWeek[0] ? this.dishesWeek[4].date : null;
                return dateFormat(date, 'dd mmm')
            }
        },
        methods: {
            dateChanged(dateAndModeObj) {
                console.log('EMIT CATHED', dateAndModeObj)

                this.date = dateAndModeObj.date
                this.calendarMode = dateAndModeObj.mode

                if (this.calendarMode === 'day') {
                    this.$store.dispatch('reducer/loadDishesByDate', this.dateForAPI)
                } else {
                    this.$store.dispatch('reducer/loadDishesByWeek', this.dateForAPI);
                }
            }
        },
        beforeRouteEnter(from, to, next) {
            next(vm => {
                vm.$store.dispatch('reducer/loadDishesByDate', vm.dateForAPI)
            });
        }
    }
</script>

<style scoped>
    .v-item--active {
        color: red;
    }
</style>
