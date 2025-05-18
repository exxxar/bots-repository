<script setup>
import ProfileCard from "@/ClientTg/Components/V2/Shop/ProfileCard.vue";
import ProductCard from "@/ClientTg/Components/V2/Admin/Shop/AdminProductCard.vue";
import PreloaderV1 from "@/ClientTg/Components/V2/Shop/Other/PreloaderV1.vue";
</script>
<template>

    <div class="container">
        <template v-if="table">
            <div class="alert alert-danger my-2 text-black" v-if="table.closed_at!=null">
                Внимание! Данный столик уже <strong class="fw-bold text-primary">закрыт</strong>! Операции со столиком недоступны!
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    <p class="d-flex justify-content-between mb-0">Номер столика <span
                        class="fw-bold text-primary">#{{ table.number || '1' }}</span></p>
                </li>
                <li class="list-group-item">
                    <p class="d-flex justify-content-between mb-0">Столик оформлен на
                        <a
                            v-if="table.creator"
                            href="javascript:void(0)"
                            data-bs-toggle="modal" data-bs-target="#creator-profile-modal"
                            class="fw-bold text-primary" style="text-align:right;">
                            <i class="fa-solid fa-arrow-up-right-from-square"></i>
                            {{ table.creator.fio_from_telegram || '-' }}
                        </a>
                        <span v-else>
                            не указано
                        </span>
                    </p>
                </li>
                <li class="list-group-item">
                    <p class="d-flex justify-content-between mb-0">Столик обслуживает
                        <a
                            data-bs-toggle="modal" data-bs-target="#officiant-profile-modal"
                            v-if="table.officiant"
                            href="javascript:void(0)"
                            style="text-align:right;"
                            class="fw-bold text-primary">
                            <i class="fa-solid fa-arrow-up-right-from-square"></i>
                            {{ table.officiant.name || table.officiant.fio_from_telegram || '-' }}
                        </a>
                        <span v-else>
                            не указано
                        </span>
                    </p>
                </li>
                <li class="list-group-item">
                    <p
                        data-bs-toggle="modal" data-bs-target="#clients-list-modal"
                        class="d-flex justify-content-between mb-0">Кол-во гостей <span
                        class="fw-bold text-primary">{{ clients.length }} чел.</span>
                    </p>
                </li>
                <li class="list-group-item">
                    <p class="d-flex justify-content-between mb-0">Заказано позиций <span
                        class="fw-bold text-primary">{{ summary_count }} ед.</span></p>
                </li>
                <li class="list-group-item">
                    <p
                        data-bs-toggle="modal" data-bs-target="#add-additional-modal"
                        class="d-flex justify-content-between mb-0">Дополнительные услуги <span
                        class="fw-bold text-primary"><i
                        class="fa-solid fa-plus"></i> {{ (table.additional_services || []).length }} ед.</span></p>
                </li>
                <li class="list-group-item">
                    <p class="d-flex justify-content-between mb-0 fw-bold">Итого <span
                        class="fw-bold text-primary">{{ summary_price }} ₽</span></p>
                </li>
            </ul>

            <div class="divider my-3">Действия</div>
            <button
                type="button"
                :disabled="table.closed_at!=null"
                @click="changeTableWaiter"
                class="btn btn-primary w-100 p-3 mb-2">
                <span v-if="table.officiant_id==null">Взять столик в работу</span>
                <span v-else>Перестать обслуживать столик</span>
            </button>
            <div class="alert alert-light mb-2">

                <p class="mb-2">Внимание! Закрывая столик вы подтверждаете что клиент уже завершает посещение заведения
                    и оплатил заказ!</p>

                <div class="form-check form-switch">
                    <input class="form-check-input"
                           v-model="need_automatic_cashback"
                           type="checkbox" role="switch" id="need-automatic-cashback">
                    <label class="form-check-label" for="need-automatic-cashback">Начислить автоматически баллы после закрытия!</label>
                </div>

                <div class="form-check form-switch">
                    <input class="form-check-input"
                           v-model="can_close_table"
                           type="checkbox" role="switch" id="can-close-table">
                    <label class="form-check-label" for="can-close-table">Всё проверено, точно можно закрыть!</label>
                </div>
            </div>
            <button
                type="button"
                :disabled="!can_close_table||table.closed_at!=null"
                @click="closeTable"
                class="btn btn-primary w-100 p-3">
                <i class="fa-solid fa-lock"></i> Закрыть столик
            </button>
            <p class="divider my-3">Заказы клиентов</p>
            <template v-if="basket.length>0">
                <template v-for="item in basket">
                    <p class="my-3">Клиент <span class="fw-bold">{{ item.name || '-' }}</span></p>

                    <ul class="list-group">
                        <li v-for="basketItem in item.basket"
                            class="list-group-item d-flex justify-content-between align-items-center"
                            @click="selectBasketItem(basketItem)"
                            v-bind:class="{'bg-warning text-white fw-bold':(selected||[]).indexOf(basketItem.id)!=-1}"
                        >
                            <span>
                                <i
                                    v-bind:class="{'text-secondary':basketItem.table_approved_at==null,
                                    'text-success':basketItem.table_approved_at!=null}"
                                    class="fa-solid fa-check-double"></i>
                                    {{ basketItem.product?.title || '-'}}
                            </span>

                            <span class="fw-bold" style="font-size:10px;">{{
                                    basketItem.count
                                }}ед. x {{ basketItem.product.current_price }}₽
                            = {{ basketItem.count * basketItem.product.current_price }}₽</span>
                        </li>

                    </ul>

                    <p class="alert alert-light d-flex justify-content-between my-2">Итого по клиенту <span
                        class="fw-bold">{{ item.summary_price || 0 }}₽</span></p>
                </template>
            </template>

            <div class="divider my-3">Действия</div>
            <template v-if="selected.length>0">
                <p>Вы выбрали {{ selected.length || 0 }} позиций
                    <span
                        @click="deselect"
                        class="text-primary">(отменить выделение)</span>
                </p>
                <div class="btn-group-vertical w-100 mb-3">
                    <button type="button"
                            :disabled="table.officiant_id==null||table.closed_at!=null"
                            class="btn btn-outline-primary p-3"><i class="fa-solid fa-xmark"></i> Убрать
                        выбранные из заказ
                    </button>
                    <button type="button"
                            :disabled="table.officiant_id==null||table.closed_at!=null"
                            class="btn btn-outline-primary p-3"><i class="fa-solid fa-plus"></i> Увеличить
                        кол-во в заказе
                    </button>
                    <button type="button"
                            :disabled="table.officiant_id==null||table.closed_at!=null"
                            class="btn btn-outline-primary p-3"><i class="fa-solid fa-hand"></i> Добавить выбранное в стоп-лист
                    </button>
                </div>
            </template>

            <div class="btn-group-vertical w-100">
                <button type="button"
                        @click="acceptTableOrder"
                        :disabled="table.officiant_id==null||table.closed_at!=null"
                        class="btn btn-success p-3"><i class="fa-solid fa-check"></i> Подтвердить заказ
                </button>
                <button
                    @click="declineTableOrder"
                    :disabled="table.officiant_id==null||table.closed_at!=null"
                    type="button" class="btn btn-danger p-3"><i class="fa-solid fa-ban"></i> Отклонить заказ
                </button>
                <button
                    @click="downloadTableData"
                    :disabled="table.officiant_id==null"
                    type="button" class="btn btn-info p-3"><i class="fa-solid fa-download"></i> Скачать данные файлом
                </button>
            </div>


            <!-- Modal -->
            <div class="modal fade" id="creator-profile-modal" tabindex="-1" aria-labelledby="exampleModalLabel"
                 aria-hidden="true">
                <div class="modal-dialog modal-fullscreen">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Профиль клиента</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <ProfileCard
                                :can-edit="false"
                                v-if="table.creator"
                                :data="table.creator"/>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="officiant-profile-modal" tabindex="-1" aria-labelledby="exampleModalLabel"
                 aria-hidden="true">
                <div class="modal-dialog modal-fullscreen">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Профиль официанта</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <ProfileCard
                                :can-edit="false"
                                v-if="table.officiant"
                                :data="table.officiant"/>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="clients-list-modal" tabindex="-1" aria-labelledby="exampleModalLabel"
                 aria-hidden="true">
                <div class="modal-dialog modal-fullscreen">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Посетители за столиком</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <ol class="list-group list-group-numbered" v-if="clients.length>0">
                                <li
                                    v-for="client in clients"
                                    class="list-group-item d-flex justify-content-between align-items-start">
                                    <div class="ms-2 me-auto">
                                        <div class="fw-bold">
                                            {{
                                                client.name || client.fio_from_telegram || client.telegram_chat_id || '-'
                                            }}
                                        </div>
                                        <p class="mb-0">Номер телефона <span
                                            class="fw-bold">{{ client.phone || 'не указан' }}</span></p>
                                        <p class="mb-0">День рождения <span
                                            class="fw-bold">{{ client.birthday || 'не указано' }}</span></p>
                                    </div>
                                    <span class="badge text-bg-primary rounded-pill">#{{ client.id }}</span>
                                </li>

                            </ol>
                        </div>

                    </div>
                </div>
            </div>

            <div class="modal fade" id="add-additional-modal" tabindex="-1" aria-labelledby="exampleModalLabel"
                 aria-hidden="true">
                <div class="modal-dialog modal-fullscreen">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Дополнительные услуги</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form v-on:submit.prevent="pushService">
                                <div class="form-floating mb-3">
                                    <input type="text"
                                           v-model="serviceForm.title"
                                           class="form-control" id="floatingInput"
                                           placeholder="name@example.com" required>
                                    <label for="floatingInput">Описание</label>
                                </div>

                                <div class="form-floating mb-3">
                                    <input type="number"
                                           v-model="serviceForm.price"
                                           class="form-control" id="floatingInput"
                                           placeholder="name@example.com" required>
                                    <label for="floatingInput">Цена, руб</label>
                                </div>

                                <button class="btn btn-primary w-100 p-3">
                                    Создать сервис
                                </button>
                            </form>
                            <template v-if="services.length>0">
                                <h6 class="divider my-3">Добавленные сервисы</h6>
                                <ul class="list-group">
                                    <li class="list-group-item d-flex justify-content-between"
                                        v-for="(service, index) in services">

                                        <span>
                                            {{ service?.title||'-' }}
                                            <strong class="fw-bold">{{ service.price }} ₽</strong>
                                        </span>
                                        <a
                                            @click="removeService(index)"
                                            href="javascript:void(0)"><i class="fa-solid fa-xmark"></i></a>
                                    </li>

                                </ul>
                            </template>

                        </div>
                        <div class="modal-footer">
                            <button
                                type="button"
                                @click="storeServices"
                                :disabled="services.length===0"
                                class="btn btn-primary w-100 p-3">Перенести сервисы в чек
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </template>
        <PreloaderV1 v-else></PreloaderV1>
    </div>


