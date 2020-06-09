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

        <v-row>
            <v-spacer></v-spacer>
            <v-col class="text-right">
                <v-btn color="orange" @click="isNewCompany = true">Добавить компанию &nbsp;<v-icon small>fa-plus
                </v-icon>
                </v-btn>
            </v-col>
        </v-row>

        <v-dialog v-model="isNewCompany" max-width="500">
            <v-card>
                <v-card-title>
                    Новая компания
                </v-card-title>
                <v-card-text>
                    <v-text-field v-model="title" placeholder="Название Компании"></v-text-field>
                </v-card-text>
                <v-card-actions>
                    <v-btn color="orange" @click="isNewCompany = false">Отменить</v-btn>
                    <v-spacer></v-spacer>
                    <v-btn color="green" @click="createCompany()">Создать</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-container>
</template>

<script>
    import {mapGetters} from 'vuex';

    export default {
        name: "Company",
        data: function () {
            return {
                isNewCompany: false,
                title: '',
                confirmDialog: false,
            }
        },
        computed: {
            ...mapGetters('common/company', {companies: 'getCompanies'})
        },
        methods: {
            createCompany() {
                const params = {
                    title: this.title,
                    owner: null
                }
                this.$store.dispatch('common/company/createCompany', params).then(response => {
                    this.isNewCompany = false
                    this.title = ''
                })
            },
            deleteCompany() {

            }
        },
        beforeRouteEnter(from, to, next) {
            // console.log('ORDER CREAYE ROUTING')
            next(vm => {
                vm.$store.dispatch('common/company/loadCompanies')
            })
        }
    }
</script>

<style scoped>

</style>
