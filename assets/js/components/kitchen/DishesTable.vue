<template>
    <div>
        <v-card-title class="headline" v-if="isDishes">{{dateForUser}}</v-card-title>
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
        <v-alert v-else type="warning" class="subtitle-1">На <span class="title">{{dateForUser}}</span> заказов нет</v-alert>
    </div>

</template>

<script>
    import {dateFormat} from "../../plugins/dateFormat";

    export default {
        name: "DishesTable",
        props: ['dishes', 'date'],
        data() {
            return {
                headers: [
                    {text: 'Название', value: 'title'},
                    {text: 'Кол-во', value: 'cnt'},
                ]
            }
        },
        computed: {
            isDishes() {
                return this.dishes.length > 0
            },
            dateForUser(){
                return dateFormat(this.date, 'dddd, dd mmm');
            }
        }
    }
</script>

<style scoped>

</style>
