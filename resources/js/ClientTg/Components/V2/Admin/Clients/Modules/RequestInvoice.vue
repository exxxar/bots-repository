<template>

    <div>
        <button
            type="button"
            data-bs-toggle="modal" data-bs-target="#request-invoice"
            class="btn btn-outline-light text-primary w-100 p-3">Запрос
            на оплату</button>


        <div class="modal fade" id="request-invoice" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-fullscreen">
                <form v-on:submit.prevent="sendInvoice"
                      class="modal-content">

                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Админ. панель</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="form-floating mb-3">

                            <input class="form-control"
                                   type="number"
                                   min="100"
                                   step="50"
                                   placeholder="100"
                                   v-model="invoiceForm.amount"
                                   id="bill-amount" required/>
                            <label for="bill-amount" class="form-label">Значение на оплату, руб</label>
                        </div>
                        <div class="form-floating mb-3">

                            <textarea class="form-control"
                                      placeholder="Текст запроса"
                                      v-model="invoiceForm.info"
                                      id="bill-info" style="min-height:200px;" required></textarea>
                            <label for="bill-info" class="form-label">Введите сообщение для пользователя</label>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button
                            :disabled="loading"
                            type="submit"
                            class="btn btn-primary w-100 p-3">
                            Отправить запрос
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
            invoiceForm: {
                amount: 100,
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
        sendInvoice() {
            this.loading = true;
            this.$store.dispatch("sendInvoice", {
                dataObject: {
                    user_telegram_chat_id: this.botUser.telegram_chat_id,
                    ...this.invoiceForm
                }
            }).then((resp) => {
                this.loading = false
                this.invoiceForm.info = null
                this.$emit("callback")

                this.$notify({
                    title: 'Отлично!',
                    text: "Вы успешно отправили пользователю запрос на оплату",
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
    }
}
</script>
