<template>
    <v-card class="mxauto">
        <v-container v-if="!showForm">
            <div class="d-flex justify-space-between mb-5">
                <span>{{dish.title}}</span><span>{{ dish.price }} грн. <v-icon
                    @click="enableEditingMode()">fa-edit</v-icon></span>
            </div>
        </v-container>
        <v-form v-else>
            <v-text-field
                    label="Тип блюда"
                    v-model="dish.type"
                    :rules="titleRule"
                    hide-details="auto"
            ></v-text-field>

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
                <v-btn v-if="ifExists" @click="deleteDish()" text color="red">Удалить</v-btn>
                <v-spacer></v-spacer>
                <v-btn text color="orange" @click="editingMode = false">Отменить</v-btn>
                <v-btn @click="submit()" color="green accent-4">Сохранить</v-btn>
            </v-card-actions>
        </v-form>
    </v-card>
</template>

<script>
    export default {
        name: "Dish",
        props: ['dish', 'date', 'menu_id'],
        data: function () {
            return {
                title: null,
                price: null,
                weight: null,
                type: null,
                editingMode: false,
                titleRule: [
                    value => !!value || 'Обязательное поле.',
                ],
                weightRule: [
                    value => !!value || 'Обязательное поле.',
                ],
                costRule: [
                    value => !!value || 'Обязательное поле.',
                ],
            }
        },
        computed: {
            // editingMode() {
            //     return this.$store.getters["dish/getEditingMode"]
            // },
            ifExists() {
                // if dish has property DISH_ID then there is in database
                return this.dish.hasOwnProperty('dish_id')
            },
            showForm() {
                if (!this.ifExists) {
                    return true;
                }
                return this.editingMode
            }
        },
        methods: {
            enableEditingMode() {
                this.editingMode = true
                this.$store.commit('dish/enableEditingMode')
            },
            createDish() {
                console.log('CREATE NEW DISH')
                let dish = this.dish;
                dish.menu_id = this.menu_id
                this.$store.dispatch('dish/createDish', dish).then(response => {
                    if (response.status === 200) {
                        console.log('НУЖНО ЗАПИСАТЬ СООБЩЕНИЕ ОБ УСПЕХЕ')
                        this.$set(this.dish, 'dish_id', response.data)
                        this.editingMode = false
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
                        this.editingMode = false
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
                return this.ifExists ? this.updateDish() : this.createDish()
            }
        },
        watch:{
            dish(){
                console.log('DISH was updated !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!')
            }
        }

    }
</script>

<style scoped>

</style>
