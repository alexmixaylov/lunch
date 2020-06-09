<template>
    <v-app id="inspire">
        <header-components></header-components>
        <content-components></content-components>
        <v-footer app>
            <a class="text--disabled" href="https://cherrygarden.lviv.ua/"><span>&copy; Cherry Garden 2020</span></a>
            <v-spacer></v-spacer>
            <a class="text--secondary" href="https://cherrygarden.lviv.ua/"><span>Food Service CRM</span></a>
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
            // получаем всю информацию о пользователе в шаблоне симфони
            const userData = window.document.getElementById('user').dataset
            let user = {}
            Object.entries(userData).map(param => {
                const key = param[0];
                user[key] = (key !== 'roles') ? param[1] : JSON.parse(param[1])
            });

            this.$store.commit('user/setUser', user)

            // если уже привязана компания, или частник - загружаем данные о компании
            console.log('GET USER IN APP COMPONET', user)

            switch (user.type) {
                case 'corporate':
                    if (user.related_company_id) {
                        // related_company_id - Relation OneToONe - only for corporate company account
                        // if user has personal account related with company profile or private type of account it param must be empty
                        this.$store.dispatch('common/company/loadCompanyByOwner', user.user_id)
                        this.$store.dispatch('person/loadPersonsByCompany', user.related_company_id)
                    } else {
                        alert('Вы зарегистрировали аккаунт компании. Теперь добавьте название вашей компани в профиль для того чтобы продолжить работу')
                        this.$router.push({name: 'profile'})
                    }
                    break;
                case 'employee':
                    if (user.person_id === "") {
                        alert('Вы зарегистрировались как "Работник компании". Свяжите свой профиль с компанией для того чтобы продолжить работу')
                        this.$router.push({name: 'profile'})
                    }
                    break;
                case 'private':
                    this.$store.dispatch('person/loadPersonByUserId', user.user_id)
                    break;

                default:
                    break;

            }


        }
    }
</script>
