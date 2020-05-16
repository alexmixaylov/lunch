<template>
    <v-dialog
            v-model="dialog"
            hide-overlay
            persistent
            width="300"
    >
        <v-card
                color="teal"
                dark
        >
            <v-card-text>
                Пожалуйста подождите
                <v-progress-linear
                        indeterminate
                        color="white"
                        class="mb-0"
                ></v-progress-linear>
            </v-card-text>
        </v-card>
    </v-dialog>
</template>

<script>
    export default {
        name: "Loading",
        props: ['dialog'],
        data: function () {
            return {

            }
        }
    }
</script>

<style scoped>

</style>
