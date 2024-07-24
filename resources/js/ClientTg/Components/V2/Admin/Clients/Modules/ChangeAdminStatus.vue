<template>

    <div>
        <button
            type="button"
            data-bs-toggle="modal" data-bs-target="#change-admin-status"
            class="btn btn-outline-light text-primary w-100 p-3">Изменить статус администратора
        </button>


        <!-- Modal -->
        <div class="modal fade" id="change-admin-status" tabindex="-1" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-fullscreen">
                <form v-on:submit.prevent="changeStatus"
                      class="modal-content">

                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Админ. панель</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">

                        <p>
                            Вы хотите  <span v-if="botUser.is_admin">удалить из администраторов</span>
                            <span v-else>назначить администратором</span>
                            пользователя
                            <strong class="text-primary fw-bold"> {{ botUser.fio_from_telegram || botUser.telegram_chat_id || '-' }}</strong>
                        </p>
                        <div class="form-floating mb-3">

                            <textarea class="form-control"
                                      placeholder="Информация"
                                      v-model="adminForm.info"
                                      style="min-height:300px;"
                                      id="bill-info"  required></textarea>
                            <label for="bill-info" class="form-label">
                                <span v-if="botUser.is_admin">Причина удаления администратора</span>
                                <span v-else>Причина добавления администратора</span>
                            </label>
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button
                            :disabled="loading"
                            type="submit"
                            class="btn btn-primary w-100 p-3">
                            Подтвердить
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</template>
<script>
import {mapGetters} from "vuex";

export default {
    props: ["botUser"],
    data() {
        return {
            loading: false,
            adminForm: {
                info: null
            },
        }
    },
    computed: {
        ...mapGetters(['getSelf', 'getCashBack',
            'getCashBackPaginateObject']),
        currentBot() {
            return window.currentBot
        }

    },
    methods: {
        changeStatus() {
            this.loading = true;
            this.$store.dispatch(!this.botUser.is_admin ? "addAdmin" : "removeAdmin", {
                dataObject: {
                    user_telegram_chat_id: this.botUser.telegram_chat_id,
                    ...this.adminForm
                }
            }).then((resp) => {
                this.loading = false
                this.adminForm.info = null

                this.$emit("callback")

                this.$notify({
                    title: 'Отлично!',
                    text: !this.botUser.is_admin ?
                        "Вы успешно назначили пользователя администратором" :
                        "Вы успешно разжаловали администратора до уровня пользователя",
                    type: 'success'
                })
            }).catch(() => {
                this.loading = false
                this.$notify({
                    title: 'Упс...!',
                    text: 'Ошибочка...',
                    type: 'error'
                })
            })
        },

    },

}
</script>
