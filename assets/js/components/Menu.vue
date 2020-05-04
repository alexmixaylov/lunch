<template>
    <v-card class="mxauto mt-10" width="90%">

        <v-app-bar
                dark
                color="#385F73"
        >
            <v-toolbar-title>{{day}} {{month}}</v-toolbar-title>

            <v-spacer></v-spacer>
            <router-link :to="{name: 'menu',params: {id: 1}}" tag="button"><v-icon>fa-edit</v-icon></router-link>

        </v-app-bar>

        <v-container>
                        <router-view></router-view>
<!--            <dish-component></dish-component>-->

        </v-container>
    </v-card>
</template>
<script>
    import Dish from "./Dish";

    export default {
        name: "Menu",
        props: ['day', 'month'],
        components: {
            'dish-component': Dish
        },
        data: function () {
            return {
                status: false
            }
        }
    }
</script>

<style scoped>
    .inputNumber input[type='number'] {
        -moz-appearance: textfield;
    }

    .inputNumber input::-webkit-outer-spin-button,
    .inputNumber input::-webkit-inner-spin-button {
        -webkit-appearance: none;
    }

    .inputNumber {
        display: inline-block;
        width: 40%;
    }
</style>
