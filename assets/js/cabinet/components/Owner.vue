<template>
    <div>
        <v-alert v-if="uuid" type="warning">Ваш ID: {{ uuid }}</v-alert>
        <v-card class="mb-5" v-else>
        <v-card-title class="orange">
                Добавьте вашу компанию
            </v-card-title>

            <v-card-text>
                После добавления вы получите уникальный код, который сможете сообщать сотрудникам если они захотят
                зарегистрировать личный профиль
            </v-card-text>

            <v-card-subtitle>
                <v-text-field v-model="title" placeholder="Название компании"></v-text-field>
            </v-card-subtitle>

            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="green" @click="createCompany">Добавить</v-btn>
            </v-card-actions>
            <v-dialog v-model="success">
                <v-alert type="warning"><b class="title red d-inline-block pa-2">{{ uuid }}</b>
                    это уникальный идентификатор вашей компании. Используйте его чтобы связать ЛИЧНЫЙ профиль с
                    аккаунтом
                    компании
                </v-alert>
            </v-dialog>
        </v-card>

    </div>

</template>

<script>
    export default {
        name: "Owner",
        props: ['company_uuid'],
        data: function () {
            return {
                title: '',
                success: false,
                newUUID: false
            }
        },
        computed:{
            uuid(){
                return this.company_uuid ? this.company_uuid : this.newUUID
            }
        },
        methods: {
            createCompany() {
                this.$store.dispatch('user/createCompany', this.title).then(response => {
                    this.success = true
                    this.newUUID = response
                }).catch(e => {
                    alert('Похоже что компания с таким название уже добавлена')

                })
            }
        }
    }
</script>

<style scoped>

</style>
