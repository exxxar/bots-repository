<script setup>

import Pagination from '@/AdminPanel/Components/Pagination.vue';
</script>
<template>

    <div class="row py-2">
        <div class="col-12">
            <div class="input-group mb-3">
                <input type="search" class="form-control "
                       placeholder="Поиск кода"
                       aria-label="Поиск кода"
                       v-model="search"
                       aria-describedby="promocode-search-and-filter">
                <button class="btn btn-outline-secondary "
                        @click="loadPromoCodes(0)"
                        type="button"
                        id="promocode-search-and-filter">Найти
                </button>
            </div>
        </div>
        <div class="col-12">
            <table class="table" v-if="codes.length>0">
                <thead>

                <tr>
                    <th scope="col" class="cursor-pointer" @click="loadAndOrder('id')">#</th>
                    <th scope="col" class="cursor-pointer" @click="loadAndOrder('code')">Код</th>
                    <th scope="col" class="cursor-pointer" @click="loadAndOrder('description')">Описание</th>
                    <th
                        v-if="getSelf.is_admin"
                        scope="col" class="cursor-pointer" @click="loadAndOrder('slot_amount')">Число слотов
                    </th>
                    <th scope="col" class="cursor-pointer" @click="loadAndOrder('cashback_amount')">Величина CashBack-а,
                        руб
                    </th>
                    <th scope="col" class="cursor-pointer" @click="loadAndOrder('max_activation_count')">Максимальное
                        число активаций
                    </th>
                    <th scope="col" class="cursor-pointer">Число активаций</th>
                    <th scope="col" class="cursor-pointer" @click="loadAndOrder('is_active')">Активный</th>
                    <th scope="col">Действие</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(code, index) in codes"
                    v-bind:class="{'border-info':code.deleted_at==null,'border-danger':code.deleted_at!=null}">
                    <th scope="row">{{ code.id }}</th>
                    <td @click="selectEvent(code)">{{ code.code || 'Не указано' }}
                    </td>
                    <td>{{ code.description || 'Не указано' }}</td>

                    <td v-if="getSelf.is_admin">
                        {{ code.slot_amount }}
                    </td>
                    <td>
                        {{ code.cashback_amount }}
                    </td>

                    <td>
                        {{ code.max_activation_count }}
                    </td>

                    <td>
                        {{ code.current_activation_count || 0 }}
                    </td>
                    <td>
                        <i class="fa-solid fa-chevron-down text-success" v-if="code.is_active"></i>
                        <i class="fa-solid  fa-xmark text-danger" v-else></i>
                    </td>

                    <td>
                        <div class="dropdown" v-if="code.id">
                            <button class="btn btn-outline-secondary" type="button" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                <i class="fa-solid fa-ellipsis"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li v-if="code.deleted_at==null">
                                    <a class="dropdown-item"
                                       @click="removeQuiz(code.id)"
                                       href="javascript:void(0)">Удалить</a></li>
                            </ul>
                        </div>

                    </td>
                </tr>


                </tbody>
            </table>

            <div v-else>
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <strong>Внимание!</strong> Вы еще не добавили ни одного промокода!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <div class="position-relative p-5 text-center text-muted bg-body border border-dashed rounded-2">

                    <div class="d-flex justify-content-center mb-3">
                        <img v-lazy="'../images/icon.png'" alt="" width="100" height="100">
                    </div>


                    <h1 class="text-body-emphasis">Создание промокода</h1>
                    <p class="col-lg-8 mx-auto fs-5 text-muted">
                        Промокод - это инструмент мотивации пользователей пользоваться вашим сервисом. Он позволяет
                        клиентам получать некоторые бонусы за активацию кода, а вы в свою очередь будете видеть
                        статистику активация прмокода.
                    </p>
                    <div class="d-inline-flex gap-2 mb-5">
                        <button
                            @click="createPromoCode"
                            class="d-inline-flex align-items-center btn btn-lg px-4 rounded-pill btn-primary"
                            type="button">
                            Добавить
                        </button>
                        <a href="#"
                           target="_blank"
                           class="d-inline-flex align-items-center btn btn-outline-secondary btn-lg px-4 rounded-pill"
                        >
                            Подробнее
                        </a>
                    </div>
                </div>

            </div>
        </div>
        <div class="col-12" v-if="codes.length>0">
            <Pagination
                v-on:pagination_page="nextPromoCodes"
                v-if="paginate_object"
                :pagination="paginate_object"/>
        </div>
    </div>

</template>
<script>
import {mapGetters} from "vuex";

export default {
    props: ["bot"],
    data() {
        return {
            direction: 'desc',
            order: 'updated_at',
            show: true,
            loading: true,
            codes: [],
            search: null,
            paginate_object: null,
        }
    },

    computed: {
        ...mapGetters(['getPromoCodes', 'getPromoCodesPaginateObject']),
        getSelf() {
            return window.profile
        }

    },
    mounted() {
        this.loadPromoCodes();
    },
    methods: {
        removeQuiz(id) {
            this.loading = true
            this.$store.dispatch("removePromoCodes", {
                promoCodeId: id

            }).then(resp => {
                this.loading = false
                this.loadPromoCodes(0)
                this.$notify("Промокод успешно удален");
            }).catch(() => {
                this.loading = false
                this.$notify("Ошибка удаления промокода")
            })
        },

        createPromoCode() {
            this.$emit("create")
        },
        nextPromoCodes(index) {
            this.loadPromoCodes(index)
        },
        selectEvent(code) {
            this.$emit("select", code)
        },
        loadAndOrder(order) {
            this.order = order
            this.direction = this.direction === 'desc' ? 'asc' : 'desc'
            this.loadPromoCodes(0)
        },
        loadPromoCodes(page = 0) {
            this.loading = true
            this.$store.dispatch("loadPromoCodes", {
                dataObject: {
                    bot_id: this.bot.id || null,
                    search: this.search,
                    order: this.order,
                    direction: this.direction
                },
                page: page,
                size: 20
            }).then(resp => {
                this.loading = false
                this.codes = this.getPromoCodes
                this.paginate_object = this.getPromoCodesPaginateObject
            }).catch(() => {
                this.loading = false
            })
        }
    }
}
</script>
