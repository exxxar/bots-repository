<script setup>
import Pagination from '@/ClientTg/Components/V1/Pagination.vue';
import ConfigPartnerForm from "@/ClientTg/Components/V2/Admin/Partners/ConfigPartnerForm.vue";
import PartnerProductList from "@/ClientTg/Components/V2/Admin/Partners/PartnerProductList.vue";
</script>
<template>

    <!-- Форма поиска -->
    <form v-on:submit.prevent="applyFilters" class="mt-2 mb-2">

        <div class="input-group mb-2">

            <div class="form-floating">
                <input type="text"
                       class="form-control"
                       placeholder="Поиск партнера"
                       aria-label="Поиск партнера"
                       v-model="search"
                       aria-describedby="button-addon2">
                <label for="floatingInput">Критерии поиска</label>
            </div>


            <button class="btn btn-outline-secondary text-primary"
                    type="submit"
                    id="button-addon2">
                Найти
            </button>
        </div>

        <div class="form-floating my-2">
            <select
                @change="applyFilters"
                v-model="sort.param"
                class="form-select" id="floatingSelect" aria-label="Floating label select example">
                <option value="title">По названию</option>
                <option value="is_active">Активные</option>
                <option value="product_count">По числу товаров</option>
                <option value="updated_at">По дате добавления</option>
                <option value="updated_at">По дате договора</option>
            </select>
            <label for="floatingSelect">Сортировать заказы по</label>
        </div>


    </form>

    <p v-if="sort.param!=null">Направление сортировки:
        <span
            class="fw-bold"
            @click="changeDirection('desc')"
            v-if="sort.direction==='asc'">по возрастанию <i class="fa-solid fa-caret-up"></i></span>
        <span
            class="fw-bold"
            @click="changeDirection('asc')"
            v-if="sort.direction==='desc'">по убыванию <i class="fa-solid fa-caret-down"></i></span>
    </p>

    <template v-if="partners.length>0">

        <!-- Список партнеров -->
        <div class="row row-cols-1">
            <div class="col" v-for="partner in filteredPartners" :key="partner.id">
                <div class="card mb-2" v-bind:class="{'border-danger':partner.before_deleted}">
                    <div class="card-body">
                        <h5 class="card-title fw-bold">{{ partner.title }}</h5>
                        <p class="card-text">
                            <strong>Число товаров: </strong> {{ partner.products.length || 0 }}<br>
                            <strong>Договор работает до: </strong> {{ partner.contract_expiration }}<br>
                            <strong>Статус: </strong>
                            <span
                                :class="{'text-success': partner.is_active, 'text-danger': !partner.is_active}">
                {{ partner.is_active ? 'Активен' : 'Не активен' }}
              </span>
                        </p>
                    </div>
                    <div class="card-footer d-flex justify-content-between align-items-center">
                        <div class="btn-group">
                            <button type="button"
                                    @click="selectPartner(partner)"
                                    class="btn btn-primary"><i class="fa-solid fa-pen-to-square"></i>
                            </button>

                            <button type="button"
                                    @click="selectPartnerForProductObserve(partner)"
                                    class="btn btn-primary"><i class="fa-solid fa-store"></i>
                            </button>
                        </div>


                        <!-- Активность -->
                        <div class="form-switch form-check">
                            <input
                                type="checkbox"
                                class="form-check-input"
                                :id="'is_active-partner-'+partner.id"
                                v-model="partner.is_active"
                            />
                            <label class="form-check-label" :for="'is_active-partner-'+partner.id">
                                {{ partner.is_active ? 'Активен' : 'Не активен' }}
                            </label>
                        </div>

                        <button type="button"
                                @click="selectPartnerForRemove(partner)"
                                class="btn btn-danger"><i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <Pagination
            :simple="true"
            v-on:pagination_page="nextPartners"
            v-if="partners_paginate_object"
            :pagination="partners_paginate_object"/>
    </template>


    <!-- Modal -->
    <div class="modal fade" id="config-partner-modal" tabindex="-1" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Настройка партнера</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ConfigPartnerForm
                        v-if="selected"
                        :partner="selected"></ConfigPartnerForm>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="products-partner-modal" tabindex="-1" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Настройка товаров</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <PartnerProductList v-if="selected" :partner="selected"></PartnerProductList>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="remove-partner-modal" tabindex="-1" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered ">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Удаление партнера</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <template v-if="selected">
                        <p class="mb-2">Вы точно хотите удалить <strong
                            class="fw-bold text-primary">{{ selected.title || 'этого партнера' }}</strong>?</p>

                        <div class="w-100 d-flex justify-content-center">
                            <button
                                @click="removePartner"
                                style="margin-right:10px;"
                                class="btn btn-danger" type="button">Да, удалить
                            </button>
                            <button
                                data-bs-dismiss="modal"
                                class="btn btn-secondary" type="button">Нет, отменить
                            </button>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>


