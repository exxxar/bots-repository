<template>

    <div>
        <button
            type="button"
            data-bs-toggle="modal" data-bs-target="#refresh-user-menu"
            class="btn btn-outline-light text-primary w-100 p-3">Обновить меню пользователю</button>


        <!-- Modal -->
        <div class="modal fade" id="refresh-user-menu" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-fullscreen">
                <form v-on:submit.prevent="requestUserMenu"
                      class="modal-content">

                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Админ. панель</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="form-floating mb-3">

                            <textarea class="form-control"
                                      placeholder="Комментарий к запросу"
                                      v-model="userDataForm.info"
                                      id="bill-info" style="min-height:200px;" required></textarea>
                            <label for="bill-info" class="form-label">Комментарий</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button
                            :disabled="loading"
                            type="submit"
                            class="btn btn-primary w-100 p-3">
                            Отправить
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
            userDataForm: {
                info: null,
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
        requestUserMenu() {
            this.loading = true;
            this.$store.dispatch("requestRefreshMenu", {
                dataObject: {
                    user_telegram_chat_id: this.botUser.telegram_chat_id,
                    ...this.userDataForm
                }
            }).then((resp) => {
                this.loading = false
                this.userDataForm.info = null

                this.$notify({
                    title: 'Отлично!',
                    text: 'Вы успешно отправили запрос на обновление меню!',
                    type: 'error'
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
    }
}
</script>
