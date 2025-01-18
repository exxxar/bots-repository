<script setup>

</script>
<template>
    <div class="container py-3">
        <h6><i class="fa-solid fa-money-bill-transfer"></i> Генерация ссылок на оплату</h6>
        <form v-on:submit.prevent="submit">

            <div class="form-floating mb-2">
                <input type="number"
                       min="0"
                       v-model="linkForm.amount"
                       required
                       class="form-control" id="floatingInput" placeholder="name@example.com">
                <label for="floatingInput">Сумма, руб
                    <span class="fw-bold text-danger">*</span>
                </label>
            </div>

            <div class="form-floating mb-2">
                        <textarea class="form-control"
                                  style="min-height:200px;"
                                  maxlength="255"
                                  required
                                  v-model="linkForm.description"
                                  placeholder="Leave a comment here"
                                  id="floatingTextarea"></textarea>
                <label for="floatingTextarea">Описание услуги <span
                    style="font-size:12px;"
                    v-if="(linkForm.description||'').length>0">({{ linkForm.description.length }}/255)</span>
                    <span class="fw-bold text-danger">*</span>
                </label>
            </div>

            <div class="divider my-3">Данные о клиенте</div>
            <div class="form-floating mb-2">
                <input type="text"
                       v-model="linkForm.name"
                       class="form-control" id="floatingInput" placeholder="name@example.com">
                <label for="floatingInput">Имя клиента</label>
            </div>
            <div class="form-floating mb-2">
                <input type="text"
                       v-mask="'+7(###) ###-##-##'"
                       v-model="linkForm.phone"
                       class="form-control" id="floatingInput" placeholder="name@example.com">
                <label for="floatingInput">Телефон клиента
                    <span class="fw-bold text-danger">*</span>
                </label>
            </div>

            <div class="form-floating mb-2">
                <input type="text"
                       v-model="linkForm.email"
                       class="form-control" id="floatingInput" placeholder="name@example.com">
                <label for="floatingInput">Почта клиента</label>
            </div>


            <button
                :disabled="sending"
                class="btn btn-primary mb-2 w-100 p-3"><i class="fa-solid fa-paper-plane"></i> Отправить ссылку на
                оплату в чат
            </button>


        </form>


    </div>

</template>
<script>

import {mapGetters} from "vuex";


export default {
    data() {
        return {
            tab: 0,
            sending: false,
            linkForm: {
                phone: null,
                name: null,
                email: null,
                amount: 0,
                description: "Оплата заказа"
            }
        }
    },
    computed: {
        ...mapGetters(['getSelf']),
        tg() {
            return window.Telegram.WebApp;
        },

    },
    mounted() {
        this.tg.BackButton.show()

        this.tg.BackButton.onClick(() => {
            document.querySelectorAll('[data-bs-dismiss="modal"]').forEach(item => item.click())

            this.$router.back()
        })


    },
    methods: {
        submit() {
            this.sending = true
            this.$store.dispatch("sendSBPInvoice", {
                dataObject: this.linkForm
            })
                .then((response) => {
                    this.sending = false

                    this.linkForm.name = null
                    this.linkForm.phone = null
                    this.linkForm.email = null
                    this.linkForm.description = "Оплата заказа"
                    this.linkForm.amount = 0

                    this.$notify({
                        title: "Оплата",
                        text: "Вы успешно отправили ссылку на оплату",
                        type: "success"
                    })
                })
                .catch(() => {
                    this.sending = false
                    this.$notify({
                        title: "Упс...",
                        text: "Что-то пошло не так...",
                        type: "error"
                    })
                })
        },

    }
}
</script>



