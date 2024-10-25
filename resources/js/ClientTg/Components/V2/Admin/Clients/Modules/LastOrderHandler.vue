<script setup>
import BotMediaList from "@/ClientTg/Components/V1/BotMediaList.vue";
import OrderItem from "@/ClientTg/Components/V2/Admin/Orders/OrderItem.vue";
</script>

<template>

    <div class="divider mb-3">Управление заказом</div>

    <template v-if="loaded&&errors.length===0">
        <p class="text-center my-3 fw-bold text-primary d-flex justify-content-between">
            <span>Заказ №{{ orderId }}</span>
            <a
                data-bs-toggle="modal" data-bs-target="#order-details"
                href="javascript:void(0)"><i class="fa-solid fa-circle-info"></i> Детали заказа</a>
        </p>
        <div class="alert alert-danger" v-if="order.status === 0">
            <strong class="fw-bold">Внимание!</strong> Заказ еще не взят в работу! Выберите актуальный статус из
            предложенных ниже!
        </div>
        <div class="btn-group mb-2 w-100" role="group">
            <button type="button"
                    v-bind:class="{'current-step-success text-primary fw-bold':order.status === 5}"
                    @click="changeStatus(5)"
                    class="btn btn-outline-primary p-3">Принять в работу
            </button>

            <button type="button"
                    data-bs-toggle="modal" data-bs-target="#message-settings"
                    class="btn btn-outline-primary w-auto d-flex justify-content-center align-items-center"
                    style="max-width:60px;" aria-expanded="false">
                <i class="fa-solid fa-cogs"></i>
            </button>
        </div>

        <div class="btn-group mb-2 w-100" role="group">
            <button type="button"
                    v-bind:class="{'current-step text-primary fw-bold':order.status === 4}"
                    @click="changeStatus(4)"
                    class="btn btn-outline-primary p-3">Передать на доставку
            </button>

            <button type="button"
                    data-bs-toggle="modal" data-bs-target="#message-settings"
                    class="btn btn-outline-primary w-auto d-flex justify-content-center align-items-center"
                    style="max-width:60px;" aria-expanded="false">
                <i class="fa-solid fa-cogs"></i>
            </button>
        </div>

        <div class="btn-group mb-2 w-100" role="group">
            <button type="button"
                    v-bind:class="{'current-step text-primary fw-bold':order.status === 1}"
                    @click="changeStatus(1)"
                    class="btn btn-outline-primary p-3">Доставляется
            </button>

            <button type="button"
                    data-bs-toggle="modal" data-bs-target="#message-settings"
                    class="btn btn-outline-primary w-auto d-flex justify-content-center align-items-center"
                    style="max-width:60px;" aria-expanded="false">
                <i class="fa-solid fa-cogs"></i>
            </button>
        </div>


        <div class="btn-group mb-2 w-100" role="group">
            <button type="button"
                    v-bind:class="{'current-step text-primary fw-bold':order.status === 2}"
                    @click="changeStatus(2)"
                    class="btn btn-outline-primary p-3">Прибыло к заказчику
            </button>

            <button type="button"
                    data-bs-toggle="modal" data-bs-target="#message-settings"
                    class="btn btn-outline-primary w-auto d-flex justify-content-center align-items-center"
                    style="max-width:60px;" aria-expanded="false">
                <i class="fa-solid fa-cogs"></i>
            </button>
        </div>


        <div class="btn-group mb-2 w-100"
             @click="autoCashback"
             role="group">
            <button type="button"
                    :disabled="order.is_cashback_crediting||spent_time_counter>0"
                    v-bind:class="{'current-step-success text-primary fw-bold':order.is_cashback_crediting}"
                    class="btn btn-outline-primary p-3">
                <span
                    v-if="spent_time_counter<=0"
                    class="color-white">Начислить CashBack</span>
                <span
                    v-else
                    class="color-white">Осталось ждать {{ spent_time_counter || 0 }} сек.</span>
            </button>

            <button type="button"
                    data-bs-toggle="modal" data-bs-target="#message-settings"
                    class="btn btn-outline-primary w-auto d-flex justify-content-center align-items-center"
                    style="max-width:60px;" aria-expanded="false">
                <i class="fa-solid fa-cogs"></i>
            </button>
        </div>

        <div class="divider my-3">Отмена заказа</div>
        <div class="btn-group mb-2 w-100" role="group">
            <button type="button"
                    v-bind:class="{'current-step-danger text-danger fw-bold':order.status === 3}"
                    @click="changeStatus(3)"
                    class="btn btn-outline-danger p-3"><i class="fa-solid fa-triangle-exclamation mr-2"></i> Отклонить
                заказ
            </button>

            <button type="button"
                    data-bs-toggle="modal" data-bs-target="#message-settings"
                    class="btn btn-outline-primary w-auto d-flex justify-content-center align-items-center"
                    style="max-width:60px;" aria-expanded="false">
                <i class="fa-solid fa-cogs"></i>
            </button>
        </div>

        <button type="button"
                @click="declineOrder"
                class="btn btn-outline-danger p-3 w-100 mb-3"><i class="fa-solid fa-triangle-exclamation mr-2"></i>
            Закрыть заказ без оповещения
        </button>

    </template>
    <div class="alert alert-light d-flex flex-column align-items-center justify-content-center" v-else>
        <template v-if="errors.length===0">
            Данные о заказе еще не загружены!

            <div class="spinner-border text-primary mt-3"

                 role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </template>
        <template v-else>
            <p class="mb-0" v-for="error in errors"><i class="fa-solid fa-triangle-exclamation text-danger"></i>
                {{ error }}</p>
        </template>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="order-details" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
         aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Детали заказа</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <OrderItem
                        v-if="loaded"
                        :item="order"></OrderItem>
                </div>

            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="message-settings" tabindex="-1" aria-labelledby="message-settings" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <form class="modal-content" v-on:submit.prevent="storeTextConfig">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Настройка сообщений</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-floating mb-2">
                        <textarea class="form-control"
                                  v-model="settings.order_status_5"
                                  maxlength="512"
                                  placeholder="Leave a comment here" id="floatingTextarea2"
                                  style="height: 100px"></textarea>
                        <label for="floatingTextarea2">Заказ принят в работу
                            <span
                                v-if="(settings.order_status_5||'').length>0">{{
                                    settings.order_status_5.length
                                }}/512</span>
                        </label>
                    </div>

                    <div class="form-floating mb-2">
                        <textarea class="form-control"
                                  v-model="settings.order_status_1"
                                  maxlength="512"
                                  placeholder="Leave a comment here" id="floatingTextarea2"
                                  style="height: 100px"></textarea>
                        <label for="floatingTextarea2">Заказ передан на доставку
                            <span
                                v-if="(settings.order_status_1||'').length>0">{{
                                    settings.order_status_1.length
                                }}/512</span>
                        </label>
                    </div>


                    <div class="form-floating mb-2">
                        <textarea class="form-control"
                                  v-model="settings.order_status_4"
                                  maxlength="512"
                                  placeholder="Leave a comment here" id="floatingTextarea2"
                                  style="height: 100px"></textarea>
                        <label for="floatingTextarea2">Заказ доставляется
                            <span
                                v-if="(settings.order_status_4||'').length>0">{{
                                    settings.order_status_4.length
                                }}/512</span>
                        </label>
                    </div>


                    <div class="form-floating mb-2">
                        <textarea class="form-control"
                                  v-model="settings.order_status_2"
                                  maxlength="512"
                                  placeholder="Leave a comment here" id="floatingTextarea2"
                                  style="height: 100px"></textarea>
                        <label for="floatingTextarea2">Заказ был доставлен
                            <span
                                v-if="(settings.order_status_2||'').length>0">{{
                                    settings.order_status_2.length
                                }}/512</span>
                        </label>
                    </div>


                    <div class="form-floating mb-2">
                        <textarea class="form-control"
                                  v-model="settings.order_status_3"
                                  maxlength="512"
                                  placeholder="Leave a comment here" id="floatingTextarea2"
                                  style="height: 100px"></textarea>
                        <label for="floatingTextarea2">Заказ отклонен
                            <span
                                v-if="(settings.order_status_3||'').length>0">{{
                                    settings.order_status_3.length
                                }}/512</span>
                        </label>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary w-100 p-3"
                            data-bs-dismiss="modal"><i class="fa fa-floppy-o mr-2" aria-hidden="true"></i> Сохранить
                        настройки
                    </button>
                </div>
            </form>
        </div>
    </div>

