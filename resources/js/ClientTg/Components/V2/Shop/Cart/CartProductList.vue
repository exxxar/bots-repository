<script setup>
import ProductCardSimple from "@/ClientTg/Components/V2/Shop/ProductCardSimple.vue";
import CollectionCardSimple from "@/ClientTg/Components/V2/Shop/CollectionCardSimple.vue";
</script>
<template>
    <div class="container py-3">
        <div class="row">
            <div class="col-12">
                <slot name="upper-text"></slot>
            </div>
        </div>
        <div class="row">
            <div class="col-12" v-for="(item, index) in cartProducts">

                <ProductCardSimple
                    :comment="item.comment"
                    v-if="(item.product||null)!=null"
                    :config="item.params"
                    :item="item.product">
                    <template #partner>
                        <p class="mb-0" v-if="item.partner">
                            <span class="fst-italic" style="font-size:10px;">{{ item.partner.title || '-' }}</span>
                        </p>
                        <p class="mb-0" v-else>
                            <span class="fst-italic" style="font-size:10px;">{{ bot.title || '-' }}</span>
                        </p>
                    </template>
                </ProductCardSimple>

                <CollectionCardSimple
                    :comment="item.comment"
                    v-if="(item.collection||null)!=null"
                    :params="item.params"
                    :item="item.collection">
                    <template #partner>
                        <p class="mb-0" v-if="item.partner">
                            <span class="fst-italic" style="font-size:10px;">{{ item.partner.title || '-' }}</span>
                        </p>

                    </template>
                </CollectionCardSimple>


            </div>

        </div>


        <template
            v-if="settings.need_prizes_from_wheel_of_fortune && (filteredActionData||[]).length>0">
            <h6 class="opacity-75 mb-2 mt-2 ">Выбор приза Колеса фортуны</h6>
            <Carousel v-bind="config">
                <Slide :key="'empty-prize'">
                    <div
                        @click="openPrizeModal(null)"
                        v-bind:class="{'border-primary':formData.action_prize==null}"
                        class="card mx-1 p-3 align-items-center" style="height:190px;border-color:#e9ecef;">
                        <img v-lazy="'/images/chest.png'" class="card-img-top  object-fit-cover w-100" alt="...">
                        <div class="card-body">
                            <p class="card-text text-primary" style="font-size:12px;">Приз не выбран</p>
                        </div>
                    </div>
                </Slide>
                <Slide v-for="slide in filteredActionData" :key="slide">
                    <div
                        @click="openPrizeModal(slide)"
                        v-bind:class="{'border-primary':(formData.action_prize?.prize||{description:null}).description == slide.description}"
                        class="card  mx-1 p-3 align-items-center" style="height:190px;border-color:#e9ecef;">
                        <img v-lazy="'/images/wheel.png'"
                             class="card-img-top object-fit-cover w-100" alt="...">
                        <div class="card-body">
                            <p class="card-text text-primary" style="font-size:10px;">{{ slide.description || '-' }}</p>
                        </div>
                    </div>
                </Slide>
                <template #addons>
                    <Navigation/>
                    <Pagination/>
                </template>
            </Carousel>
        </template>

        <template v-if="!simpleMode">
            <div class="card mb-3">
                <div class="card-body">
                    <h6 class="mb-2">Товаров в корзине <strong class="fw-bold">{{ cartTotalCount }} ед.</strong></h6>
                    <h6 class="mb-0">Общая цена товаров <strong class="fw-bold">{{
                            cartTotalPrice - formData.discount
                        }}₽</strong>
                        <strong
                            v-if="(formData.discount||0)>0"
                            class="text-success fw-bold"> (-{{ formData.discount }} ₽
                            )</strong>
                    </h6>

                </div>
            </div>

            <slot name="recommendation-list"></slot>

            <button
                v-if="cartTotalCount>0"
                @click="clearCart"
                class="btn btn-outline-primary p-3 w-100">
                <i class="fa-solid  fa-trash-can mr-2"></i><span class="color-white">Очистить корзину</span>
            </button>
        </template>

    </div>


    <!-- Modal -->
    <div class="modal fade" id="take-prize-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Использование приза</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Вы точно хотите использовать этот приз сейчас?
                    <p class="alert alert-info my-">
                        Внимание! Призы со скидкой будут применены к текущей корзине, если товара по скидке нет, то он
                        будет добавлен в корзину и применена скидка к нему.
                    </p>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                    <button type="button"
                            @click="selectPrize"
                            class="btn btn-primary">Использовать
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import {mapGetters} from "vuex";
import 'vue3-carousel/dist/carousel.css'
import {Carousel, Slide, Pagination, Navigation} from 'vue3-carousel'

export default {
    props: ["formData", "simpleMode"],
    components: {
        Carousel, Slide, Pagination, Navigation
    },
    computed: {
        ...mapGetters(['cartProducts', 'cartTotalCount', 'cartTotalPrice']),
        filteredActionData() {
            if (!this.action)
                return []

            if (!this.action.data)
                return []

            return this.action.data.filter(item => !item.taked_at)
        },
        bot() {
            return window.currentBot
        },
        settings() {
            return this.bot.settings
        },
    },
    data() {
        return {
            selected_prize: null,
            prize_modal: null,
            config: {
                itemsToShow: 2.5
            },
            display_promo_code: false,
            action: null
        }
    },
    mounted() {
        this.prize_modal = new bootstrap.Modal(document.getElementById('take-prize-modal'), {})
        this.loadClientNotUsedPrizes()

    },
    methods: {
        openPrizeModal(item) {
            this.selected_prize = null
            this.$nextTick(() => {
                this.selected_prize = item

                if (this.selected_prize!=null)
                    this.prize_modal.show()
            })


        },

        selectPrize() {

            const item = this.selected_prize
            if (item == null) {
                this.prize_modal.hide()
                return
            }
            this.$store.dispatch("useWheelOfFortunePrize", {
                form: {
                    action_prize: item,
                    action_id: this.action.id,
                }
            }).then((resp) => {
                this.action = resp.action

                this.$emit("select-prize", {
                    prize: item,
                    action_id: this.action.id
                })

                this.selected_prize = null
                this.prize_modal.hide()
            }).catch(()=>{
                this.prize_modal.hide()
            })


        },
        loadClientNotUsedPrizes() {
            if (!this.settings.selected_script_id)
                return

            this.$store.dispatch("getClientNotUsedPrizesFromWheelOfFortune", {
                slug_id: this.settings.selected_script_id
            }).then((resp) => {
                this.action = resp.action
            })

        },


        clearCart() {
            this.$emit("change-tab", 1)
            this.$store.dispatch("clearCart").then(() => {
                this.$notify({
                    title: "Корзина",
                    text: "Корзина успешно очищена",
                    type: "success"
                })
            })

        },
    }
}
</script>
