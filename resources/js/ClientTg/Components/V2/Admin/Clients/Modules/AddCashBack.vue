<template>

    <div>
        <button
            type="button"
            data-bs-toggle="modal" data-bs-target="#add-cashback-to-user"
            class="btn btn-outline-light text-primary w-100 p-3">Начислить
            Баллы</button>


        <!-- Modal -->
        <div class="modal fade" id="add-cashback-to-user" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-fullscreen">
                <form v-on:submit.prevent="addCashBack"
                      class="modal-content">

                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Админ. панель</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <p class="mb-2">У пользователя <strong>{{ botUser.cashBack.amount || 0 }} руб</strong>
                            CashBack</p>
                        <p class="mb-2" v-if="botUser.cashBack.subs" v-for="item in botUser.cashBack.subs">
                            {{ item.title || 'Без названия' }} - {{ item.amount || 0 }} руб.
                        </p>

                        <div v-if="currentBot.cashback_config">
                            <h6>В боте поддерживается начисление CashBack по категориям</h6>

                            <div class="form-floating">
                                <select class="form-control mb-2" v-model="cashbackForm.category" required>
                                    <option selected :value="null">Общий CashBack</option>
                                    <option :value="item.title" v-for="item in currentBot.cashback_config">
                                        {{ item.title || 'Без названия' }}
                                    </option>
                                </select>
                                <label for="floatingSelect">Категории CashBack</label>
                            </div>


                            <em>Начисления по реферальной системе происходя только для общего CashBack-а.
                                CashBack-по
                                категориям не суммируется с общим и отображается пользователю отдельно.</em>
                        </div>

                        <div class="form-check my-3">
                            <input class="form-check-input"
                                   v-model="cashbackForm.need_user_review"
                                   type="checkbox" value="" id="need_user_review">
                            <label class="form-check-label" for="need_user_review">
                                Нужен отзыв от пользователя
                            </label>
                        </div>

                        <div class="form-check my-3">
                            <input class="form-check-input"
                                   v-model="cashbackForm.need_custom_percents"
                                   type="checkbox" value="" id="need_custom_cashback_amount">
                            <label class="form-check-label" for="need_custom_cashback_amount">
                                Нужен нестандартный % CashBack
                            </label>
                        </div>

                        <div class="form-floating mb-3" v-if="cashbackForm.need_custom_percents">

                            <input type="number" min="0" class="form-control"
                                   id="bill-percent"
                                   v-model="cashbackForm.percent"
                                   placeholder="Значите %" required>
                            <label for="bill-percent" class="form-label">% CashBack-а</label>
                        </div>

                        <div class="form-floating mb-3">

                            <input type="number" min="0" class="form-control"
                                   id="bill-amount"
                                   v-model="cashbackForm.amount"
                                   placeholder="Сумма" required>
                            <label for="bill-amount" class="form-label">Сумма в чеке, руб</label>
                        </div>

                        <div class="form-floating mb-3">

                            <textarea class="form-control"
                                      placeholder="Информация"
                                      v-model="cashbackForm.info"
                                      id="bill-info" rows="3" required></textarea>
                            <label for="bill-info" class="form-label">Информация о чеке, номер</label>
                        </div>

                        <div class="form-floating mb-3">

                            <textarea class="form-control"
                                      placeholder="Комментарий"
                                      v-model="cashbackForm.message"
                                      id="message" style="min-height:150px;"></textarea>
                            <label for="message" class="form-label">Комментарий для пользователя</label>
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
            cashbackForm: {
                percent: null,
                need_custom_percents: false,
                need_user_review: false,
                category: null,
                amount: null,
                message: null,
                info: null
            }
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
        addCashBack() {
            if (!this.botUser.telegram_chat_id) {
                this.$botNotification.warning("Упс!", "Вы должны выбрать пользователя!")
                return
            }
            this.loading = true;
            this.$store.dispatch("addCashBack", {
                dataObject: {
                    user_telegram_chat_id: this.botUser.telegram_chat_id,
                    ...this.cashbackForm
                }
            }).then((resp) => {
                this.loading = false
                this.cashbackForm.amount = 0
                this.cashbackForm.info = null
                this.cashbackForm.message = null
                this.$emit("callback")
                this.$notify({
                    title: 'Отлично!',
                    text: 'Вы успешно начислили кэшбэк',
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
