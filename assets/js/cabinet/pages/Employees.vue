<template>
    <div>Employees</div>
</template>

<script>
    import {mapGetters} from 'vuex';

    export default {
        name: "Employees",
        computed:{
            ...mapGetters('user', {user: 'getUser'}),
        },
        beforeRouteEnter(from, to, next) {
            console.log()
            next(vm => {
                vm.$store.dispatch('person/loadPersonsByCompany', vm.user.company_id)
            })
        }
    }
</script>

<style scoped>

</style>
