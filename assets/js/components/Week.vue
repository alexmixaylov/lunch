<template>
    <v-container>
        <v-row justify="center">
            <!--        <v-date-picker v-model="picker" ></v-date-picker>-->
            <v-tabs centered center-active grow background-color="orange--text" active-class="darken-1">
                <v-tab v-on:click="generateDateWithShift(-1)">< назад</v-tab>
                <v-tab v-on:click="generateDateWithShift(0)">Эта неделя</v-tab>
                <v-tab v-on:click="generateDateWithShift(1)">вперед ></v-tab>
            </v-tabs>
            <!--        <menu-list :listOfDays="timing"></menu-list>-->
        </v-row>
        <v-row>
            {{ menus }}
        </v-row>
    </v-container>
</template>

<script>
    import {dateFormat}  from "../plugins/dateFormat";
    import {mapGetters} from 'vuex';
    import MenuList from "./MenuList";

    export default {
        name: "Week",
        components: {
            'menu-list': MenuList
        },
        data() {
            return {
                shift: 0,
                selectedWeek: new Date()
            }
        },
        computed: {
            ...mapGetters('menu', {menus: 'getMenus'}),
            dateForAPI() {
                return dateFormat(this.selectedWeek, 'yyyy-mm-dd');
            },
            dateForUser() {
                return dateFormat(this.selectedWeek, 'd mmmm');
            }
        },
        methods: {
            generateDateWithShift(shift) {

                (shift === 0) ? this.shift = 0 : this.shift = this.shift + shift;
                // additional function for adding days
                const addDaysToDate = function (shift) {
                    Date.prototype.addDays = function (days) {
                        let date = new Date(this.valueOf());
                        date.setDate(date.getDate() + days);
                        return date;
                    };
                    let date = new Date();
                    return date.addDays(shift)
                };

                const newDate = addDaysToDate(this.shift * 7);

                this.selectedWeek = newDate;

                // update store with new dates
                this.loadMenuTable();
            },
            loadMenuTable() {
                this.$store.dispatch("menu/loadMenuTable", this.dateForAPI)
            },


        },
        created() {
        },
        mounted() {
        },
        beforeRouteEnter(to, from, next) {
            // вызывается до подтверждения пути, соответствующего этому компоненту.
            // НЕ ИМЕЕТ доступа к контексту экземпляра компонента `this`,
            // так как к моменту вызова экземпляр ещё не создан!
            // console.log(to)
            // console.log(from)
            next(vm => {
                vm.loadMenuTable();
            })

        },
    }
</script>
