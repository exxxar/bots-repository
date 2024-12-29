<script setup>
import SelectOffice from "@/ClientTg/Components/V2/Admin/Cdek/SelectOffice.vue";
import SelectBoxSize from "@/ClientTg/Components/V2/Admin/Cdek/SelectBoxSize.vue";
import PreloaderV1 from "@/ClientTg/Components/V2/Shop/Other/PreloaderV1.vue";
</script>
<template>

    <h3>Расчёт цены CDEK</h3>

    <h6>Офис отправления</h6>
    <SelectOffice v-on:callback="selectOffice($event, 0)"/>

    <h6>Офис получения</h6>
    <SelectOffice v-on:callback="selectOffice($event, 1)"/>

    <div class="form-check form-switch mt-3 mb-2">
        <input class="form-check-input"
               v-model="calcTariffForm.is_shop_mode"
               type="checkbox" role="switch" id="flexSwitchCheckDefault">
        <label class="form-check-label" for="flexSwitchCheckDefault">Режим интернет-магазина</label>
    </div>

    <SelectBoxSize
        :shop-mode="calcTariffForm.is_shop_mode"
        v-on:callback="selectSize"/>

    <template v-if="calcTariffForm.packages.length>0">
        <h6 class="my-2">Ваши посылки</h6>
        <ul class="list-group my-2">
            <li
                v-for="(item, index) in calcTariffForm.packages"
                class="list-group-item d-flex justify-content-between">
            <span>
                    {{ item.width || 0 }}x{{ item.height || 0 }}x{{
                    item.length || 0
                }} см, до {{ item.weight || 0 }}кг
            </span>
                <span
                    @click="removeItem(index)"
                    class="text-danger"><i class="fa-regular fa-trash-can"></i></span>
            </li>
        </ul>
    </template>

    <div class="alert alert-light" v-if="!calcTariffForm.tariff">
        Тариф еще не выбран!
    </div>
    <div class="alert alert-light" v-else>
        <div class="d-flex justify-content-between">

            <p class="fw-bold "> {{ calcTariffForm.tariff.tariff_name }} <span
                class="badge bg-primary">#{{ calcTariffForm.tariff.tariff_code }}</span></p>

            <span
                @click="calcTariffForm.tariff = null"
                class="text-danger"><i class="fa-regular fa-trash-can"></i></span>
        </div>

        <p class="mb-0">Доставка от <span
            class="fw-bold text-primary">{{ calcTariffForm.tariff.period_min }}</span> до <span
            class="fw-bold text-primary">{{ calcTariffForm.tariff.period_max }}</span> рабочих дней</p>
        <p class="mb-0">Стоимость доставки <span
            class="fw-bold text-primary">{{ calcTariffForm.tariff.delivery_sum }}</span> руб.</p>

    </div>

    <nav class="navbar navbar-expand-sm fixed-bottom p-3 bg-transparent border-0"
         style="border-radius:10px 10px 0px 0px;">
        <button
            v-if="calcTariffForm.tariff==null"
            :disabled="!canCalcTariff"
            style="box-shadow: 1px 1px 6px 0px #0000004a;"
            @click="submitTariffCalcCdek"
            class="btn btn-primary w-100 p-3 rounded-3 shadow-lg d-flex justify-content-center ">
            Рассчитать тариф
        </button>
        <button
            v-if="calcTariffForm.tariff!=null"
            :disabled="!canCalcTariff"
            style="box-shadow: 1px 1px 6px 0px #0000004a;"
            @click="openOrderModal"
            class="btn btn-primary w-100 p-3 rounded-3 shadow-lg d-flex justify-content-center ">
            Оформить заказ
        </button>
    </nav>
    <!--    <form
            v-on:submit.prevent="submitCdek"
            class="row py-3">
            <div class="col-md-6 col-12 mb-2">

                <div class="form-floating">
                    <input type="text" class="form-control"
                           placeholder="account"
                           aria-label="account"
                           v-model="calcTariffForm.account"
                           required
                           aria-describedby="account">
                    <label class="form-label" id="account">
                        Аккаунт
                    </label>
                </div>

            </div>
            <div class="col-md-6 col-12 mb-2">
                <div class="form-floating">
                    <input type="text" class="form-control"
                           placeholder="secure_password"
                           aria-label="secure_password"
                           v-model="calcTariffForm.secure_password"
                           required
                           aria-describedby="secure_password">
                    <label class="form-label" id="secure_password">
                        Пароль приложения
                    </label>
                </div>
            </div>
            <div class="col-md-6 mb-2">

                <div class="form-check form-switch">
                    <input class="form-check-input"
                           v-model="calcTariffForm.is_active"
                           type="checkbox" role="switch" id="is_active">
                    <label class="form-check-label" for="is_active">Статус
                        <span v-if="calcTariffForm.is_active" class="fw-bold text-primary">вкл</span>
                        <span v-else class="fw-bold text-primary">выкл</span>
                    </label>
                </div>

            </div>

            <div class="col-md-12 col-12">
                <button type="submit" class="btn btn-outline-primary p-3 w-100">
                    <i class="fa-solid fa-cloud-arrow-down mr-1"></i> Сохранить настройку
                </button>
            </div>
        </form>-->
    <!-- Modal -->
    <div class="modal fade" id="choose-tariff-modal" tabindex="-1" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Выбор тарифа</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" v-if="tariffs.length>0">
                    <ol class="list-group list-group-numbered">
                        <li
                            v-for="item in tariffs"
                            class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">{{ item.tariff_name }}</div>

                                <p class="mb-0">Доставка от <span
                                    class="fw-bold text-primary">{{ item.period_min }}</span> до <span
                                    class="fw-bold text-primary">{{ item.period_max }}</span> рабочих дней</p>
                                <p class="mb-0">Стоимость доставки <span
                                    class="fw-bold text-primary">{{ item.delivery_sum }}</span> руб.</p>

                                <button
                                    type="button"
                                    class="btn btn-primary w-100 mt-3"
                                    @click="selectTariff(item)">Выбрать тариф
                                </button>
                            </div>
                            <span class="badge text-bg-primary rounded-pill">#{{ item.tariff_code }}</span>

                        </li>

                    </ol>
                </div>
                <div class="modal-body" v-else>
                    <PreloaderV1/>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="make-order-modal" tabindex="-1" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <form class="modal-content" v-on:submit.prevent="submitCdek">
                <div class="modal-header">
                    <h1 class="modal-title fs-5"
                        id="exampleModalLabel">Оформление заказа</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="divider my-3">Данные отправителя</div>
                    <div class="form-floating mb-2">
                        <input type="text"
                               v-model="calcTariffForm.sender_name"
                               class="form-control" id="floatingInput" placeholder="name@example.com" required>
                        <label for="floatingInput">Ф.И.О. отправителя</label>
                    </div>

                    <template v-for="(item, index) in calcTariffForm.sender_phones">

                        <div class="input-group mb-2">
                            <div class="form-floating">
                                <input type="text"
                                       v-mask="'+7(###)###-##-##'"
                                       v-model="calcTariffForm.sender_phones[index]"
                                       class="form-control" id="floatingInput"
                                       required
                                       placeholder="name@example.com">
                                <label for="floatingInput">+7(000)000-00-00</label>
                            </div>
                            <span
                                @click="removeSenderPhone(index)"
                                class="input-group-text" id="basic-addon1"><i class="fas fa-trash"></i></span>
                        </div>



                    </template>
                    <a
                        @click="addSenderPhone"
                        href="javascript:void(0)">+ Ещё номер телефона</a>

                    <div class="divider my-3">Данные получателя</div>
                    <div class="form-floating mb-2">
                        <input type="text"
                               v-model="calcTariffForm.recipient_name"
                               class="form-control" id="floatingInput" placeholder="name@example.com" required>
                        <label for="floatingInput">Ф.И.О. получателя</label>
                    </div>

                    <template v-for="(item, index) in calcTariffForm.recipient_phones">

                        <div class="input-group mb-2">
                            <div class="form-floating">
                                <input type="text"
                                       v-mask="'+7(###)###-##-##'"
                                       v-model="calcTariffForm.recipient_phones[index]"
                                       class="form-control" id="floatingInput" placeholder="name@example.com" required>
                                <label for="floatingInput">+7(000)000-00-00</label>
                            </div>
                            <span
                                v-if="calcTariffForm.recipient_phones.length>1"
                                @click="removeRecipientPhone(index)"
                                class="input-group-text" id="basic-addon1"><i class="fas fa-trash"></i></span>
                        </div>



                    </template>
                    <a
                        @click="addRecipientPhone"
                        href="javascript:void(0)">+ Ещё номер телефона</a>

                    <div class="form-floating my-2">
                        <input type="text"
                               v-model="calcTariffForm.to.address"
                               class="form-control" id="floatingInput" placeholder="name@example.com">
                        <label for="floatingInput">Адрес получателя</label>
                    </div>
                    <div class="divider my-3">Комментарий к заказу</div>
                    <div class="form-floating">
                        <textarea
                            v-model="calcTariffForm.comment"
                            class="form-control" placeholder="Leave a comment here" id="floatingTextarea2" style="height: 100px"></textarea>
                        <label for="floatingTextarea2">Комментарий к заказу</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary w-100 p-3">Зарегистрировать заказ</button>
                </div>
            </form>
        </div>
    </div>
