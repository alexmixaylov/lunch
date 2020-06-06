<template>
    <div>
        <form class="login" @submit.prevent="login">
            <h1>Войти</h1>
            <label>User name</label>
            <input required v-model="username" type="text" placeholder="email"/>
            <label>Password</label>
            <input required v-model="password" type="password" placeholder="пароль"/>
            <hr/>
            <button type="submit">Login</button>
        </form>
    </div>
</template>

<script>
    export default {
        name: "Login",
        data: function(){
            return {
                username: 'alexmixaylov@gmail.com',
                password: false
            }
        },
        methods: {
            login: function () {
                const { username, password } = this
                this.$store.dispatch('auth/AUTH_REQUEST', { username, password }).then(() => {
                    this.$router.push('/')
                })
            }
        }
    }
</script>

<style scoped>

</style>
