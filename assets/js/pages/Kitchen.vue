<template>
    <v-container>
        <v-row align="center">
            <v-col class="grow headline">Список блюд на:</v-col>
            <v-col class="shrink">
                <v-btn-toggle v-model="dateToggle" borderless mandatory color="teal">
                    <v-btn :color="activeDateButton" value="today" large @click="setToday()">На Сегодня</v-btn>
                    <v-btn :color="activeDateButton" value="week" large @click="setWeek()">На Неделю
                    </v-btn>
                    <v-btn :color="activeDateButton" value="date" large @click="calendar = true">{{dateForUser}}
                        &nbsp;
                        <v-icon>fa-edit</v-icon>
                    </v-btn>
                </v-btn-toggle>
            </v-col>
        </v-row>

        <v-divider class="pa-5"></v-divider>

        <template v-if="activeDateButton !== 'week'">
            <dishes-table :dishes="dishes" :date="date"></dishes-table>
        </template>
        <template v-else v-for="day in dishesWeek">
            <dishes-table :dishes="day.dishes" :date="day.date"></dishes-table>
        </template>


        <v-dialog v-model="calendar" max-width="290">
            <v-date-picker
                    locale="ru"
                    first-day-of-week="1"
                    width="290"
                    color="teal"
                    v-model="date"
                    @change="calendar = false"
            ></v-date-picker>
        </v-dialog>
    </v-container>
</template>

<script>
    import {dateFormat} from "../plugins/dateFormat";
    import {mapGetters} from 'vuex';
    import Calendar from "../components/dates/Calendar";
    import DishesTable from "../components/kitchen/DishesTable";

    export default {
        name: "Kitchen",
        components: {Calendar, DishesTable},
        data() {
            return {
                date: new Date().toISOString().substr(0, 10),
                calendar: false,
                dateToggle: 'today',
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
                return dateFormat(this.date, 'dddd, dd mmm')
            },
            activeDateButton() {
                return this.dateToggle
            },
            isDishes() {
                return this.dishes.length > 0
            }
        },
        methods: {
            setToday() {
                this.date = new Date().toISOString().substr(0, 10)
            },
            setWeek() {
                this.$store.dispatch('reducer/loadDishesByWeek', this.dateForAPI);
            }
        },
        watch: {
            date() {
                console.log('date changed')
                this.$store.dispatch('reducer/loadDishesByDate', this.dateForAPI)
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
