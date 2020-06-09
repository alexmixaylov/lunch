<template>
    <v-container>
            edit
    </v-container>
</template>

<script>
    import {mapGetters} from 'vuex';
    import {encodeOrders, decodeOrders} from "../../../plugins/dishNormalizer";

    export default {
        name: "OrderEdit",
        computed:{
            ...mapGetters('common/commonOrder', {order: "getOrder"}),
        },
        beforeRouteEnter(from, to, next) {
            next(vm => {
                vm.$store.dispatch('common/commonOrder/loadOrderById', from.params.id)
            })
        }
    }
</script>

<style scoped>

</style>
