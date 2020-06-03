<template>
    <div>
        <v-btn color="red" @click="showChoosePerson = true">{{buttonText}}</v-btn>
        <v-dialog v-model="showChoosePerson" persistent max-width="500">
            <v-card>
                <v-card-title class="teal">
                    Вы может сохранить себя в куках чтоб каждый раз не выбирать свое имя
                </v-card-title>
                <v-card-text class="" v-for="(person, index) in persons" :key="person.person_id">
                    <v-btn text @click="savePersonToStore(person)">{{ person.name }}</v-btn>
                </v-card-text>
                <v-card-actions>
                    <v-btn color="red" @click="cleanLocalStorage">Очистить куки</v-btn>
                    <v-btn color="orange" @click="savePersonToLocalStorage">Сохранить в куках</v-btn>
                    <v-spacer></v-spacer>
                    <v-btn color="green" @click="showChoosePerson = false">Только сейчас</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
    import {mapGetters} from 'vuex';

    export default {
        name: "Storage",
        data: function () {
            return {
                showChoosePerson: false
            }
        },
        computed: {
            ...mapGetters('person', {
                person: 'getPerson',
                persons: 'getPersons'
            }),
            buttonText() {
                return this.person.name ?? 'хелло, Мистер Никто'
            }
        },
        methods: {
            savePersonToStore(person) {
                this.$store.commit('person/setPerson', person)
            },
            savePersonToLocalStorage() {
                localStorage.person = JSON.stringify(this.person)
                this.showChoosePerson = false
            },
            cleanLocalStorage(){
                localStorage.removeItem('person')
                this.showChoosePerson = false
            }

        },
        mounted() {
            if (localStorage.person) {
                this.$store.commit('person/setPerson', JSON.parse(localStorage.person))
            }
        },
    }
</script>

<style scoped>

</style>
