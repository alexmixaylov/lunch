<template>
    <div v-if="user.type === 'corporate'">

        <p>Не должнен увидеть частник</p>
        <p>Вначале менеджер должен добавить всех сотрудников
            После чего сотрудник может зарегистрировать себе личный аккаунт и связать его с раннее созданым профилем</p>
        <p>Заказы создаются на Профиль а не на юзера</p>

    </div>
</template>

<script>
    import {mapGetters} from 'vuex';

    export default {
        name: "Employees",
        computed:{
            ...mapGetters('user', {user: 'getUser'}),
        },
        beforeRouteEnter(from, to, next) {


            next(vm => {
                const user = vm.user;
                if (user.type === "corporate") {
                    vm.$store.dispatch('person/loadPersonsByCompany', user.related_company_id)
                } else {
                    vm.$router.push({name:'profile'})
                }

            })
        }
    }
</script>

<style scoped>

</style>
