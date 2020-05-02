<template>
    <div>
        <header-components/>
        <content-components/>
    </div>

</template>

<script>
    import Header from "../layouts/Header";
    import Content from "../layouts/Content";

    export default {
        name: "App",
        components: {
            'header-components':Header,
            'content-components':Content
        }
    }
</script>

<style scoped>

</style>
