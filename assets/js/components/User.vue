<template>
    <div>
        <template v-if="isAuth">
            <v-list-item link>
                <v-list-item-action>
                    Мама Олега
                </v-list-item-action>
                <v-list-item-content>
                    <v-icon>fa-sign-out-alt</v-icon>
                </v-list-item-content>
            </v-list-item>
        </template>

        <template v-else>
            <v-list-item link>
                <v-list-item-action>
                    Войти
                </v-list-item-action>
                <v-list-item-content>
                    <v-icon>fa-sign-in-alt</v-icon>
                </v-list-item-content>
            </v-list-item>
        </template>
    </div>
</template>

<script>
    export default {
        name: "User",
        data: () => ({
            isAuth: true
        })
    }
</script>

<style scoped>

</style>
