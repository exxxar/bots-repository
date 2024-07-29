<script setup>
import Pagination from '@/AdminPanel/Components/Pagination.vue';
</script>
<template>

    <div class="row">
        <div class="col-12">
            <div class="input-group mb-2">

                <div class="form-floating">
                    <input type="search"
                           v-model="search"
                           class="form-control border-light" id="floatingInput" placeholder="name@example.com">
                    <label for="floatingInput">Поиск промокода</label>
                </div>

                <button class="btn btn-outline-light text-secondary"
                        @click="loadPromoCodes(0)"
                        type="button"
                        id="promocode-search-and-filter">Найти
                </button>
            </div>
        </div>

    </div>

    <div class="row" >
        <div class="col-12">
            <div class="form-floating">
                <select class="form-select"
                        v-if="order"
                        @change="loadPromoCodes(0)"
                        id="floatingSelect" aria-label="Floating label select example">
                    <option value="id">По номеру</option>
                    <option value="code">Код</option>
                    <option value="description">Описание</option>
                    <option value="cashback_amount">Сумма скидки</option>
                    <option value="is_active">Активные</option>
                    <option value="max_activation_count">Максимальное
                        число активаций
                    </option>
                    <option value="updated_at">По дате обновления</option>
                </select>
                <label for="floatingSelect">Тип сортировки</label>
            </div>

            <p v-if="order!=null" class="mb-2">Направление сортировки:
                <span
                    class="fw-bold"
                    @click="changeDirection('desc')"
                    v-if="direction==='asc'">по возрастанию <i class="fa-solid fa-caret-up"></i></span>
                <span
                    class="fw-bold"
                    @click="changeDirection('asc')"
                    v-if="direction==='desc'">по убыванию <i class="fa-solid fa-caret-down"></i></span>
            </p>
        </div>
    </div>

    <div class="row row-cols-1" v-if="codes.length>0">
        <div class="col mb-2" v-for="(code, index) in codes">
            <div class="card"  >
                <div class="card-header d-flex justify-content-between align-items-center bg-transparent">
                    <span>
                        <i class="fa-solid fa-chevron-down text-success" v-if="code.is_active"></i>
                        <i class="fa-solid  fa-xmark text-danger" v-else></i>
                        #{{ code.id }}
                    </span>

                    <a href="javascript:void(0)"
                       data-bs-toggle="modal" :data-bs-target="'#remove-promo-modal'+index"

                       v-if="code.deleted_at==null"
                       style="font-size:12px;"
                       class="btn btn-link">
                        Удалить
                    </a>

                    <span>  {{ code.current_activation_count || 0 }} / {{ code.max_activation_count }}</span>
                </div>
                <div class="card-body">
                    <h5 @click="selectEvent(code)" class="card-title fw-bold">{{ code.code || 'Не указано' }}</h5>
                    <p class="card-text mb-2 fst-italic">{{ code.description || 'Не указано' }}</p>
                    <p class="card-text mb-0">
                        Скидка:
                        <strong class="fw-bold">-{{ code.cashback_amount }} руб</strong>
                    </p>

                </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" :id="'remove-promo-modal'+index" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Удаление промокода</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Вы действительно хотите удалить {{code.code || code.id ||'этот промокод'}}?</p>

                            <div class="d-flex justify-content-center w-100 flex-row-reverse">
                                <button type="button" class="btn btn-secondary ml-2" data-bs-dismiss="modal">Нет, отменить</button>
                                <button type="button"
                                        @click="removeCode(code.id)"
                                        data-bs-dismiss="modal"
                                        class="btn btn-primary">Да, удалить</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div v-else>
        <div class="alert alert-warning alert-dismissible fade show mb-2" role="alert">
            <strong>Внимание!</strong> Вы еще не добавили ни одного промокода!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <div class="position-relative p-5 text-center text-muted bg-body border border-dashed rounded-2">

            <div class="d-flex justify-content-center mb-3">
                <img v-lazy="'/images/icon.png'" alt="" width="100" height="100">
            </div>


            <h1 class="text-body-emphasis">Создание промокода</h1>
            <p class="col-lg-8 mx-auto fs-6 text-muted">
                Промокод - это инструмент мотивации пользователей пользоваться вашим сервисом. Он позволяет
                клиентам получать некоторые бонусы за активацию кода, а вы в свою очередь будете видеть
                статистику активация прмокода.
            </p>
        </div>

    </div>

    <div class="row">
        <div class="col-12">

            <Pagination
                v-on:pagination_page="nextPromoCodes"
                v-if="paginate_object&&codes.length>0"
                :pagination="paginate_object"/>
        </div>
    </div>


</template>
<script>
import {mapGetters} from "vuex";

export default {
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
        ...mapGetters(['getSelf', 'getPromoCodes', 'getPromoCodesPaginateObject']),
        bot(){
            return window.currentBot
        }

    },
    mounted() {
        this.loadPromoCodes();
    },
    methods: {
        changeDirection(direction) {
            this.direction = direction
            this.loadPromoCodes(0)
        },
        removeCode(id) {
            this.loading = true
            this.$store.dispatch("removePromoCodes", {
                promoCodeId: id

            }).then(resp => {
                this.loading = false
                this.loadPromoCodes(0)

                this.$notify({
                    type:'success',
                    text:"Промокод успешно удален",
                })
            }).catch(() => {
                this.loading = false
                this.$notify({
                    type:'error',
                    text:"Ошибка удаления промокода",
                })
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
