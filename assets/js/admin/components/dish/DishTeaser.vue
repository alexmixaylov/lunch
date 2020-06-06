<template>
    <div class="alex-row">
        <div>{{dish.title}}</div>
        <div class="alex-row-end">{{ dish.price }} грн.
            <v-icon @click="editDish()">fa-edit</v-icon>
        </div>
    </div>
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
    .alex-row {
        display: flex;
        justify-content: space-between;
        padding: 12px 15px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    }
    .alex-row:last-of-type{
        border: none;
    }
    .alex-row-end{
        text-align: end;
    }
    .alex-row:hover {
        background-color: rgba(255, 255, 255, 0.2);
    }
</style>
