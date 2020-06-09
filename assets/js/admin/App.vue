<template>
    <v-app id="inspire">
        <header-components></header-components>
        <content-components>
            <router-view></router-view>
        </content-components>
        <v-footer app>
            <span>&copy; 2020</span>
        </v-footer>
    </v-app>
</template>

<script>
    import Header from "./layouts/Header";
    import Content from "../common/layouts/Content";

    export default {
        name: "App",
        components: {
            'header-components': Header,
            'content-components': Content
        },
        props: {
            source: String,
        },

        data: () => ({
            drawer: null,
            user: false
        }),

        created() {
            // можно здесь поменять тему а можно в самом плагине
            this.$vuetify.theme.dark = true
            // this.$vuetify.lang.current = 'ru'
            // console.log(this.$vuetify)
        },
        beforeMount() {
            const userData = window.document.getElementById('user').dataset
            let user = {}
            Object.entries(userData).map(param => {
                const key = param[0];
                user[key] = (key !== 'roles') ? param[1] : JSON.parse(param[1])
            });
            console.log('USER COMMIT', user)
            this.$store.commit('user/setUser', user)
        }
    }
</script>

<style scoped>

</style>
