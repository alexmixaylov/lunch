<template>
    <v-container>
        <v-row>
            <v-col class="grow"><span class="title">{{company.title}}</span></v-col>
            <v-col class="text-right"><span v-if="company.owner_name">Представитель: <b @click="info">{{ company.owner_name }}</b></span>
            </v-col>
        </v-row>

        <v-simple-table v-if="isPersons">
            <thead>
            <tr>
                <th>Сотрудник</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(person, index) in persons">
                <td class="subtitle-1">{{ person.name }}</td>
                <td class="text-right">
                    <v-btn text color="" @click="editPerson(index)">
                        <v-icon small>fa-edit</v-icon>
                    </v-btn>
                    <v-btn @click="tryDelete(index)" text color="red">
                        <v-icon small>fa-trash-alt</v-icon>
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
                <v-btn color="orange" @click="newPerson = true">
                    Добавить сотрудника&nbsp;<v-icon small>fa-plus</v-icon>
                </v-btn>
            </v-col>
        </v-row>
        <v-row>
            <v-col class="text-right">
                <v-btn color="red" @click="confirmDeleteCompany = true">
                    Удалить компанию &nbsp;<v-icon x-small>fa-minus</v-icon>
                </v-btn>
            </v-col>
        </v-row>
        <v-dialog max-width="500" v-model="newPerson">
            <v-card>
                <v-card-title>
                    Новый Сотрудник
                </v-card-title>
                <v-card-text>
                    <v-form v-model="valid">
                        <v-text-field
                                v-model="name"
                                :rules="nameRules"
                                :counter="10"
                                label="Имя сотрудника"
                                required
                        ></v-text-field>
                    </v-form>
                </v-card-text>

                <v-card-actions>
                    <v-btn @click="newPerson = false" color="orange">Отменить</v-btn>
                    <v-spacer></v-spacer>
                    <v-btn @click="createPerson" color="green">Добавить</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-dialog max-width="500" v-model="showPerson" v-if="selectedPerson">
            <v-card>
                <v-card-title>
                    Ссылка на профиль если есть
                </v-card-title>
                <v-card-text>
                    <v-text-field
                            v-model="selectedPerson.name"
                            :rules="nameRules"
                            :counter="10"
                            label="Имя сотрудника"
                            required
                    ></v-text-field>
                </v-card-text>
                <v-card-actions>
                    <v-btn @click="showPerson = false" color="orange">Отменить</v-btn>
                    <v-spacer></v-spacer>
                    <v-btn @click="updatePerson()" color="green">Сохранить</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-dialog v-model="confirmDialog" persistent max-width="290">
            <v-card>
                <v-card-title class="headline">Уверены что хотите удалить человека :) ?</v-card-title>
                <v-card-text>Это действие нельзя отменить</v-card-text>
                <v-card-actions>
                    <v-btn color="green darken-1" @click="confirmDialog = false">Передумал</v-btn>
                    <v-spacer></v-spacer>
                    <v-btn color="red darken-1" @click="deletePerson()">Конечно</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-dialog v-model="confirmDeleteCompany" persistent max-width="500">
            <v-card>
                <v-card-title class="headline">Уверены что хотите удалить компанию?</v-card-title>
                <v-card-text>Это действие нельзя отменить</v-card-text>
                <v-card-text>Если у когото из компании есть активные заказы, вы не сможет удалить пока не отмените или
                    заархивируете заказы
                </v-card-text>
                <v-card-actions>
                    <v-btn color="green darken-1" @click="confirmDeleteCompany = false">Передумал</v-btn>
                    <v-spacer></v-spacer>
                    <v-btn color="red darken-1" @click="deleteCompany()">Конечно</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-container>
</template>

<script>
    import {mapGetters} from 'vuex';

    export default {
        name: "CompanyRead",
        data: function () {
            return {
                newPerson: false,
                showPerson: false,
                personIndex: false,
                confirmDialog: false,
                confirmDeleteCompany: false,
                valid: true,
                name: '',
                nameRules: [
                    v => !!v || 'Имя обязательно',
                    v => v.length <= 10 || 'Должно быть меньше 10 символов',
                ],
            }
        },
        computed: {
            ...mapGetters('company',
                {
                    persons: 'getPersons',
                    company: 'getCompany'
                }),
            isPersons() {
                return this.persons.length > 0
            },
            selectedPerson() {
                return this.persons[this.personIndex]
            }
        },
        methods: {
            info() {
                alert('Можно сделать ссылку на профиль представителя компании с телефоном, почтой и т.д')
            },
            createPerson() {
                const params = {company_id: this.company.company_id, name: this.name}
                if (this.valid) {
                    this.$store.dispatch('company/createPerson', params).then(response => {
                        if (response === 200) {
                            this.name = ''
                            this.newPerson = false
                        }
                    }).catch(response => console.log(response))
                }

            },
            editPerson(index) {
                this.personIndex = index
                this.showPerson = true
            },
            updatePerson() {
                this.$store.dispatch('company/updatePerson', this.selectedPerson)
                    .then(response => {
                        this.showPerson = false
                    })
            },
            tryDelete(index) {
                this.personIndex = index
                this.confirmDialog = true
            },
            deletePerson() {
                this.$store.dispatch('company/deletePerson', this.selectedPerson.person_id).then(response => {
                    this.$store.commit('company/deletePersonByIndex', this.personIndex)
                    this.confirmDialog = false
                }).catch(e => {
                    console.log(e)
                    alert('У этого человека активные заказы. Или он привязал свой профиль к компании.')
                })
            },
            deleteCompany() {
                this.$store.dispatch('company/deleteCompany', this.company.company_id).then(status => {
                    status === 200 ? this.$router.push({name: 'companies'}) : false
                }).catch(e => alert('Вы не можете пока удалить эту компанию'))
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
