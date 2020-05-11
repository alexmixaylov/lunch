<template>
    <v-row justify="center">
        <v-container>
            <v-row align="center">
                <v-col class="grow headline">Список блюд на:</v-col>
                <v-col class="shrink">
                    <v-btn color="teal" large @click="calendar = true">{{dateForUser}} &nbsp;
                        <v-icon>fa-edit</v-icon>
                    </v-btn>
                </v-col>
            </v-row>

            <v-divider class="pa-5"></v-divider>

            <v-data-table
                    v-if="isDishes"
                    disable-pagination
                    disable-sort
                    disable-filtering
                    hide-default-footer
                    :headers="headers"
                    :items="dishes"
                    class="elevation-1"
                    locale="ru"
            ></v-data-table>

            <v-alert v-else type="warning" class="subtitle-1">на сегодня заказов нет, можно выбрать другую дату
            </v-alert>
        </v-container>
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
    </v-row>
</template>

<script>
    import {dateFormat} from "../plugins/dateFormat";
    import {mapGetters} from 'vuex';

    export default {
        name: "Kitchen",
        data() {
            return {
                date: new Date().toISOString().substr(0, 10),
                calendar: false,
                headers: [
                    {text: 'Название', value: 'title'},
                    {text: 'Кол-во', value: 'cnt'},
                ]
            }
        },
        computed: {
            ...mapGetters('reducer', {dishes: 'getDishes'}),
            dateForAPI() {
                return dateFormat(this.date, 'yyyy-mm-dd');
            },
            dateForUser() {
                return dateFormat(this.date, 'dddd, dd mmm')
            },
            isDishes() {
                return this.dishes.length > 0
            }
        },
        methods: {},
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

</style>
