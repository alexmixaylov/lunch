<template>
    <v-row>
        <v-container>
            <div class="d-flex justify-space-between mb-5">
                <span>{{dish.title}}</span>
                <span>{{ dish.price }} грн. <v-icon @click="editDish()">fa-edit</v-icon></span>
            </div>
        </v-container>
    </v-row>
</template>

<script>
    export default {
        name: "DishTeaser",
        props: ['dish', 'index'],
        data: function () {
            return {}
        },
        computed: {},
        methods: {
            editDish() {
                this.$emit('edit-dish', this.index)
            }
        }
    }
</script>

<style scoped>

</style>
