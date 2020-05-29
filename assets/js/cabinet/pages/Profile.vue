<template>
    <v-container>
        <owner v-if="isManager" :company_uuid="companyOwner.uuid"></owner>

        <v-card color="teal">
            <v-card-title>Представитель компании</v-card-title>
            <v-simple-table>
                <tbody>
                <tr>
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

                <tr>
                    <td>Компания</td>
                    <td v-if="isCompany">{{ user.company }}</td>
                    <td v-else>
                        <v-btn color="red" @click="companyLink = true">
                            Связать с компанией
                        </v-btn>
                    </td>
                    <td></td>
                </tr>

                <tr v-if="persons" v-for="person in persons">
                    <td class="title">{{ person.name }}</td>
                    <td>
                        <v-btn color="red" @click="linkPerson(person.person_id)">Связать</v-btn>
                    </td>
                </tr>

                <tr v-if="user.company">
                    <td>Nameplate</td>
                    <td v-if="person">{{person}}</td>
                    <td v-else>
                        <v-btn color="red" :disabled="disableBtn">Связать</v-btn>
                    </td>
                    <td></td>
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
                uuid: '',
                company: false,
                persons: false,
                disableBtn: false
            }
        },
        computed: {
            ...mapGetters('user', {companyOwner: 'getCompany'}),
            isCompany() {
                return this.user.company ? this.user.company : this.company
            },
            isManager() {
                return this.user.type === 'manager'
            },
            person() {
                return this.user.person
            }
        },
        methods: {
            searchCompany() {
                this.$store.dispatch('user/searchCompanyByUUID', this.uuid)
                    .then(response => {
                        console.log(response)
                        if (response.persons.length < 1) {
                            alert('Компания найдена, но администратор еще не добавил сотрудников')
                            return false
                        }
                        if (response) {
                            this.company = response.company.title
                            this.companyLink = false
                            this.persons = response.persons
                        }
                    })
                    .catch(e => alert('Неправильный идентификатор'))
            },
            linkPerson(personId) {
                this.$store.dispatch('user/linkPersonToUser', personId).then(response => {
                    //TODO не получается сделать реактивное добавление
                    window.location.reload()

                })
            }
        },
        beforeRouteEnter(from, to, next) {
            next(vm => {
                const user = vm.$store.getters["user/getUser"]
                vm.user = user
                vm.$store.dispatch('user/loadCompanyByOwner', user.user_id)
            })
        }
    }
</script>
