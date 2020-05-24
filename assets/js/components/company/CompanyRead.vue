<template>
    <v-container>
        <v-row>
            <v-col class="grow"><span class="title">{{company.title}}</span></v-col>
            <v-col class="text-right"><span v-if="company.owner_name">Представитель: <b @click="info">{{ company.owner_name }}</b></span></v-col>
        </v-row>

        <v-simple-table v-if="isPersons">
            <thead>
            <tr>
                <th>Сотрудник</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="person in persons">
                <td class="subtitle-1">{{ person.name }}</td>
                <td>
                    <v-btn text color="teal">
                        <!--                        <router-link tag="span" :to="{name: 'companies#read', params:{id: company.company_id}}">-->
                        <!--                            Подробнее-->
                        <!--                        </router-link>-->
                    </v-btn>
                </td>
            </tr>
            </tbody>
        </v-simple-table>
        <v-alert type="warning" v-else>Пока не добавлен ни один сотрудник</v-alert>

        <v-row>
            <v-col>
                <v-btn color="blue">
                    <router-link tag="span" :to="{name:'companies'}">
                        <v-icon small>fa-caret-left</v-icon>&nbsp;Все компании
                    </router-link>
                </v-btn>
            </v-col>
            <v-col class="text-right">
                <v-btn color="orange" @click="">
                    Добавить сотрудника&nbsp;<v-icon small>fa-plus</v-icon>
                </v-btn>
            </v-col>
        </v-row>

    </v-container>
</template>

<script>
    import {mapGetters} from 'vuex';

    export default {
        name: "CompanyRead",
        data: function () {
            return {}
        },
        computed: {
            ...mapGetters('company',
                {
                    persons: 'getPersons',
                    company: 'getCompany'
                }),
            isPersons() {
                return this.persons.length > 0
            }
        },
        methods:{
            info(){
                alert('Можно сделать ссылку на профиль представителя компании с телефоном, почтой и т.д')
            }
        },
        beforeRouteEnter(from, to, next) {
            console.log()
            next(vm => {
                vm.$store.dispatch('company/loadCompanyById', from.params.id)
                vm.$store.dispatch('company/loadPersonsByCompany', from.params.id)
            })
        }
    }
</script>

<style scoped>

</style>
