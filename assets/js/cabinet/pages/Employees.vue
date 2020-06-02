<template>
    <v-container v-if="user.type === 'corporate'">
        <v-simple-table>
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
                    <v-btn text color="" @click="tryEditPerson(index)">
                        <v-icon small>fa-edit</v-icon>
                    </v-btn>
                    <v-btn @click="tryDeletePerson(index)" text color="red">
                        <v-icon small>fa-trash-alt</v-icon>
                    </v-btn>
                </td>
            </tr>
            </tbody>
        </v-simple-table>

        <v-row>
            <v-col class="text-right">
                <v-btn color="orange" @click="tryCreatePerson()">
                    Добавить сотрудника&nbsp;<v-icon small>fa-plus</v-icon>
                </v-btn>
            </v-col>
        </v-row>

        <v-dialog max-width="500" v-model="actionWithPerson">
            <v-card>
                <v-card-title>
                    <span v-if="person">{{person.name}}</span>
                    <span v-else>Новый Сотрудник</span>
                </v-card-title>
                <v-card-text>
                    <v-form v-model="valid">
                        <v-text-field
                                v-model="name"
                                :rules="nameRules"
                                :counter="20"
                                label="Имя сотрудника"
                                required
                                autofocus
                        ></v-text-field>
                    </v-form>
                </v-card-text>

                <v-card-actions>
                    <v-btn @click="actionWithPerson = false" color="orange">Отменить</v-btn>
                    <v-spacer></v-spacer>
                    <v-btn v-if="person" @click="updatePerson" color="green">Сохранить</v-btn>
                    <v-btn v-else @click="createPerson" color="green">Добавить</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-dialog v-model="confirmDialog" persistent max-width="290">
            <v-card>
                <v-card-title class="headline">Уверены что хотите удалить <b class="userName">{{person.name}}</b> ?
                </v-card-title>
                <v-card-text>Это действие нельзя отменить</v-card-text>
                <v-card-actions>
                    <v-btn color="green darken-1" @click="confirmDialog = false">Передумал</v-btn>
                    <v-spacer></v-spacer>
                    <v-btn color="red darken-1" @click="deletePerson()">Конечно</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-container>
</template>

<script>
    import {mapGetters} from 'vuex';

    export default {
        name: "Employees",
        data: function () {
            return {
                actionWithPerson: false,
                personIndex: false,
                name: '',
                valid: true,
                nameRules: [
                    v => !!v || 'Имя обязательно',
                    v => v.length <= 20 || 'Должно быть меньше 20 символов',
                ],
                confirmDialog: false
            }
        },
        computed: {
            ...mapGetters('user', {user: 'getUser'}),
            ...mapGetters('company', {company: 'getCompany'}),
            ...mapGetters('person', {persons: 'getPersons'}),
            person() {
                return this.personIndex !== false ? this.persons[this.personIndex] : false
            }
        },
        methods: {
            checkUniqName() {
                const isNotUniq = this.persons.find(person => {
                    return person.name === this.name
                });
                console.log(isNotUniq)
                if (isNotUniq) {
                    alert('У вас уже есть сотрудник с таким именем');
                    return false;
                }
                return true;
            },
            tryCreatePerson() {
                this.actionWithPerson = true
                this.personIndex = false
            },
            createPerson() {
                if (!this.checkUniqName()) {
                    return false
                }
                const params = {
                    company_id: this.user.related_company_id,
                    name: this.name
                }
                if (this.valid) {
                    this.$store.dispatch('person/createPerson', params).then(response => {
                        this.name = ''
                        this.actionWithPerson = false
                    })
                }
            },
            tryEditPerson(index) {
                this.personIndex = index
                this.actionWithPerson = true
            },
            updatePerson() {
                if (!this.checkUniqName()) {
                    return false
                }
                const person = {name: this.name, person_id: this.person.person_id}
                this.$store.dispatch('person/updatePerson', person)
                    .then(response => {
                        this.actionWithPerson = false
                        this.$store.commit('person/updatePerson', {index: this.personIndex, person})
                        this.personIndex = false
                        this.name = ''
                    })
            },
            tryDeletePerson(index) {
                this.personIndex = index
                this.confirmDialog = true
            },
            deletePerson() {
                this.$store.dispatch('person/deletePerson', this.person.person_id)
                    .then(response => {
                        this.$store.commit('person/deletePersonByIndex', this.personIndex)
                        this.personIndex = false
                        this.confirmDialog = false
                    })
            }
        },
        beforeRouteEnter(from, to, next) {
            next(vm => {
                const user = vm.user;
                if (user.type === "corporate") {
                    vm.$store.dispatch('person/loadPersonsByCompany', user.related_company_id)
                } else {
                    vm.$router.push({name: 'profile'})
                }

            })
        }
    }
</script>

<style scoped>
    .userName {
        text-transform: uppercase;
    }
</style>
