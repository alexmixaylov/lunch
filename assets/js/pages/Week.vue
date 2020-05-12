<template>
    <v-row justify="center">
        <!--        <v-date-picker v-model="picker" ></v-date-picker>-->
        <v-tabs centered grow>
            <v-tab v-on:click="generateDateWithShift(-1)">< назад</v-tab>
            <v-tab v-on:click="generateDateWithShift(0)">Текущая неделя</v-tab>
            <v-tab v-on:click="generateDateWithShift(1)">вперед ></v-tab>
        </v-tabs>
        <menu-list
                v-if="menu"
                v-for="menu in menusFromAPI"
                v-bind:key="menu.menu_id"
                :date="menu.date"
                :menu-id="menu.menu_id"
                :dishes="menu.dishes"
        >
        </menu-list>
    </v-row>
</template>

<script>
    import {dateFormat} from "./dateFormat";
    import {mapGetters} from 'vuex';
    import MenuList from "../components/menu/MenuList";

    export default {
        name: "Week",
        components: {
            'menu-list': MenuList
        },
        data() {
            return {
                shift: 0,
                //TODO this variable needs rename
            }
        },
        computed: {
            ...mapGetters('menu', {
                menusFromAPI: 'getMenus',
                selectedDate: 'getSelectedDate'
            }),
            dateForAPI() {
                return dateFormat(this.selectedDate, 'yyyy-mm-dd');
            },
            dateForUser() {
                return dateFormat(this.selectedDate, 'd mmmm');
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

                this.$store.commit('menu/setSelectedDate', newDate)

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
