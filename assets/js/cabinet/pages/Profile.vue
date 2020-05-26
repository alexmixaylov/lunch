<template>
    <v-container>
        <v-card color="teal">
            <v-card-title>Ваши данные</v-card-title>
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
                    <td v-if="isCompany">{{ isCompany }}</td>
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
                        <v-btn color="red">Связать</v-btn>
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

    export default {
        name: "Profile",
        data: function () {
            return {
                companyLink: false,
                uuid: '',
                company: false,
                persons: false
            }
        },
        computed: {
            ...mapGetters('user', {user: 'getUser'}),
            isCompany() {
                return this.user.company ? this.user.company : this.company
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
                    this.persons = []
                    // this.$set(this.user, 'person', response)
                    this.$store.commit('user/setUserPerson', response)
                })
            }
        },
    }
</script>

<style scoped>

</style>
