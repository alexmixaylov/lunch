<template>
    <v-row justify="center">
        <!--        <v-date-picker v-model="picker" ></v-date-picker>-->
        <v-tabs centered center-active grow background-color="orange--text" active-class="darken-1">
            <v-tab v-on:click="prevWeek()">< назад</v-tab>
            <v-tab v-on:click="thisWeek()">Эта неделя</v-tab>
            <v-tab v-on:click="nextWeek()">вперед ></v-tab>
        </v-tabs>
        <menu-list :listOfDays="timing"></menu-list>
    </v-row>
</template>

<script>
    import MenuList from "./MenuList";

    export default {
        name: "Week",
        components: {

            'menu-list': MenuList
        },
        data() {
            return {
                indexDate: 0,
                activeWeek: 0
            }
        },
        computed: {
            timing() {
                return this.createWeek(this.activeWeek);
            }
        },
        methods: {
            thisWeek() {
                this.activeWeek = 0;
            },
            nextWeek() {
                this.activeWeek += 7;
            },
            prevWeek() {
                this.activeWeek -= 7;
            },
            createWeek(shift = 0) {
                const addDaysToDate = function (shift) {
                    Date.prototype.addDays = function (days) {
                        let date = new Date(this.valueOf());
                        date.setDate(date.getDate() + days);
                        return date;
                    };
                    let date = new Date();
                    return date.addDays(shift)
                };
                const getText = function (type, index) {
                    const ru = {
                        day: ['Воскресенье', 'Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота'],
                        month: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь']
                    };
                    return ru[type][index];
                };

                let start = this.indexDate < 1 ? 0 : (this.indexDate - 1) * -1;
                const indexOfDays = function (start, acc) {
                    const weekLength = 6;
                    if (acc.length > weekLength) {
                        return acc;
                    }
                    acc.push(addDaysToDate(start));
                    return indexOfDays(start + 1, acc)
                };

                let fullWeek = indexOfDays(start + shift, []).map((day) => {
                    const indexWeekDay = day.getDay();
                    return {
                        stringDate: `${getText('day', indexWeekDay)},  ${day.getDate()}`,
                        month: `${getText('month', day.getMonth())}`,
                        indexWeekDay: indexWeekDay,
                        rawDate: day
                    }
                });

                return fullWeek.filter(day => {
                    // remove Saturday and Sunday from result
                    return (day.indexWeekDay > 0 && day.indexWeekDay < 6)
                });
            },
        },
        created() {
        },
        mounted() {
        }
    }
</script>

<style scoped>

</style>
