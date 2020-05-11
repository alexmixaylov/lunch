<template>
    <v-row>
        <v-container>
            <div class="d-flex justify-space-between mb-5">
                <span>{{dish.title}}</span><span>{{ dish.price }} грн. <v-icon
                    @click="updateDish()">fa-edit</v-icon></span>
            </div>
        </v-container>
    </v-row>
</template>

<script>
    export default {
        name: "Dish",
        props: ['dish', 'date', 'menu_id'],
        data: function () {
            return {

            }
        },
        computed: {
            showForm() {
                return this.$store.getters["dish/getEditingMode"]
            },
            ifExists() {
                // if dish has property DISH_ID then there is in database
                return this.dish.hasOwnProperty('dish_id')
            },
        },
        methods: {
            enableEditingMode() {
                //TODO check it
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
        watch: {
            dish() {
                if (!this.ifExists) {
                    this.showForm = true
                }

                console.log('DISH was updated !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!')
            }
        }

    }
</script>

<style scoped>

</style>
