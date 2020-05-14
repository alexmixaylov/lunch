<template>
    <div>
        sdfdf
    </div>
</template>

<script>
    import {mapGetters} from 'vuex';

    export default {
        name: "Company",
        data: function () {
            return {}
        },
        computed: {
            ...mapGetters('company', {companies: 'getCompanies'})
        },
        beforeRouteEnter(from, to, next) {
            // console.log('ORDER CREAYE ROUTING')
            next(vm => {
                vm.$store.dispatch('company/loadCompanies')
            })
        }
    }
</script>

<style scoped>

</style>
