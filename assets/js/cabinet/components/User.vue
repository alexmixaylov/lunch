<template>
    <div>
        <template v-if="user">
            <v-list-item link>
                <v-list-item-action>
                    {{ user.name }}
                </v-list-item-action>
                <v-list-item-content>
                    <a href="/logout">
                        <v-icon>fa-sign-out-alt</v-icon>
                    </a>
                </v-list-item-content>
            </v-list-item>
        </template>
    </div>
</template>

<script>
    import {mapGetters} from "vuex";
    export default {
        name: "User",
        data: () => ({
        }),
        computed: {
            ...mapGetters('user', {user: 'getUser'}),
        }
    }
</script>

<style scoped>

</style>
