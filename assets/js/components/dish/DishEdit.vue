<template>
    <v-dialog v-model="showModal" max-width="400">
        <v-card>
            <v-card-title></v-card-title>
            <v-card-text class="text--primary">
                <v-form
                        v-if="dish"
                        ref="form"
                        v-model="valid"
                >
                    <v-select
                            :items="types"
                            v-model="dish.type"
                            label="Тип блюда"
                            solo
                            :rules="typeRule"
                    ></v-select>

                    <v-text-field
                            label="Название блюда"
                            v-model="dish.title"
                            :rules="titleRule"
                            hide-details="auto"
                    ></v-text-field>

                    <v-text-field
                            type="number"
                            class="inputNumber"
                            label="Вес (грамм)"
                            v-model="dish.weight"
                            :rules="weightRule"
                    ></v-text-field>

                    <v-text-field
                            type="number"
                            class="inputNumber"
                            label="Соимость"
                            v-model="dish.price"
                            :rules="titleRule"
                    ></v-text-field>

                    <v-card-actions>
                        <v-btn v-if="!ifNew" @click="deleteDish()" text color="red">Удалить</v-btn>
                        <v-spacer></v-spacer>
                        <v-btn color="orange darken-2" @click="cancelEditing()">Отменить</v-btn>
                        <v-btn @click="submit()" color="green darken-2">Сохранить</v-btn>
                    </v-card-actions>
                </v-form>
            </v-card-text>
        </v-card>
    </v-dialog>
</template>

<script>
    import {mapGetters} from 'vuex';

    export default {
        name: "DishEdit",
        props: ['menu_id', 'dish', 'index'],
        data: function () {
            return {
                showModal: false,
                valid: false,
                types: [
                    {
                        text: 'Первое',
                        value: 'first'

                    }, {
                        text: 'Гарнир',
                        value: 'garnir'
                    }, {
                        text: 'Мясо',
                        value: 'meat'
                    },
                    {
                        text: 'Комплексное',
                        value: 'complex'
                    },
                    {
                        text: 'Салат',
                        value: 'salad'
                    },
                    {
                        text: 'Дессерт',
                        value: 'desert'
                    },
                ],
                titleRule: [
                    value => !!value || 'Обязательное поле.',
                ],
                weightRule: [
                    value => !!value || 'Обязательное поле.',
                ],
                costRule: [
                    value => !!value || 'Обязательное поле.',
                ],
                typeRule: [
                    value => !!value || 'Обязательное поле.',
                ]
            }
        },
        computed: {
            ifNew() {
                // if dish has property DISH_ID then there is in database
                return !this.dish.hasOwnProperty('dish_id')
            },
        },
        methods: {
            createDish() {
                let dish = this.dish;
                dish.menu_id = this.menu_id


                this.$store.dispatch('dish/createDish', dish).then(response => {
                    if (response.status === 200) {
                        console.log('НУЖНО ЗАПИСАТЬ СООБЩЕНИЕ ОБ УСПЕХЕ')
                        this.$set(this.dish, 'dish_id', response.data)
                        this.$emit('reset-dish-index')
                    }
                }).catch(error => {
                    console.log(error)
                })

            },
            updateDish() {
                console.log('UPDATE THIS DISH')
                this.$store.dispatch('dish/updateDish', this.dish)
                    .then((response) => {
                        console.log(response)
                        this.$emit('reset-dish-index')
                    }).catch(e => {
                    alert('Не сохранилось. Ошибка ответа сервера')
                    console.log(e)
                })
            },
            deleteDish() {
                this.$store.dispatch('dish/deleteDish', this.dish.dish_id).then(
                    this.$emit('delete-dish')
                )
            },
            submit() {
                this.validate();
                console.log('ЗАПУСТИЛИ ВАЛИДАЦИЮ')
                console.log(this.valid)
                if (this.valid) {
                    this.ifNew ? this.createDish() : this.updateDish();
                }
            },
            cancelEditing() {
                // если открыл для редактирования то уже инициализуруется NEW DISH, тогда нужно удалять
                // иначе просто сбрасываем индекс и DISH сбрасывается
                if (this.ifNew) {
                    this.$emit('delete-dish', this.index)
                } else {
                    this.$emit('reset-dish-index')
                }
            },
            validate() {
                this.$refs.form.validate()
            },
        },
        beforeMount() {
            this.showModal = true
        },
        updated() {
            this.showModal = true
        }
    }
</script>

<style scoped>

</style>
