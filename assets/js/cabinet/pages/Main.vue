<template>
    <v-container>
        <v-card v-if="menu" @click="createOrder(menu.menu_id)">
            <v-card-title class="teal">
                {{dateForUser}}
            </v-card-title>
            <v-card-text v-for="dish in dishes" :key="dish.dish_id">
                {{dish.title }}
            </v-card-text>
        </v-card>
    </v-container>
</template>

<script>
    import {mapGetters} from "vuex";
    import {dateFormat} from '../../plugins/dateFormat'

    export default {
        name: "Main",
        data: function () {
            return {
                date: new Date()
            }
        },
        computed: {
            ...mapGetters('common/menu', {menu: 'getMenu'}),
            dateForUser() {
                return dateFormat(this.menu.date, 'dddd, dd mmmm')
            },
            dateForAPI() {
                return dateFormat(this.date, 'yyyy-mm-dd')
            },
            dishes() {
                return this.menu.dishes
            }
        },
        methods: {
            createOrder(menuId) {
                this.$router.push({name: 'orders#create', params:{date: this.dateForAPI}})
            }
        },
        beforeRouteEnter(from, to, next) {
            next(vm => {
                vm.$store.dispatch('common/menu/loadMenuByDate', vm.dateForAPI).then(response => {
                    console.log(response)
                })
            })
        }
    }
</script>

<style scoped>

</style>
