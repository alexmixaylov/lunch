<template>
    <ul>
        <li v-for="(item, index) in withoutSelected">
            {{ index }} ->
            {{ item }}
        </li>
    </ul>

</template>

<script>
    export default {
        name: "Matrix",
        props: ['dishes'],
        data: function () {
            return {
                sets: [
                    {},
                    {}
                ],
                selectedSets: [0, 2]
            }
        },
        computed:{
            withoutSelected(){
                return this.dishes
            }
        }
    }
</script>

<style scoped>

</style>
