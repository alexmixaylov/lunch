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
            ...mapGetters('common/user', {user: 'getUser'}),
        },
        beforeMount() {
            if (this.user.type === 'corporate' &&  this.user.person_id !== ""){
                console.log('CHECK USER', this.user)
                // this.$store.dispatch('person/loadPersonById', this.user.person_id)
            }
        }
    }
</script>

<style scoped>

</style>
