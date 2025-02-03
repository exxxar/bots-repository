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
                    v-if="(item.product||null)!=null"
                    :item="item.product"/>

                <CollectionCardSimple
                    v-if="(item.collection||null)!=null"
                    :params="item.params"
                    :item="item.collection"/>
            </div>
        </div>

        <template
            v-if="settings.need_prizes_from_wheel_of_fortune && (filteredActionData||[]).length>0">
            <h6 class="opacity-75 mb-2 mt-2 ">Выбор приза Колеса фортуны</h6>
            <Carousel v-bind="config">
                <Slide :key="'empty-prize'">
                    <div
                        @click="selectPrize(null)"
                        v-bind:class="{'border-primary':formData.action_prize==null}"
                        class="card mx-1 p-3 align-items-center" style="height:250px;border-color:#e9ecef;">
                        <img v-lazy="'/images/chest.png'" class="card-img-top  object-fit-cover w-100" alt="...">
                        <div class="card-body">
                            <p class="card-text text-primary">Приз не выбран</p>
                        </div>
                    </div>
                </Slide>
                <Slide v-for="slide in filteredActionData" :key="slide">
                    <div
                        @click="selectPrize(slide)"
                        v-bind:class="{'border-primary':(formData.action_prize?.prize||{description:null}).description == slide.description}"
                        class="card  mx-1 p-3 align-items-center" style="height:250px;border-color:#e9ecef;">
                        <img v-lazy="'/images/wheel.png'"
                             class="card-img-top object-fit-cover w-100" alt="...">
                        <div class="card-body">
                            <p class="card-text text-primary">{{ slide.description || '-' }}</p>
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
            <div class="card my-2">
                <div class="card-body">
                    <h6 class="mb-2">Товаров в корзине <strong class="fw-bold">{{ cartTotalCount }} ед.</strong></h6>
                    <h6 class="mb-0">Общая цена товаров <strong class="fw-bold">{{
                            cartTotalPrice - formData.promo.discount
                        }}₽</strong>
                        <strong
                            v-if="(formData.promo.discount||0)>0"
                            class="text-success fw-bold"> (-{{ formData.promo.discount }}
                            <span v-if="formData.promo.discount_in_percent">%</span>
                            <span v-else>₽</span>
                            )</strong>
                    </h6>

                </div>
            </div>
            <button
                v-if="cartTotalCount>0"
                @click="clearCart"
                class="btn btn-outline-primary p-3 w-100">
                <i class="fa-solid  fa-trash-can mr-2"></i><span class="color-white">Очистить корзину</span>
            </button>
        </template>

    </div>
</template>
<script>
import {mapGetters} from "vuex";
import 'vue3-carousel/dist/carousel.css'
import {Carousel, Slide, Pagination, Navigation} from 'vue3-carousel'

export default {
    props: ["settings", "formData", "simpleMode"],
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
        }
    },
    data() {
        return {
            config: {
                itemsToShow: 1.5
            },
            display_promo_code: false,
            action: null
        }
    },
    mounted() {

        this.loadClientNotUsedPrizes()

    },
    methods: {
        selectPrize(item) {

            if (item == null) {
                this.$emit("select-prize", null)
                return
            }

            this.$emit("select-prize", {
                prize: item,
                action_id: this.action.id
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
