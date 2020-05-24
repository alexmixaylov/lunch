<template>
    <v-container>
        <v-row>
            <v-col>Список компаний</v-col>
        </v-row>
        <v-simple-table>
            <thead>
            <tr>
                <th>Компания</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="company in companies">
                <td class="subtitle-1">{{ company.title }}</td>
                <td class="text-right">
                    <v-btn text color="teal">
                        <router-link tag="span" :to="{name: 'companies#read', params:{id: company.company_id}}">
                            Подробнее
                        </router-link>
                    </v-btn>
                </td>
            </tr>
            </tbody>
        </v-simple-table>
    </v-container>
</template>

<script>
    import {mapGetters} from 'vuex';

    export default {
        name: "Company",
        data: function () {
            return {}
        },
        computed: {
            ...mapGetters('company', {companies: 'getCompanies'})
        },
        beforeRouteEnter(from, to, next) {
            // console.log('ORDER CREAYE ROUTING')
            next(vm => {
                vm.$store.dispatch('company/loadCompanies')
            })
        }
    }
</script>

<style scoped>

</style>
