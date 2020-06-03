<template>
    <header>
        <v-navigation-drawer v-model="drawer" app clipped>
            <v-list dense>
                <v-list-item link>
                    <v-list-item-action>
                        <v-icon>fa-utensils</v-icon>
                    </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title>
                            <router-link :to="{name: 'main'}">Меню</router-link>
                        </v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item link>
                    <v-list-item-action>
                        <v-icon>fa-address-book</v-icon>
                    </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title>
                            <router-link :to="{name:'orders'}">Заказы</router-link>
                        </v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item link>
                    <v-list-item-action>
                        <v-icon>fa-user-tie</v-icon>
                    </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title>
                            <router-link :to="{name: 'profile'}">Профиль <span v-if="isCorporate">компании</span>
                            </router-link>
                        </v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item link v-if="isCorporate">
                    <v-list-item-action>
                        <v-icon>fa-users</v-icon>
                    </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title>
                            <router-link :to="{name: 'employees'}">Сотрудники</router-link>
                        </v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
            </v-list>

            <div class="full-height">
                <local-storage/>
            </div>
        </v-navigation-drawer>
        <v-app-bar
                app
                clipped-left
        >
            <v-app-bar-nav-icon @click.stop="drawer = !drawer"/>
            <v-toolbar-title>ВИШНЕВИЙ САД</v-toolbar-title>

            <v-spacer></v-spacer>
            <user-component></user-component>
        </v-app-bar>
    </header>
</template>

<script>
    import {mapGetters} from 'vuex';
    import User from "../components/User";
    import Storage from "../components/Storage";

    export default {
        name: "Header",
        components: {
            'user-component': User,
            'local-storage': Storage
        },
        data: () => ({
            drawer: null,
        }),
        computed: {
            ...mapGetters('user', {user: 'getUser'}),
            isCorporate() {
                return this.user.type === 'corporate'
            }
        },
    }
</script>

<style>

</style>
