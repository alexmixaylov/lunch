<template>
    <v-app id="inspire">
        <header-components></header-components>
        <content-components></content-components>
        <v-footer app>
            <span>&copy; 2020</span>
        </v-footer>
    </v-app>
</template>

<script>
    import Header from "../layouts/Header";
    import Content from "../layouts/Content";

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
        }),

        created() {
            // можно здесь поменять тему а можно в самом плагине
            // this.$vuetify.theme.dark = false
            // this.$vuetify.theme.themes.dark.primary = '#FB8C00';
        },
    }
</script>

<style scoped>

</style>
