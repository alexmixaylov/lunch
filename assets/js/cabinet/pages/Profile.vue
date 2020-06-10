<template>
    <v-container>
        <!--        TODO нужно с этим что то сделать -->
        <owner v-if="isCorporate" :company_uuid="company.uuid"></owner>

        <v-card color="teal">
            <v-simple-table>
                <tbody>
                <tr v-if="!isCorporate">
                    <td>Имя</td>
                    <td>{{ user.name }}</td>
                    <td></td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td>{{ user.email }}</td>
                    <td></td>
                </tr>
                <tr>
                    <td>Телефон</td>
                    <td>{{ user.phone }}</td>
                    <td></td>
                </tr>

                <template v-if="user.type === 'employee'">
                    <template v-if="person">
                        <tr>
                            <td>Компания</td>
                            <td>{{person.title}}</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Nameplate</td>
                            <td>{{person.name}}</td>
                            <td></td>
                        </tr>
                    </template>
                    <tr v-else>
                        <td>Компания</td>
                        <td v-if="foundedCompany">{{ foundedCompany }}</td>
                        <td v-else>
                            <v-btn color="red" @click="companyLink = true">
                                Связать личный аккаунт с профилем в компании
                            </v-btn>
                        </td>
                        <td></td>
                    </tr>
                </template>

                <tr v-if="persons" v-for="person in persons">
                    <td class="title">{{ person.name }}</td>
                    <td>
                        <v-btn color="red" @click="linkPerson(person.person_id)">Связать</v-btn>
                    </td>
                </tr>
                </tbody>
            </v-simple-table>
        </v-card>
        <v-card>

        </v-card>
        <v-dialog v-model="companyLink" max-width="500">
            <v-card>
                <v-card-title>Введите идентификатор компании</v-card-title>
                <v-card-text>
                    <v-text-field v-model="uuid"></v-text-field>
                </v-card-text>
                <v-card-actions>
                    <v-btn color="orange" @click="companyLink = false">Отменить</v-btn>
                    <v-spacer></v-spacer>
                    <v-btn color="green" @click="searchCompany()">Искать&nbsp;<v-icon small>fa-search</v-icon>
                    </v-btn>
                </v-card-actions>

            </v-card>
        </v-dialog>
    </v-container>
</template>

<script>
    import {mapGetters} from "vuex";
    import Owner from "../components/Owner";

    export default {
        name: "Profile",
        components: {Owner},
        data: function () {
            return {
                user: false,
                companyLink: false,
                foundedCompany: false,
                uuid: '',
                persons: false,
                disableBtn: false
            }
        },
        computed: {
            ...mapGetters('common/company', {company: 'getCompany'}),
            ...mapGetters('person', {person: 'getPerson'}),
            isCorporate() {
                return this.user.type === 'corporate'
            },
        },
        methods: {
            searchCompany() {
                this.$store.dispatch('common/company/searchCompanyByUUID', this.uuid)
                    .then(response => {
                        console.log(response)
                        if (response.persons.length < 1) {
                            alert('Компания найдена, но администратор еще не добавил сотрудников')
                            return false
                        }
                        if (response) {
                            this.foundedCompany = response.company.title
                            this.companyLink = false
                            this.persons = response.persons
                        }
                    })
                    .catch(e => alert('Неправильный идентификатор'))
            },
            linkPerson(personId) {
                this.$store.dispatch('person/linkPersonToUser', personId).then(response => {
                    //TODO не получается сделать реактивное добавление
                    window.location.reload()

                })
            }
        },
        beforeRouteEnter(from, to, next) {
            next(vm => {
                const user = vm.$store.getters["common/user/getUser"]
                vm.user = user
                // if (user.user_id) {
                //     vm.$store.dispatch('common/company/loadCompanyByOwner', user.user_id)
                // }

                if (user.person_id) {
                    vm.$store.dispatch('person/loadPersonById', user.person_id)
                }
            })
        }
    }
</script>
