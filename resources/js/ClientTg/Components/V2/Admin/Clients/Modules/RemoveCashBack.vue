<template>

    <div>
        <button
            type="button"
            data-bs-toggle="modal" data-bs-target="#remove-cashback-from-user"
            class="btn btn-outline-light text-primary w-100 p-3">Списать
            CashBack</button>

        <!-- Modal -->
        <div class="modal fade" id="remove-cashback-from-user" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-fullscreen">
                <form v-on:submit.prevent="removeCashBack"
                      class="modal-content">

                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Админ. панель</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <p>У пользователя <strong>{{ botUser.cashBack.amount || 0 }} руб</strong> CashBack</p>

                        <p class="mb-2" v-if="botUser.cashBack.subs" v-for="item in botUser.cashBack.subs">
                            {{ item.title || 'Без названия' }} - {{ item.amount || 0 }} руб.
                        </p>

                        <div v-if="currentBot.cashback_config">
                            <h6>В боте поддерживается списание CashBack по категориям</h6>

                            <div class="form-floating mb-3" >
                                <select class="form-select" id="floatingSelect" v-model="cashbackForm.category" required>
                                    <option selected :value="null">Общий CashBack</option>
                                    <option :value="item.title" v-for="item in currentBot.cashback_config">
                                        {{ item.title || 'Без названия' }}
                                    </option>
                                </select>
                                <label for="floatingSelect">Категория CashBack-а</label>
                            </div>


                        </div>

                        <div class="form-floating mb-3">

                            <input type="number"
                                   min="0"
                                   class="form-control"
                                   id="bill-amount"
                                   v-model="cashbackForm.amount"
                                   placeholder="Сумма" required>
                            <label for="bill-amount" class="form-label">Сумма списания CashBack, руб</label>
                        </div>

                        <div class="form-floating mb-3">

                            <textarea class="form-control"
                                      placeholder="Информация"
                                      v-model="cashbackForm.info"
                                      style="min-height:200px;"
                                      id="bill-info" rows="3" required></textarea>
                            <label for="bill-info" class="form-label">Причина списания</label>
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
                category: null,
                amount: null,
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
        removeCashBack() {
            if (!this.botUser.telegram_chat_id) {
                this.$botNotification.warning("Упс!", "Вы должны выбрать пользователя!")
                return
            }
            this.loading = true;
            this.$store.dispatch("removeCashBack", {
                dataObject: {
                    user_telegram_chat_id: this.botUser.telegram_chat_id,
                    ...this.cashbackForm
                }
            }).then((resp) => {
                this.loading = false
                this.cashbackForm.amount = 0
                this.cashbackForm.info = null

                this.$notify({
                    title: 'Отлично!',
                    text: 'Вы успешно списали кэшбэк',
                    type: 'success'
                })

                this.$emit("callback")

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
