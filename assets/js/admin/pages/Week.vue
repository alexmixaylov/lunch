<template>
    <main class="text-center">
        <v-row justify="center">
            <v-col class="shrink">
                <v-btn @click="generateDateWithShift(-1)">
                    <v-icon>fa-caret-left</v-icon>
                </v-btn>
            </v-col>
            <!--            <v-col class="grow text-center">{{ weekStartUser }} - {{ weekEndUser }}</v-col>-->
            <v-col class="now-week">
                <v-btn @click="generateDateWithShift(0)">ТЕКУЩАЯ НЕДЕЛЯ</v-btn>
            </v-col>
            <v-col class="shrink">
                <v-btn @click="generateDateWithShift(1)">
                    <v-icon>fa-caret-right</v-icon>
                </v-btn>
            </v-col>
        </v-row>

        <v-row justify="center">
            <menu-list
                    v-if="menu"
                    v-for="menu in menus"
                    v-bind:key="menu.menu_id"
                    :date="menu.date"
                    :menu-id="menu.menu_id"
                    :dishes="menu.dishes"
            >
            </menu-list>
        </v-row>
    </main>
</template>

<script>
    import {dateFormat} from "../../plugins/dateFormat";
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
            ...mapGetters('common/menu', {
                menus: 'getMenus',
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

                this.$store.commit('common/menu/setSelectedDate', newDate)

                // update store with new dates
                this.loadMenuTable();
            },
            loadMenuTable() {
                this.$store.dispatch("common/menu/loadMenuTable", this.dateForAPI).then(response => {console.log(response)})
            },
        },
        mounted() {
            this.loadMenuTable()
        },
        // beforeRouteEnter(to, from, next) {
        //     next(vm => {
        //         vm.loadMenuTable();
        //     })
        //
        // },
    }
</script>
<style scoped>
.now-week{
    padding-left: 0;
    padding-right: 0;
}
.now-week .v-btn{
    padding-left: 7px;
    padding-right: 7px;
}
</style>