</template>
<script>

import {mapGetters} from "vuex";

export default {

    data() {
        return {
            tariffs: [],
            chooseTariffModal: null,
            makeOrderModal: null,
            calcTariffForm: {
                is_shop_mode: true,
                comment: null,
                tariff: null,
                from: {
                    region: null,
                    city: null,
                    office: null,
                    address: null,
                },
                to: {
                    region: null,
                    city: null,
                    office: null,
                    address: null,
                },
                packages: [],
                sender_name:null,
                sender_phones:[],
                recipient_name:null,
                recipient_phones:[""],
            }
        }
    },
    watch: {
        'calcTariffForm.packages': {
            handler: function (newValue) {
                this.calcTariffForm.tariff = null
            },
            deep: true
        }
    },
    computed: {
        canCalcTariff() {
            return this.calcTariffForm.packages.length > 0 &&
                (
                    this.calcTariffForm.from.region != null &&
                    this.calcTariffForm.from.city != null// &&
                    //  this.calcTariffForm.from.office != null
                ) &&
                (
                    this.calcTariffForm.to.region != null &&
                    this.calcTariffForm.to.city != null //&&
                    // this.calcTariffForm.to.office != null
                )
        }
    },
    mounted() {
        this.chooseTariffModal = new bootstrap.Modal(document.getElementById('choose-tariff-modal'), {})
        this.makeOrderModal = new bootstrap.Modal(document.getElementById('make-order-modal'), {})

        if (localStorage.getItem("cashman_calc-tariff-form-from") != null) {
            let from = JSON.parse(localStorage.getItem("cashman_calc-tariff-form-from"))

            this.calcTariffForm.to.region = from?.region || null
            this.calcTariffForm.to.city = from?.city || null
            this.calcTariffForm.to.office = from?.office || null
        }

        if (localStorage.getItem("cashman_calc-tariff-form-to") != null) {
            let to = JSON.parse(localStorage.getItem("cashman_calc-tariff-form-to"))

            this.calcTariffForm.to.region = to?.region || null
            this.calcTariffForm.to.city = to?.city || null
            this.calcTariffForm.to.office = to?.office || null
        }

    },
    methods: {
        addSenderPhone(){
            this.calcTariffForm.sender_phones.push("")
        },
        addRecipientPhone(){
            this.calcTariffForm.recipient_phones.push("")
        },
        removeRecipientPhone(index){
            this.calcTariffForm.recipient_phones.splice(index, 1)
        },
        removeSenderPhone(index){
            this.calcTariffForm.sender_phones.splice(index, 1)
        },
        openOrderModal() {
            this.makeOrderModal.toggle()
        },
        selectTariff(item) {
            this.calcTariffForm.tariff = item
            this.chooseTariffModal.hide()
        },
        selectOffice(event, direction) {
            let param = direction === 0 ? "from" : "to"
            this.calcTariffForm[param].region = event.region
            this.calcTariffForm[param].city = event.city
            this.calcTariffForm[param].office = event.office

            this.calcTariffForm.tariff = null
            //this.loadCdekOffices(direction)
        },
        selectSize(event) {
            this.calcTariffForm.packages.push({
                width: event.width || 0,
                height: event.height || 0,
                length: event.length || 0,
                weight: event.weight || 0,
                items: event.items || []
            })
        },
        removeItem(index) {
            this.calcTariffForm.packages.splice(index, 1)
        },
        submitCdek() {

            let data = new FormData();
            Object.keys(this.calcTariffForm)
                .forEach(key => {
                    const item = this.calcTariffForm[key] || ''
                    if (typeof item === 'object')
                        data.append(key, JSON.stringify(item))
                    else
                        data.append(key, item)
                });


            this.$store.dispatch("storeCdekOrder", {
                cdekForm: data
            }).then((response) => {
                localStorage.setItem("cashman_calc-tariff-form-tariff", JSON.stringify(this.calcTariffForm.tariff))
                localStorage.setItem("cashman_calc-tariff-form-from", JSON.stringify(this.calcTariffForm.from))
                localStorage.setItem("cashman_calc-tariff-form-to", JSON.stringify(this.calcTariffForm.to))

                this.$notify({
                    title: "Работа с CDEK",
                    text: "Данные успешно сохранены",
                    type: "success",
                });
            }).catch(err => {
                this.$notify({
                    title: "Работа с CDEK",
                    text: "Ошибка работы",
                    type: "error",
                });
            })


        },
        submitTariffCalcCdek() {
            this.chooseTariffModal.show();
            this.tariffs = []
            let data = new FormData();
            Object.keys(this.calcTariffForm)
                .forEach(key => {
                    const item = this.calcTariffForm[key] || ''
                    if (typeof item === 'object')
                        data.append(key, JSON.stringify(item))
                    else
                        data.append(key, item)
                });


            this.$store.dispatch("calcCdekTariff", {
                cdekForm: data
            }).then((response) => {

                this.tariffs = response

                this.$notify({
                    title: "Работа с CDEK",
                    text: "Данные успешно сохранены",
                    type: "success",
                });
            }).catch(err => {
                this.$notify({
                    title: "Работа с CDEK",
                    text: "Ошибка работы",
                    type: "error",
                });
            })


        }
    }
}
</script>