</template>
<script>
export default {
    data() {
        return {
            can_close_table: false,
            need_automatic_cashback: true,
            serviceForm: {
                title: null,
                price: 0,
            },
            services: [],
            summary_price: 0,
            summary_count: 0,
            selected: [],
            table: null,
            clients: [],
            basket: [],
        }
    },
    mounted() {
        this.loadTable()
    },
    methods: {
        closeTable() {
            let tableId = this.$route.params.tableId
            this.$store.dispatch("closeTableOrder", {
                dataObject: {
                    table_id: tableId,
                }
            }).then(resp => {

                this.$notify({
                    title: 'Заказ',
                    text: "Столик успешно закрыт",
                    type: 'success'
                })

                this.loadTable()
            }).catch(() => {
                this.$notify({
                    title: 'Упс!',
                    text: "Ошибка завершения работы столика",
                    type: 'error'
                })
            })
        },
        downloadTableData() {

        },
        acceptTableOrder() {
            this.changeOrderStatus(0)
        },
        declineTableOrder() {
            this.changeOrderStatus(1)
        },
        changeOrderStatus(type) {
            let tableId = this.$route.params.tableId
            this.$store.dispatch("acceptTableOder", {
                dataObject: {
                    table_id: tableId,
                    type: type
                }
            }).then(resp => {

                this.$notify({
                    title: 'Заказ',
                    text: "Заказ успешно передан на кухню",
                    type: 'success'
                })

                this.loadTable()
            }).catch(() => {
                this.$notify({
                    title: 'Упс!',
                    text: "Ошибка передачи заказа",
                    type: 'error'
                })
            })
        },
        storeServices() {
            let tableId = this.$route.params.tableId
            this.$store.dispatch("storeTableAdditionalServices", {
                dataObject: {
                    table_id: tableId,
                    services: this.services
                }
            }).then(resp => {

                this.$notify({
                    title: 'Заказ',
                    text: "Заказ успешно передан на кухню",
                    type: 'success'
                })
                this.services = []
                this.loadTable()
            }).catch(() => {
                this.$notify({
                    title: 'Упс!',
                    text: "Ошибка передачи заказа",
                    type: 'error'
                })
            })
        },
        deselect() {
            this.selected = []
        },
        removeService(index) {
            this.services.splice(index, 1)
        },
        pushService() {
            const form = JSON.parse(JSON.stringify(this.serviceForm))
            this.services.push(form)
            this.serviceForm.price = 0
            this.serviceForm.title = null
        },
        selectBasketItem(basketItem) {

            let index = this.selected.findIndex(item => basketItem.id === item)

            if (index !== -1)
                this.selected.splice(index, 1)
            else
                this.selected.push(basketItem.id)
        },
        changeTableWaiter() {
            let tableId = this.$route.params.tableId
            this.$store.dispatch("changeTableWaiter", {
                dataObject: {
                    table_id: tableId
                }
            }).then(resp => {

                this.$notify({
                    title: 'Смена официанта',
                    text: "Официант успешно изменен",
                    type: 'success'
                })

                this.table = resp.data
            }).catch(() => {
                this.$notify({
                    title: 'Упс!',
                    text: "Ошибка смены официанта",
                    type: 'error'
                })
            })
        },
        loadTable() {
            let tableId = this.$route.params.tableId

            this.$store.dispatch("loadTableData", {
                dataObject: {
                    table_id: tableId
                }
            }).then(resp => {
                this.summary_price = resp.summary_price || 0
                this.summary_count = resp.summary_count || 0
                this.table = resp.table || null
                this.services = [...this.services, ...(this.table.additional_services || [])]
                this.clients = resp.clients || []
                this.basket = resp.basket || []
            })
        }
    }
}
</script>
