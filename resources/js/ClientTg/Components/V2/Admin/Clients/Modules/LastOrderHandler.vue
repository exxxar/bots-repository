<script setup>
import BotMediaList from "@/ClientTg/Components/V1/BotMediaList.vue";
</script>

<template>


    <template v-if="loaded">
        <div class="btn-group mb-2 w-100" role="group" aria-label="Button group with nested dropdown">
            <button type="button"
                    v-bind:class="{'current-step text-primary fw-bold':order.status === 5}"
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

        <div class="btn-group mb-2 w-100" role="group" aria-label="Button group with nested dropdown">
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

        <div class="btn-group mb-2 w-100" role="group" aria-label="Button group with nested dropdown">
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


        <div class="btn-group mb-2 w-100" role="group" aria-label="Button group with nested dropdown">
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
             role="group" aria-label="Button group with nested dropdown">
            <button type="button" class="btn btn-outline-primary p-3">Автоматический CashBack</button>

            <button type="button"
                    data-bs-toggle="modal" data-bs-target="#message-settings"
                    class="btn btn-outline-primary w-auto d-flex justify-content-center align-items-center"
                    style="max-width:60px;" aria-expanded="false">
                <i class="fa-solid fa-cogs"></i>
            </button>
        </div>

    </template>
    <div class="alert alert-light d-flex flex-column align-items-center justify-content-center" v-else>
        Данные о заказе еще не загружены!

        <div class="spinner-border text-primary mt-3" role="status">
            <span class="visually-hidden">Loading...</span>
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
                                  v-model="settings.order_status_3"
                                  maxlength="512"
                                  placeholder="Leave a comment here" id="floatingTextarea2"
                                  style="height: 100px"></textarea>
                        <label for="floatingTextarea2">Заказ доставляется
                            <span
                                v-if="(settings.order_status_3||'').length>0">{{
                                    settings.order_status_3.length
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
export default {
    props: ["orderId", "botUser"],
    data() {
        return {
            loaded: true,
            order: {
                status: 0
            },
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
    mounted() {
        if (this.orderId)
            this.loadOrderById()
    },
    methods: {
        loadOrderById() {

        },
        autoCashback() {

        },
        storeTextConfig() {

        },
        changeStatus(status) {
            this.order.status = status
            this.$store.dispatch("changeOrderStatus", {
                dataObject: {
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
</style>