</template>

<script>
import {mapGetters} from "vuex";

export default {
    data() {
        return {
            sort: {
                param: 'id',
                direction: 'desc'
            },
            selected: null,
            search: null,
            config_modal: null,
            remove_modal: null,
            partners: [], // Заглушка для списка партнеров
            partners_paginate_object: null,
        };
    },
    computed: {
        ...mapGetters(['getPartners', 'getPartnersPaginateObject']),

        filteredPartners() {
            if (!this.search)
                return this.partners

            return this.partners.filter((partner) => {
                const matchesSearchQuery = partner.title.toLowerCase().includes((this.search || '').toLowerCase());
                const matchesStatus = this.sort.param ? partner.status === this.sort.param : true;
                return matchesSearchQuery && matchesStatus;
            });
        },
    },
    mounted() {

        this.config_modal = new bootstrap.Modal(document.getElementById('config-partner-modal'));
        this.remove_modal = new bootstrap.Modal(document.getElementById('remove-partner-modal'));
        this.products_modal = new bootstrap.Modal(document.getElementById('products-partner-modal'));
        this.loadPartners()
    },
    methods: {
        changeDirection(direction) {
            this.sort.direction = direction
            this.loadPartners(0)
        },
        nextPartners(index) {
            this.loadPartners(index)
        },
        selectPartnerForProductObserve(item){
            this.selected = null

            this.$nextTick(() => {
                this.selected = item
                this.products_modal.show()
            })
        },
        selectPartnerForRemove(item) {
            this.selected = null

            this.$nextTick(() => {
                this.selected = item
                this.remove_modal.show()
            })
        },
        selectPartner(item) {
            this.selected = null

            this.$nextTick(() => {
                this.selected = item
                this.config_modal.show()
            })
        },
        loadPartners(pageIndex = 0) {

            this.$store.dispatch("loadPartners", {
                dataObject: {
                    search: this.search,
                    order_by: this.sort.param || null,
                    direction: this.sort.direction || 'asc'
                },
                page: pageIndex
            }).then(resp => {
                this.partners = this.getPartners || []
                this.partners_paginate_object = this.getPartnersPaginateObject || null
            }).catch(() => {

            })
        },
        removePartner() {

            if (!this.selected)
                return

            let preparedIndex = this.partners.findIndex(item => item.id === this.selected.id)

            if (preparedIndex !== -1)
                this.partners[preparedIndex].before_deleted = true

            this.$store.dispatch("removePartner", {
                partnerId: this.selected.id
            }).then(resp => {
                this.$notify({
                    title: 'Управление партнерами',
                    text: 'Партнер успешно удален!',
                    type: 'success'
                })
                this.remove_modal.hide()
                this.loadPartners()
            }).catch(() => {
                this.$notify({
                    title: 'Управление партнерами',
                    text: 'Ошибка удаления партнера!',
                    type: 'error'
                })
                this.remove_modal.hide()
            })


        },
        // Заглушка для загрузки партнеров с сервера
        loadPartnersTest() {
            // Здесь будет запрос к серверу, например:
            // axios.get('/api/partners').then(response => { this.partners = response.data; });

            // Для демонстрации создадим несколько партнеров
            this.partners = [
                {
                    id: 1,
                    bot_name: 'Телега1',
                    product_count: 120,
                    contract_expiration: '2026-12-31',
                    status: 'active',
                },
                {
                    id: 2,
                    bot_name: 'Телега2',
                    product_count: 80,
                    contract_expiration: '2025-05-15',
                    status: 'inactive',
                },
                {
                    id: 3,
                    bot_name: 'Телега3',
                    product_count: 200,
                    contract_expiration: '2027-11-01',
                    status: 'active',
                },
                {
                    id: 4,
                    bot_name: 'Телега4',
                    product_count: 50,
                    contract_expiration: '2024-08-19',
                    status: 'inactive',
                },
            ];
        },

        // Применение фильтров
        applyFilters() {
            this.loadPartners(); // Перезагружаем партнеров после применения фильтров (поиск или статус)
        },
    },

};
</script>

<style scoped>
/* Добавьте стили, если необходимо */
</style>
