<template>
    <v-card class="mxauto">

        <v-container>
            <v-text-field
                    label="Название блюда"
                    v-model="title"
                    :rules="titleRule"
                    hide-details="auto"
            ></v-text-field>

            <v-text-field
                    type="number"
                    class="inputNumber"
                    label="Вес (грамм)"
                    v-model="weight"
                    :rules="weightRule"
            ></v-text-field>

            <v-text-field
                    type="number"
                    class="inputNumber"
                    label="Соимость"
                    v-model="cost"
                    :rules="titleRule"
            ></v-text-field>

<!--            <v-card-actions>-->
<!--                <v-spacer></v-spacer>-->
<!--                <v-btn text color="orange">Отменить</v-btn>-->
<!--                <v-btn text color="green accent-4">Сохранить</v-btn>-->
<!--            </v-card-actions>-->
        </v-container>
    </v-card>
</template>

<script>
    export default {
        name: "Dish",
        data: function () {
            return {
                title: null,
                cost: null,
                weight: null,
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
        }
    }
</script>

<style scoped>

</style>
