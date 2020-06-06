<template>
    <form>
        <v-text-field
                v-model="name"
                :error-messages="nameErrors"
                :counter="10"
                label="Name"
                required
                @input="$v.name.$touch()"
                @blur="$v.name.$touch()"
        ></v-text-field>

        <v-select
                v-model="select"
                :items="items"
                :error-messages="selectErrors"
                label="Item"
                required
                @change="$v.select.$touch()"
                @blur="$v.select.$touch()"
        ></v-select>


        <v-btn class="mr-4" @click="submit">submit</v-btn>
        <v-btn @click="clear">clear</v-btn>
    </form>
</template>

<script>
    export default {
        name: "OrderForm"
    }
</script>

<style scoped>

</style>
