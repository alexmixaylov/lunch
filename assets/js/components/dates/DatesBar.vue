<template>
    <div>
        <v-row align="center">
            <v-col class="grow headline">{{ title }}</v-col>
            <v-col class="shrink">
                <v-btn-toggle v-model="calendarMode" borderless mandatory color="teal">
                    <v-btn value="today" large @click="setToday()">На Сегодня</v-btn>
                    <v-btn value="week" large @click="setWeek()">На Неделю</v-btn>
                    <v-btn value="day" large @click="calendar = true">{{dateForUser}} &nbsp;<v-icon>fa-edit</v-icon>
                    </v-btn>
                </v-btn-toggle>
            </v-col>
        </v-row>

        <v-row v-if="calendarMode === 'week'">
            <v-col class="shrink">
                <v-btn text color="teal" @click="prevWeek()">
                    <v-icon>fa-caret-left</v-icon>
                </v-btn>
            </v-col>
            <v-col class="grow text-center">{{ weekStartUser }} - {{ weekEndUser }}</v-col>
            <v-col class="shrink">
                <v-btn @click="nextWeek()">
                    <v-icon>fa-caret-right</v-icon>
                </v-btn>
            </v-col>
        </v-row>
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
    </div>
</template>

<script>
    import {dateFormat} from "../../plugins/dateFormat";

    // должен вернуть родителю ДАТУ(или конретная дата или любая дата среди недели) и РЕЖИМ (неделя/день)
    export default {
        name: "DatesBar",
        props: ['title', 'defaultMode', 'isGlobalDate'],
        data() {
            return {
                localDate: new Date().toISOString().substr(0, 10),
                calendar: false,
                calendarMode: 'day',
            }
        },
        computed: {
            date: {
                get() {
                    if (this.isGlobalDate) {
                        return this.globalDate ? this.globalDate : this.localDate
                    }
                    return this.localDate
                },
                set(date) {
                    this.localDate = date
                    if (this.isGlobalDate) {
                        this.$store.commit('setGlobalDate', date)
                    }
                }
            },
            globalDate() {
                return this.$store.getters.getGlobalDate
            },
            dateForUser() {
                return dateFormat(this.date, 'dd mmm')
            },
            weekStart() {
                function getMonday(d) {
                    d = new Date(d);
                    let day = d.getDay()
                    let diff = d.getDate() - day + (day === 0 ? -6 : 1); // adjust when day is sunday
                    return new Date(d.setDate(diff));
                }

                return getMonday(new Date(this.date)).toISOString().substr(0, 10);
            },
            weekEnd() {
                let monday = new Date(this.weekStart);
                let friday = new Date(monday.setTime(monday.getTime() + 4 * 86400000));

                return friday.toISOString().substr(0, 10)
            },
            weekStartUser() {
                return dateFormat(this.weekStart, 'dd mmm')
            },
            weekEndUser() {
                return dateFormat(this.weekEnd, 'dd mmm')
            }
        },
        methods: {
            setToday() {
                this.date = new Date().toISOString().substr(0, 10)
                this.$emit('date-bar', {date: this.date, mode: 'day'})
            },
            setWeek() {
                // вначале нужно сбросить дату на сегодня, чтобы если воспользовались календарем, не подтянулась та дата для старта недели
                this.date = new Date().toISOString().substr(0, 10)
                this.$emit('date-bar', {date: this.date, mode: 'week'})
            },
            changeDate() {
                this.calendar = false
                this.$emit('date-bar', {date: this.date, mode: 'day'})
            },
            nextWeek() {
                const date = new Date(this.date);
                this.date = new Date(date.setTime(date.getTime() + 7 * 86400000)).toISOString().substr(0, 10);
                this.$emit('date-bar', {date: this.date, mode: 'week'})
            },
            prevWeek() {
                const date = new Date(this.date);
                this.date = new Date(date.setTime(date.getTime() - 7 * 86400000)).toISOString().substr(0, 10);
                this.$emit('date-bar', {date: this.date, mode: 'week'})
            }
        },
        mounted() {
            // if (this.defaultMode) {
            //     this.calendarMode = this.defaultMode
            //     this.$emit('date-bar', {date: this.date, mode: 'week'})
            // }
        },
        watch: {
            weekStart() {
                console.log('WEEK START CHANGED')
            }
        }
    }
</script>

<style scoped>

</style>