</template>
<script>
import {mapGetters} from "vuex";

export default {
    props: ["orderId", "botUser"],
    data() {
        return {
            loaded: true,
            order: {
                status: 0
            },
            errors: [],
            spent_time_counter: 0,
            settings: {
                order_status_0: null, //NewOrder
                order_status_1: null, //InDelivery
                order_status_2: null, //Completed
                order_status_3: null, //Decline
                order_status_4: null, //ReadyForDelivery
                order_status_5: null, //StartsCooking
            }

        }
    },
    computed: {
        currentBot() {
            return window.currentBot
        }
    },
    mounted() {
        if (this.orderId)
            this.loadOrderById()

        if (this.currentBot.config) {
            this.settings.order_status_0 = this.currentBot.config.order_status_0 || null
            this.settings.order_status_1 = this.currentBot.config.order_status_1 || null
            this.settings.order_status_2 = this.currentBot.config.order_status_2 || null
            this.settings.order_status_3 = this.currentBot.config.order_status_3 || null
            this.settings.order_status_4 = this.currentBot.config.order_status_4 || null
            this.settings.order_status_5 = this.currentBot.config.order_status_5 || null
        }

        if (localStorage.getItem("cashman_order_cashback_add_counter") != null) {
            this.startTimer(localStorage.getItem("cashman_order_cashback_add_counter") || 0)
        }
    },
    methods: {
        loadOrderById() {
            this.errors = []
            this.loaded = false
            this.$store.dispatch("loadOrderById", {
                dataObject: {
                    order_id: this.orderId
                }
            }).then((resp) => {
                this.order = resp.data
                this.loaded = true
            }).catch(() => {
                this.$notify({
                    title: 'Упс...!',
                    text: 'Ошибка загрузки заказа',
                    type: 'error'
                })

                this.errors.push(`Заказ #${this.orderId} не найден в системе!`)
            })
        },
        startTimer(time) {
            this.spent_time_counter = time != null ? Math.min(time, 10) : 10;

            let counterId = setInterval(() => {
                    if (this.spent_time_counter > 0)
                        this.spent_time_counter--
                    else {
                        clearInterval(counterId)
                        this.is_requested = false
                        this.spent_time_counter = null
                    }
                    localStorage.setItem("cashman_order_cashback_add_counter", this.spent_time_counter)
                }, 1000
            )
        },
        autoCashback() {
            this.startTimer();
            this.$store.dispatch("addCashBackToOrder", {
                order_id: this.orderId
            }).then(() => {
                this.order.is_cashback_crediting = true
                this.$notify({
                    title: "Отзывы",
                    text: "Вы успешно начислили CashBack пользователю",
                    type: "success"
                })
            }).catch(() => {
                this.order.is_cashback_crediting = false
                this.$notify({
                    title: "Отзывы",
                    text: "Ошибка начисления CashBack",
                    type: "error"
                })
            })
        },
        storeTextConfig() {
            this.$store.dispatch("storeMessageSettings", {
                dataObject: {
                    settings: this.settings
                }
            }).then((resp) => {
                this.$notify({
                    title: 'Отлично!',
                    text: 'Данные успешно сохранены',
                    type: 'success'
                })
            }).catch(() => {
                this.$notify({
                    title: 'Упс...!',
                    text: 'Ошибка сохранения данных',
                    type: 'error'
                })
            })
        },
        declineOrder(){
            this.$store.dispatch("declineOrder", {
                dataObject: {
                    order_id: this.orderId,
                }
            }).then((resp) => {
                this.$notify({
                    title: 'Отлично!',
                    text: 'Вы отклонили заказ и изменили его статус!',
                    type: 'success'
                })

            }).catch(() => {

                this.$notify({
                    title: 'Упс...!',
                    text: 'Ошибка смены статуса заказа!',
                    type: 'error'
                })
            })
        },
        changeStatus(status) {
            this.order.status = status
            this.$store.dispatch("changeOrderStatus", {
                dataObject: {
                    order_id: this.orderId,
                    user_telegram_chat_id: this.botUser.telegram_chat_id || null,
                    status: status
                }
            }).then((resp) => {
                this.$notify({
                    title: 'Отлично!',
                    text: 'Вы изменили статус заказа',
                    type: 'success'
                })

            }).catch(() => {

                this.$notify({
                    title: 'Упс...!',
                    text: 'Ошибка смены статуса заказа!',
                    type: 'error'
                })
            })
        },

    }
}
</script>
<style>
.current-step {
    background-image: repeating-linear-gradient(-45deg, #eee 0, #eee 15px, #fff 15px, #fff 25px);
}

.current-step-success {
    background-image: repeating-linear-gradient(-45deg, rgba(221, 255, 162, 0.89) 0, rgba(221, 255, 162, 0.89) 15px, #fff 15px, #fff 25px);
}

.current-step-danger {
    background-image: repeating-linear-gradient(-45deg, rgba(255, 162, 162, 0.89) 0, rgba(255, 162, 162, 0.89) 15px, #fff 15px, #fff 25px);
}
</style>
